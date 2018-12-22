<?php
namespace frontend\modules\admin\controllers\actions\akcii;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\helpers\Convert;
use Yii;

class SendAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $AkciaForm = new \AkciaForm();
        $post = Yii::app()->request->getParam('AkciaForm');
        if ($post) {
            $User = Yii::app()->getUser()->model();

            $t = Yii::app()->db->beginTransaction();
            try {
                $Akcia = \Akcii::model()->findByPk($AkciaForm->getAkciaId());
                if (!$Akcia)
                    Yii::app()->ajax->addErrors('Акция не найдена')->send();

                $AkciaForm->setAttributes($post, false);
                $Transfer = new Transfer($User);
                switch ($AkciaForm->getPriceType()) {
                    case Convert::TYPE_KR:
                        if (!$Transfer->inAkciaKr($AkciaForm->getPrice(), $AkciaForm->getAkciaId())) {
                            Yii::app()->ajax->addErrors('Не удалось зачислить средства');
                            throw new \Exception();
                        }
                        break;
                    case Convert::TYPE_EKR:
                        if (!$Transfer->inAkciaEkr($AkciaForm->getPrice(), $AkciaForm->getAkciaId())) {
                            Yii::app()->ajax->addErrors('Не удалось зачислить средства');
                            throw new \Exception();
                        }
                        break;
                    default:
                        Yii::app()->ajax->addErrors('Валюта не найдена');
                        throw new \Exception();
                        break;
                }

                $t->commit();
                Yii::app()->ajax->addMessage('Средства зачислены');
            } catch (\Exception $ex) {
                $t->rollback();
                \MException::logMongo($ex);
            }

            Yii::app()->ajax->send();
        }
    }
}