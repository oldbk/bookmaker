<?php
namespace frontend\modules\admin\controllers\actions\akcii;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use CDbCriteria;

class AutoAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $returned = [];

        $criteria = new CDbCriteria();
        $criteria->addSearchCondition('title', Yii::app()->request->getParam('q'));
        /** @var \Akcii[] $Users */
        $Users = \Akcii::model()->findAll($criteria);
        foreach ($Users as $User)
            $returned[] = [
                'label' => $User->getTitle(),
                'id' => $User->getId(),
                'value' => $User->getTitle()
            ];

        Yii::app()->ajax->addOther(['items' => $returned])->send();
    }
}