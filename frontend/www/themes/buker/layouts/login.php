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
    <!--<script type="text/javascript" src="<?= Yii::app()->getStatic()->setLibrary('jquery')->getLink('js/jquery-1.10.2.min.js'); ?>"></script>-->
    <script type="text/javascript"
            src="http://vitalets.github.io/x-editable/assets/jquery/jquery-1.9.1.min.js"></script>
    <title></title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <script>$(function () {
            $ajax.setCSRF(<?= CJavaScript::encode(Yii::app()->getRequest()->csrfToken); ?>);
        });</script>
    <style type="text/css">
        @font-face {
            font-family: 'MinusmanC';
            src: url('<?= Yii::app()->getStatic()->imageLink('www/'.Yii::app()->getTheme()->name.'/fonts/MinusmanC.woff'); ?>');
        }

        @font-face {
            font-family: 'FontAwesome';
            src: url('<?= Yii::app()->getStatic()->imageLink('www/'.Yii::app()->getTheme()->name.'/fonts/fontawesome-webfont.woff?v=4.2.0'); ?>');
            font-weight: normal;
            font-style: normal;
        }
    </style>
</head>

<body>
<div class="wrapper">
    <div class="middle">
        <?= $content; ?>
    </div>
    <!-- .middle-->
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
    });
</script>
</body>
</html>