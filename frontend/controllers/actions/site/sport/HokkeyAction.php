<?php
namespace frontend\controllers\actions\site\sport;
use CAction;
use Yii;
/**
 * Created by PhpStorm.
 * User: me
 *
 * @method \FrontendBaseController getController()
 */

class HokkeyAction extends CAction
{
    public function run()
    {
        $criteria = new \CDbCriteria();
        $criteria->with = ['sport'];
        $criteria->scopes = ['for_user'];
        $criteria->limit = 15;
        $criteria->index = 'id';
        $criteria->order = '`t`.date_int asc';
        /** @var \iSportEvent[] $Events */
        $Events = \HokkeyEvent::model()->findAll($criteria);

        $criteria = new \CDbCriteria();
        $criteria->addInCondition('type', ['ratio_p1', 'ratio_x', 'ratio_p2']);

        $new_ratio = \SportEventRatio::getByIds(array_keys($Events), \SportEventRatio::POSITION_NEW, $criteria);
        $last_ratio = \SportEventRatio::getByIds(array_keys($Events), \SportEventRatio::POSITION_LAST, $criteria);
        foreach ($Events as $Event) {
            if(isset($new_ratio[$Event->getId()]))
                $Event->getNewRatio()->populateRecord($new_ratio[$Event->getId()], true);
            if(isset($last_ratio[$Event->getId()]))
                $Event->getOldRatio()->populateRecord($last_ratio[$Event->getId()], true);
        }

        $params = ['Events' => $Events];

        $this->getController()->setPageTitle('Главная - Хоккей');
        Yii::app()->getAjax()
            ->addTrigger('page:loaded')
            ->addHtml('main/hokkey', '#content-replacement #tab-content', $params)
            ->send();
    }
}