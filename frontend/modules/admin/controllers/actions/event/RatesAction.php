<?php
namespace frontend\modules\admin\controllers\actions\event;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use BettingGroup;
use CDbCriteria;

class RatesAction extends CAction
{
    public $admin_part = false;

    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $line_id = Yii::app()->getRequest()->getParam('line_id');
        $event = Yii::app()->getRequest()->getParam('event_id');
        /** @var \SportEvent $Event */
        $Event = \SportEvent::model()->with('sport')->findByPk($event);
        if (!$Event)
            Yii::app()->getAjax()->addErrors('Событие не найдено')->send();

        $criteria = new CDbCriteria();
        $criteria->addCondition('`userBetting`.event_id = :event_id');
        $criteria->with = [
            'userBetting' => [
                'with' => [
                    'event' => [
                        'with' => ['sport']
                    ],
                ],
                'together' => true,
            ],
            'user'
        ];
        $criteria->params = [':event_id' => $event];

        $pages = new \CPagination(\BettingGroup::model()->count($criteria));
        $pages->pageSize = 50;
        $pages->applyLimit($criteria);

        $models = BettingGroup::model()->findAll($criteria);

        $params = [
            'models' => $models,
            'line_id' => $line_id,
            'sport' => $Event->sport,
            'event' => $Event,
            'pages' => $pages
        ];

        if (Yii::app()->getRequest()->isAjaxRequest)
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('rates', '#content-replacement', $params)
                ->send();
        else
            $this->getController()->render('rates', $params);
    }
}