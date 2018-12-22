<?php
namespace frontend\modules\admin\controllers\actions\team;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use CDbCriteria;

class DeleteAction extends CAction
{
    public function run()
    {
        $id = Yii::app()->getRequest()->getParam('alias_id');

        $criteria = new \CDbCriteria();
        $criteria->addCondition('id = :id');
        $criteria->params = [':id' => $id];
        \TeamAliasNew::model()->deleteAll($criteria);

        Yii::app()->getAjax()->runJS('alias', ['delete', $id])->send();
    }
}