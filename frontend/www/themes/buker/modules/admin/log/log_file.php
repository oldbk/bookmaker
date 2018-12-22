<?php
/**
 * Created by PhpStorm.
 *
 * @var \common\components\Controller $this
 */ ?>

<div class="" id="content-replacement">
    <?php
    $this->widget('common.extensions.loganalyzer.LogAnalyzerWidget', [
        //'filters' => array('Текст для фильтрации', 'И еще одно'),
        'title' => 'Анализатор логов' // заголовок виджета
        // 'log_file_path' => 'Абсолютный путь до файла лога'
    ]);
    ?>
</div>