<?php
namespace frontend\modules\admin\controllers;

/**
 * Basic "kitchen sink" controller for frontend.
 * It was configured to be accessible by `/site` route, not the `/frontendSite` one!
 *
 * @package YiiBoilerplate\Frontend
 */

use frontend\modules\admin\components\AdminBaseController;

class ProblemController extends AdminBaseController
{
    public function filters()
    {
        return array(
            sprintf('ajaxOnly + ignore, resolve_%s, resolve_%s, resolve_%s',
                \SportEventProblem::PROBLEM_DATE, \SportEventProblem::PROBLEM_FORA, \SportEventProblem::PROBLEM_NO_RESULT),
        );
    }

    /**
     * Actions attached to this controller
     *
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => [
                'class' => 'frontend\modules\admin\controllers\actions\problem\IndexAction'],
            'resolve_' . \SportEventProblem::PROBLEM_DATE => [
                'class' => 'frontend\modules\admin\controllers\actions\problem\resolve\DateAction'],
            'resolve_' . \SportEventProblem::PROBLEM_FORA => [
                'class' => 'frontend\modules\admin\controllers\actions\problem\resolve\ForaAction'],
            'resolve_' . \SportEventProblem::PROBLEM_NO_RESULT => [
                'class' => 'frontend\modules\admin\controllers\actions\problem\resolve\NoResultAction'],
            'ignore' => [
                'class' => 'frontend\modules\admin\controllers\actions\problem\IgnoreAction'],
            'critical' => [
                'class' => 'frontend\modules\admin\controllers\actions\problem\CriticalAction'],
            'critical_ok' => [
                'class' => 'frontend\modules\admin\controllers\actions\problem\CriticalOkAction'],
            'line' => [
                'class' => 'frontend\modules\admin\controllers\actions\problem\LineAction'],
        );
    }
}