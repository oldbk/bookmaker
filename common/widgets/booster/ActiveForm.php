<?php
namespace common\widgets\booster;
/**
 * Created by PhpStorm.
 * User: me
 * Date: 16.09.2014
 * Time: 3:13
 */
use Yii;
use TbActiveForm;
use CHtml;

Yii::import('booster.widgets.TbActiveForm');
class ActiveForm extends TbActiveForm
{
    protected function horizontalGroup(&$fieldData, &$model, &$attribute, &$options) {

        $groupOptions = isset($options['groupOptions']) ? $options['groupOptions']: array(); // array('class' => 'form-group');
        self::addCssClass($groupOptions, 'form-group');

        $_attribute = $attribute;
        CHtml::resolveName($model, $_attribute);
        if ($model->hasErrors($_attribute))
            self::addCssClass($groupOptions, 'has-error');

        echo CHtml::openTag('div', $groupOptions);

        self::addCssClass($options['labelOptions'], $options['labelClass'].' control-label');
        if (isset($options['label'])) {
            if (!empty($options['label'])) {
                echo CHtml::label($options['label'], CHtml::activeId($model, $attribute), $options['labelOptions']);
            } else {
                echo '<span class="'.$options['labelClass'].'"></span>';
            }
        } else {
            echo $this->labelEx($model, $attribute, $options['labelOptions']);
        }

        if(isset($options['wrapperHtmlOptions']) && !empty($options['wrapperHtmlOptions']))
            $wrapperHtmlOptions = $options['wrapperHtmlOptions'];
        else
            $wrapperHtmlOptions = $options['wrapperHtmlOptions'] = array();
        $this->addCssClass($wrapperHtmlOptions, 'col-sm-9');
        echo CHtml::openTag('div', $wrapperHtmlOptions);

        if (!empty($options['prepend']) || !empty($options['append'])) {
            $this->renderAddOnBegin($options['prepend'], $options['append'], $options['prependOptions']);
        }

        if (is_array($fieldData)) {
            echo call_user_func_array($fieldData[0], $fieldData[1]);
        } else {
            echo $fieldData;
        }

        if (!empty($options['prepend']) || !empty($options['append'])) {
            $this->renderAddOnEnd($options['append'], $options['appendOptions']);
        }

        if ($this->showErrors && $options['errorOptions'] !== false) {
            echo $this->error($model, $attribute, $options['errorOptions'], $options['enableAjaxValidation'], $options['enableClientValidation']);
        }

        if (isset($options['hint'])) {
            self::addCssClass($options['hintOptions'], $this->hintCssClass);
            echo CHtml::tag($this->hintTag, $options['hintOptions'], $options['hint']);
        }

        echo '</div></div>'; // controls, form-group
    }

    protected function initOptions(&$options, $initData = false)
    {
        parent::initOptions($options, $initData);

        if(!isset($options['labelClass']))
            $options['labelClass'] = 'col-sm-3';
    }
} 