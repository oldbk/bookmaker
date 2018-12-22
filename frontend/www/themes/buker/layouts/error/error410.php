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
    <script type="text/javascript"
            src="<?= Yii::app()->getStatic()->setLibrary('jquery')->getLink('js/jquery-1.10.2.min.js'); ?>"></script>
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
    <?= $content; ?>
</div>
</body>
</html>