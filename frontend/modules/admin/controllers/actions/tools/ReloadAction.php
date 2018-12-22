<?php
namespace frontend\modules\admin\controllers\actions\tools;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\interfaces\iStatus;
use Yii;

class ReloadAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $frame = Yii::app()->getNodeSocket()->getFrameFactory()->createEventFrame();
        $frame->setEventName('exCommand');
        $frame['name'] = 'reload';
        $frame->send();
    }
}