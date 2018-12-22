<?php
/**
 * Created by PhpStorm.
 * User: me
 *
 * @var \frontend\components\FrontendController $this
 * @var BettingGroup[] $groups
 * @var UserBetting[] $betting
 * @var SportEvent[] $events
 */ ?>

<?php foreach ($groups as $key => $model): ?>
    <?php $this->renderPartial('page/_history_item', [
        'historyList' => $betting[$model->getId()],
        'BettingGroup' => $model,
        'events' => $events[$model->getId()]
    ]); ?>
<?php endforeach; ?>
