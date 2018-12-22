<?php
namespace frontend\modules\admin\controllers\actions\stats;

/**
 * Most basic landing page rendering action possible.
 *
 * @package YiiBoilerplate\Frontend\Actions
 */
use CAction;
use common\helpers\Convert;
use Yii;

class SpecialAction extends CAction
{
    /**
     * What to do when this action will be called.
     *
     * Just render the `index` view file from current controller.
     */
    public function run()
    {
        $kr = $this->getValueByType(\BettingGroup::TYPE_KR);
        $ekr = $this->getValueByType(\BettingGroup::TYPE_EKR);
        //$voucher = $this->getValueByType(\BettingGroup::TYPE_VOUCHER);

        Yii::app()->ajax
            ->addHtml(Convert::getMoneyFormat($kr), '#special-stats-kr', [], false)
            ->addHtml(Convert::getMoneyFormat($ekr), '#special-stats-ekr', [], false)
            //->addReplace(Convert::getMoneyFormat($voucher), '#special-stats-voucher', [], false)
            ->send();
    }

    private function getValueByType($type)
    {
        $post = Yii::app()->getRequest()->getPost('StatFormSpecial', ['start' => null, 'end' => null]);
        $criteria = new \CDbCriteria();
        if ($post['start']) {
            $criteria->addCondition('create_at >= :start');
            $criteria->params = \CMap::mergeArray($criteria->params, [':start' => strtotime($post['start'] . ' 00:00:00')]);
        }
        if ($post['end']) {
            $criteria->addCondition('create_at <= :end');
            $criteria->params = \CMap::mergeArray($criteria->params, [':end' => strtotime($post['end'] . ' 00:00:00')]);
        }
        $criteria->addNotInCondition('user_id', Yii::app()->getUser()->getAdminIds());
        $criteria->addCondition('price_type = :price_type');
        $criteria->addCondition('result_status = :result_status');
        $criteria->params = \CMap::mergeArray($criteria->params, [
            ':price_type' => $type,
            ':result_status' => \BettingGroup::RESULT_WIN
        ]);
        $count = \BettingGroup::model()->count($criteria);
        if ($count == 0)
            return 0;

        $criteria->select = 'sum(`t`.price) as sum';
        /** @var \BettingGroup $model */
        $model = \BettingGroup::model()->find($criteria);
        $sum = 0;
        if ($model->sum)
            $sum = $model->sum;

        return $sum / $count;
    }
}