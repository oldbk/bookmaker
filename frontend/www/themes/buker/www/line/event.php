<?php
/**
 * @var \frontend\components\FrontendController $this
 * @var SportEvent[] $team1games
 * @var SportEvent[] $team2games
 * @var FootballEvent $event
 */
?>

<?php $this->renderPartial('eventView.' . $event->getEventTypeView() . '.public.event', [
    'event' => $event,
    'team1games' => $team1games,
    'team2games' => $team2games,
]) ?>
