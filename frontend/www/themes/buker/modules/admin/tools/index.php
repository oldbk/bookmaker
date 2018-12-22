<?php
use common\extensions\html\MHtml;
/**
 * Created by PhpStorm.
 * User: ice
 * Date: 16.09.2015
 * Time: 0:32
 *
 * @var \common\components\Controller $this
 * @var iSportEvent $Event
 */ ?>

<div class="" id="content-replacement" data-page="admin-tools">
    <div class="row">
        <div class="col-md-6">
            <?php $this->renderPartial('tools/recalc/index') ?>
            <?php $this->renderPartial('tools/simulator/index') ?>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse" data-target="#notepad">
                    Блокнот
                    <div class="pull-right">
                        <span class="caret"></span>
                    </div>
                </div>
                <div class="panel-body collapse in" id="notepad">

                </div>
            </div>
        </div>
    </div>
</div>
