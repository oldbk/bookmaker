<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 09.02.2015
 * Time: 23:23
 *
 * @var FootballEvent[] $Events
 * @var \frontend\components\FrontendController $this
 */ ?>

<div class="" id="content-replacement">
    <div class="content-main">
        <?php $this->widget(
            'booster.widgets.TbTabs',
            [
                'type' => 'tabs',
                'justified' => true,
                'tabs' => [
                    [
                        'label' => 'Футбол',
                        'content' => '<div id="tab-content">'.$this->renderPartial('main/football', ['Events' => $Events, 'WC18' => $WC18], true).'</div>',
                        'active' => true,
                        'linkOptions' => [
                            'data-link' => Yii::app()->createUrl('/site/index'),
                            'data-type' => 'ajax'
                        ]
                    ],
                    [
                        'label' => 'Теннис',
                        'content' => '<div id="tab-content"></div>',
                        'linkOptions' => [
                            'data-link' => Yii::app()->createUrl('/site/tennis'),
                            'data-type' => 'ajax'
                        ]
                    ],
                    [
                        'label' => 'Баскетбол',
                        'content' => '<div id="tab-content"></div>',
                        'linkOptions' => [
                            'data-link' => Yii::app()->createUrl('/site/basketball'),
                            'data-type' => 'ajax'
                        ]
                    ],
                    [
                        'label' => 'Хоккей',
                        'content' => '<div id="tab-content"></div>',
                        'linkOptions' => [
                            'data-link' => Yii::app()->createUrl('/site/hokkey'),
                            'data-type' => 'ajax'
                        ]
                    ],
                ]
            ]
        ); ?>
    </div>
    <div class="content-side">
        <?php $this->widget('frontend\widgets\coupon\CouponWidget') ?>
        <?php $this->widget('frontend\widgets\event\EventWidget') ?>
    </div>
</div>