<?php
/**
 * @var string $content
 * @var \common\components\Controller $this
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <script type="text/javascript" src="<?= Yii::app()->getStatic()->setLibrary('jquery')->getLink('js/jquery-1.10.2.min.js'); ?>"></script>
    <script type="text/javascript" src="http://i.b.oldbk.com/www/buker/js/public/js/i.min.js"></script>
    <?php if(Yii::app()->getUser()->getName() == 'Байт'): ?>
        <!--<script type="text/javascript" src="http://fbug.googlecode.com/svn/lite/branches/firebug1.4/content/firebug-lite-dev.js"></script>-->
    <?php endif; ?>
    <script type="">
        <?php if(in_array(Yii::app()->getUser()->getName(), array('Байт', 'Архитектор'))): ?>
            var _d = true;
        <?php else: ?>
            var _d = false;
        <?php endif; ?>
    </script>
    <!--<script type="text/javascript" src="http://vitalets.github.io/x-editable/assets/jquery/jquery-1.9.1.min.js"></script>-->
    <title><?= $this->getPageTitle() ?></title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <script>$(function () {
            $ajax.setCSRF(<?= CJavaScript::encode(Yii::app()->getRequest()->csrfToken); ?>);
        });</script>
    <style type="text/css">
        @font-face {
            font-family: 'MinusmanC';
            src: url('<?= Yii::app()->getStatic()->setWww()->imageLink('fonts/MinusmanC.woff', true); ?>');
        }

        @font-face {
            font-family: 'Kristen';
            src: url('<?= Yii::app()->getStatic()->setWww()->imageLink('fonts/ITCKRIST.woff', true); ?>');
        }

        @font-face {
            font-family: 'FontAwesome';
            src: url('<?= Yii::app()->getStatic()->setWww()->imageLink('fonts/fontawesome-webfont.woff?v=4.2.0', true); ?>');
            font-weight: normal;
            font-style: normal;
        }
    </style>
    <!--[if lte IE 9]>
    <link rel="stylesheet" type="text/css" href="<?= Yii::app()->getStatic()->setWWW()->getLink('css/ie.css'); ?>"/>
    <![endif]-->
</head>

<body>
<div class="wrapper">
    <div class="nav-bar-panel">
        <div class="logo">
            <img src="<?= Yii::app()->getStatic()->setWww()->imageLink('images/logo.jpg', true) ?>">
        </div>
        <div class="balance" id="balance">
            <div class="label">Счета:</div>
            <span class="glyphicon glyphicon-question-sign pointer" data-toggle="tooltip" data-placement="bottom"
                  title="Кликнете на счет, чтобы выбрать активный"></span>
            <!--<a data-type="<?= User::TYPE_KR ?>"
               data-link="<?= Yii::app()->createUrl('/user/finance/balance', ['type' => User::TYPE_KR]) ?>"
               href="javascript:void(0);"
               class="bl label label-<?= Yii::app()->getUser()->isActiveKr() ? 'active' : 'none'; ?>">
                <span class="kr"><?= Yii::app()->getUser()->getKr() ?></span> кр
            </a>-->
            <a data-type="<?= User::TYPE_EKR ?>"
               data-link="<?= Yii::app()->createUrl('/user/finance/balance', ['type' => User::TYPE_EKR]) ?>"
               href="javascript:void(0);"
               class="bl label label-<?= Yii::app()->getUser()->isActiveEkr() ? 'active' : 'none'; ?>">
                <span class="ekr"><?= Yii::app()->getUser()->getEkr() ?></span> екр
            </a>
            <a data-type="<?= User::TYPE_GOLD ?>"
               data-link="<?= Yii::app()->createUrl('/user/finance/balance', ['type' => User::TYPE_GOLD]) ?>"
               href="javascript:void(0);"
               class="bl label label-<?= Yii::app()->getUser()->isActiveGold() ? 'active' : 'none'; ?>">
                <span class="gold"><?= Yii::app()->getUser()->getGold() ?></span> мон.
            </a>
            <a data-type="ajax" data-history="true" data-link="<?= Yii::app()->createUrl('/user/finance/index'); ?>"
               class="label label-success">Ввод\Вывод</a>
        </div>
        <div class="nickname t">
            <?php if (Yii::app()->getUser()->isAdmin()): ?>
                <?= exec('hostname') . ' - ' . exec('uptime') ?>
            <?php endif; ?>
            <?= Yii::app()->getUser()->getLoginHTML(); ?>
        </div>
        <?php if (Yii::app()->getUser()->isAdmin()): ?>
            <?php $this->widget('common\widgets\info\InfoWidget') ?>
        <?php endif; ?>
    </div>
    <div id="sport-menu">
        <div id="headMenu">
            <?php $this->widget('\common\widgets\menu\MenuWidget', [
                'items' => $this->menu()->getHeadMenu(),
                'view' => 'headMenu'
            ]); ?>
        </div>
    </div>
    <?= $content; ?>
</div>
<div class="modal fade" id="customModal" tabindex="-1" role="dialog" aria-labelledby="customModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" id="replacement"></div>
</div>
<div id="page-dark">
    <div id="page-loader"></div>
</div>
<script>
    $(function () {
        $ajax.setLoaded(<?= CJSON::encode(array_keys(Yii::app()->getClientScript()->getJS())); ?>);
        $ajax.setLoadedCSS(<?= CJSON::encode(Yii::app()->getClientScript()->getCSS()); ?>);
        $("table.list tbody tr:nth-child(odd), table.list-event tbody tr:nth-child(odd)").addClass("odd");
        $("table.list tbody tr:nth-child(even), table.list-event tbody tr:nth-child(even)").addClass("even");
    });
</script>
</body>
</html>