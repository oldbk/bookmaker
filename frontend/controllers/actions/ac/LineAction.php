<?php
namespace frontend\controllers\actions\ac;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use CDbCriteria;

class LineAction extends CAction
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
        $criteria->addSearchCondition('title', Yii::app()->getRequest()->getParam('q'));

        $dependency = new \CDbCacheDependency('SELECT MAX(update_at) FROM {{sport}}');
        $dependency->reuseDependentData = true;
        /** @var \Sport[] $SportList */
        $SportList = \Sport::model()->cache(3600, $dependency)->findAll($criteria);
        foreach ($SportList as $Sport)
            $returned[] = [
                'label' => $Sport->getTitle(),
                'id' => $Sport->getId(),
                'value' => $Sport->getTitle()
            ];

        Yii::app()->getAjax()->addOther(['items' => $returned])->send();
    }
}