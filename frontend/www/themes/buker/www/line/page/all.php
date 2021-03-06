<?php
/**
 * Created by PhpStorm.
 * User: me
 *
 * @var Sport[] $sports
 * @var SportEvent[] $EventList
 * @var \frontend\components\FrontendController $this
 */?>

<?php foreach ($sports as $sport): ?>
    <tr
        class="head-title hover">
        <td class="sport-event-title">
            <?= $sport->getTitle(); ?>
        </td>
        <td style="width: 10px;"></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 0">
            <table data-type="event-line" class="table list-event head-sticker" id="bet-list">
                <?php $this->renderPartial('eventView.' . $sport->getSportTypeView() . '.public.head.' . $sport->getSportTemplate(), ['models' => $EventList[$sport->getId()]]) ?>
                <tfoot>
                <tr style="border: 0">
                    <td colspan="17"></td>
                </tr>
                </tfoot>
            </table>
        </td>
    </tr>
<?php endforeach; ?>
