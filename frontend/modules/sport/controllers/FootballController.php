<?php
namespace frontend\modules\sport\controllers;
use frontend\modules\sport\components\SportBaseController;

class FootballController extends SportBaseController
{
    public function filters()
    {
        return [
           //'ajaxOnly + all',
        ];
    }

    /**
     * Actions attached to this controller
     *
     * @return array
     */
    public function actions()
    {
        return [
            'index' => ['class' => 'frontend\modules\sport\controllers\actions\football\IndexAction'],
        ];
    }
}