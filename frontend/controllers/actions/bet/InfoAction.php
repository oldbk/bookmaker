<?php
namespace frontend\controllers\actions\bet;
/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\factories\DataToViewFactory;
use common\helpers\Convert;
use common\singletons\prices\Prices;
use common\singletons\Settings;
use Yii;
use CDbCacheDependency;
class InfoAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $min_level = Settings::init()->getMinLevel();
        $min_bet = Prices::init()->getMinBet();
        if(Yii::app()->user->getLevel() < $min_level)
            Yii::app()->ajax->addErrors("Ставки доступны с {$min_level} уровня")->send();

        $placeholder = [
            'event' => null,
            'type'  => null,
            'value' => null,
            'num'   => null,
        ];
        $bet_list = \CMap::mergeArray($placeholder, Yii::app()->request->getPost('Bet', []));
        $criteria = new \CDbCriteria();
        $criteria->addCondition('`t`.id = :id');
        $criteria->addCondition('`t`.date_int > :date_int');
        $criteria->scopes = ['for_user'];
        $criteria->with = ['sport'];
        $criteria->params = [':date_int' => time(), ':id' => $bet_list['event']];

        $dependency = new CDbCacheDependency('SELECT MAX(update_at) FROM {{sport_event}}');
        $dependency->reuseDependentData = true;

        /** @var \iSportEvent $model */
        $model = \SportEvent::model()->cache(3600, $dependency)->find($criteria);
        if(!$model)
            Yii::app()->ajax->addErrors('Событие не найдено')->send();
        $model->scenario = 'betting';

        $criteria = new \CDbCriteria();
        $criteria->select = ['event_id', 'type', 'value'];
        $criteria->addCondition('`t`.event_id = :event_id');
        $criteria->addCondition('`t`.position = :position');
        $criteria->params = [
            ':position' => \SportEventRatio::POSITION_NEW,
            'event_id' => $model->getId()
        ];
        $new_ratio = \SportEventRatio::model()
            ->getCommandBuilder()
            ->createFindCommand(\SportEventRatio::model()->tableName(), $criteria)
            ->queryAll();
        $new_ratio_list = [];
        foreach ($new_ratio as $ratio)
            $new_ratio_list[$ratio['type']] = $ratio['value'];
        $model->getNewRatio()->populateRecord($new_ratio_list, true);

        $DataToView = DataToViewFactory::factory($model->getSportType(), ['Event' => $model]);

        $ratio_field = $model->getFieldByAlias($bet_list['type']);
        $ratio_value = $model->getNewRatio()->getAttribute($ratio_field);
        if($ratio_value != $bet_list['value'])
            Yii::app()->ajax->addErrors("Изменился коэфициент на событие {$DataToView->buildMessageResultString($bet_list['type'])}. Новый: {$ratio_value}");

        $ratio_bet = Yii::app()->sport->getMaxBet($model, $ratio_value);
        if($min_bet > 0 && $min_bet > $ratio_bet)
            $ratio_bet = 0.00;

        $view_table = [
            'event_id' => $model->getId(),
            'event_title' => $model->getTeam1(). ' - '.$model->getTeam2(),
            'liga_id' => $model->getSport()->getId(),
            'liga_title' => $model->getSport()->getTitle(),
            'ratio_type' => $bet_list['type'],
            'ratio_type_string' => $DataToView->buildMessageResultString($bet_list['type']),
            'ratio_value' => $bet_list['value'],
            'max_bet' => $ratio_bet,
        ];

        Yii::app()->getAjax()
            ->addOther(['event_info' => $view_table])
            ->menu()
            ->send();
    }
}