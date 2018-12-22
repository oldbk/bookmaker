<?php
namespace frontend\modules\admin\controllers\actions\trash;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use Yii;
use SportEvent;

class RecoveryAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $event = Yii::app()->getRequest()->getParam('event_id');

        $t = Yii::app()->getDb()->beginTransaction();
        try {
            /** @var SportEvent $Event */
            $Event = SportEvent::model()->feature()->with('sport')->findByPk($event);
            if (!$Event) {
                Yii::app()->getAjax()->addErrors('Событие не найдено');
                throw new \Exception();
            }

            if (!$Event->isTrash()) {
                Yii::app()->getAjax()->addErrors('Событие не находится в корзине или уже завершилось.');
                throw new \Exception();
            }

            $Event->setIsTrash(0)
                ->onAfterTrashRecovery = [new \AdminHistory(), 'afterTrashRecovery'];
            if (!$Event->updateAction()) {
                Yii::app()->getAjax()->addErrors($Event);
                throw new \Exception();
            }

            $t->commit();

            $link = Yii::app()->getUser()->getState('return_link', Yii::app()->createUrl('/admin/trash/index'));
            Yii::app()->getAjax()
                ->addMessage('Событие восстановлено из корзины')
                ->runJS('updatePage', $link);
        } catch (\Exception $ex) {
            $t->rollback();
            \MException::logMongo($ex);
        }

        Yii::app()->getAjax()->send();
    }
}