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
use Team;

class AcceptAction extends CAction
{
    public function run()
    {
        $id = Yii::app()->getRequest()->getParam('alias_id');

        $criteria = new \CDbCriteria();
        $criteria->addCondition('id = :id');
        $criteria->params = [':id' => $id];
        /** @var \TeamAliasNew $NewAlias */
        $NewAlias = \TeamAliasNew::model()->find($criteria);
        if (!$NewAlias)
            Yii::app()->getAjax()->addErrors('Неудалось найти алиас')->send();

        $post = Yii::app()->getRequest()->getPost('TeamAliasNew');
        if ($post) {
            $t = Yii::app()->getDb()->beginTransaction();
            try {
                if (isset($post['is_main'])) {
                    $Team = new Team();
                    $Team->setTitle($NewAlias->getTitle())
                        ->save();
                } else {
                    /** @var Team $Parent */
                    $Parent = Team::model()->findByPk($post['parent']);
                    if (!$Parent)
                        Yii::app()->getAjax()->addErrors('Неудалось найти команду')->send();

                    $Team = new \TeamAlias();
                    $Team->setTitle($NewAlias->getTitle())
                        ->setTeamId($Parent->getId())
                        ->save();
                }

                $NewAlias->delete();

                $criteria = new CDbCriteria();
                $criteria->addCondition('team_1 = :team_1 and team_1_id = 0');
                \SportEvent::model()->updateAll(['team_1_id' => $Team->getId(), 'update_at' => time()], $criteria);

                $criteria = new CDbCriteria();
                $criteria->addCondition('team_2 = :team_2 and team_2_id = 0');
                \SportEvent::model()->updateAll(['team_2_id' => $Team->getId(), 'update_at' => time()], $criteria);

                $t->commit();

                Yii::app()->getAjax()->addMessage('Операция завершена')
                    ->runJS('closeModal')
                    ->runJS('updatePage', Yii::app()->createUrl('/admin/team/index'));
            } catch (\Exception $ex) {
                $t->rollback();
                \MException::logMongo($ex);
            }
        }

        $params = [
            'alias' => $NewAlias,
            'team' => Team::model()->findAll()
        ];

        Yii::app()->getAjax()
            ->addReplace('_accept', '#customModal #replacement', $params)
            ->runJS('openCustom')
            ->runJS('select2')->send();
    }
}