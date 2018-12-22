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

class FootballAction extends CAction
{
    public function run()
    {
        $criteria = new \CDbCriteria();
        $criteria->with = ['sport'];
        $criteria->scopes = ['for_user'];
        $criteria->limit = 15;
        $criteria->index = 'id';
        $criteria->order = '`t`.date_int asc, sport.title asc';
        $criteria->addCondition('t.sport_id != 3389');
        /** @var \iSportEvent[] $Events */
        $Events = \FootballEvent::model()->findAll($criteria);

        $criteria = new \CDbCriteria();
        $criteria->addInCondition('`t`.type', ['ratio_p1', 'ratio_p2', 'ratio_x']);

        $new_ratio = \SportEventRatio::getByIds(array_keys($Events), \SportEventRatio::POSITION_NEW, $criteria);
        $last_ratio = \SportEventRatio::getByIds(array_keys($Events), \SportEventRatio::POSITION_LAST, $criteria);
        foreach ($Events as $Event) {
            if(isset($new_ratio[$Event->getId()]))
                $Event->getNewRatio()->populateRecord($new_ratio[$Event->getId()], true);
            if(isset($last_ratio[$Event->getId()]))
                $Event->getOldRatio()->populateRecord($last_ratio[$Event->getId()], true);
        }


		$WC18 = [];
        if(Yii::app()->getUser()->isAdmin()) {
			$criteria = new \CDbCriteria();
			$criteria->with = ['sport'];
			$criteria->scopes = ['for_user'];
			$criteria->limit = 15;
			$criteria->index = 'id';
			$criteria->order = '`t`.date_int asc, sport.title asc';
			$criteria->addCondition('`t`.sport_id = 3389 and `t`.date_int <= :date_int');
			$criteria->params = [':date_int' => (new \DateTime())->modify('+1 day')->getTimestamp()];
			/** @var \iSportEvent[] $WC18 */
			$WC18 = \FootballEvent::model()->findAll($criteria);

			$criteria = new \CDbCriteria();
			$criteria->addInCondition('`t`.type', ['ratio_p1', 'ratio_p2', 'ratio_x']);
			$new_ratio = \SportEventRatio::getByIds(array_keys($WC18), \SportEventRatio::POSITION_NEW, $criteria);
			$last_ratio = \SportEventRatio::getByIds(array_keys($WC18), \SportEventRatio::POSITION_LAST, $criteria);
			foreach ($WC18 as $Event) {
				if(isset($new_ratio[$Event->getId()]))
					$Event->getNewRatio()->populateRecord($new_ratio[$Event->getId()], true);
				if(isset($last_ratio[$Event->getId()]))
					$Event->getOldRatio()->populateRecord($last_ratio[$Event->getId()], true);
			}
		}


        $params = [
        	'Events' => $Events,
			'WC18' => $WC18
		];

        $this->getController()->setPageTitle('Главная - Спорт');
        if (Yii::app()->getRequest()->getIsAjaxRequest())
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('main', '#content-replacement', $params)
                ->send();
        else
            $this->getController()->render('main', $params);
    }
}