<?php
/**
 * @var \frontend\components\FrontendController $this
 * @var FootballEvent[] $models
 */
?>
<colgroup span="1" width="44"></colgroup>
<colgroup span="1" width="99%"></colgroup>
<colgroup span="1" width="50"></colgroup>
<colgroup span="1" width="50"></colgroup>
<colgroup span="1" width="50"></colgroup>
<colgroup span="1" width="50"></colgroup>
<colgroup span="1" width="50"></colgroup>
<colgroup span="1" width="50"></colgroup>
<colgroup span="1" width="50"></colgroup>
<colgroup span="1" width="50"></colgroup>
<colgroup span="1" width="50"></colgroup>
<thead>
<tr class="odd">
    <th class="text-center">Дата</th>
    <th class="text-center">Событие</th>
    <th class="text-center">Т</th>
    <th class="text-center">Б</th>
    <th class="text-center">М</th>
    <th class="text-center">П1</th>
    <th class="text-center">X</th>
    <th class="text-center">П2</th>
    <th class="text-center">1X</th>
    <th class="text-center">12</th>
    <th class="text-center">X2</th>
</tr>
</thead>
<tbody>
<?php foreach ($models as $Event): ?>
    <tr data-event="<?= $Event->getId() ?>">
        <?php $this->renderPartial('eventView.' . $Event->getEventTypeView() . '.public.body.custom3', ['Event' => $Event]) ?>
    </tr>
<?php endforeach; ?>
</tbody>