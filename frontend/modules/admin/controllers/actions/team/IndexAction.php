<?php
namespace frontend\modules\admin\controllers\actions\team;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use CActiveDataProvider;
use CDbCriteria;
use Team;

class IndexAction extends CAction
{
    public function run()
    {
        $criteria = new \CDbCriteria();
        $criteria->with = ['teamAliases'];

        $dataProvider = new CActiveDataProvider(new Team('search'), [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => '`t`.id desc'
            ]
        ]);

        /** @var \TeamAliasNew[] $NewAliases */
        $NewAliases = \TeamAliasNew::model()->findAll(['order' => 'title asc']);

        $params = [
            'dataProvider' => $dataProvider,
            'newAliases' => $NewAliases
        ];

        Yii::app()->getStatic()->setWww()->registerScriptFile('team.js');
        if (Yii::app()->getRequest()->getIsAjaxRequest())
            Yii::app()->getAjax()
                ->addTrigger('page:loaded')
                ->addReplace('list', '#content-replacement', $params)->send();
        else
            $this->getController()->render('list', $params);
    }
}