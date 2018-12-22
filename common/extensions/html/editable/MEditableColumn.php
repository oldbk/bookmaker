<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 12.08.14
 * Time: 16:04
 */

namespace common\extensions\html\editable;

use TbEditableColumn;
use Yii;
use CModel;

Yii::import('booster.widgets.TbEditableColumn');
class MEditableColumn extends TbEditableColumn
{
//protected was removed due to https://github.com/vitalets/x-editable-yii/issues/63
    public function renderDataCellContent($row, $data)
    {
        $isModel = $data instanceOf CModel;

        if($isModel) {
            $widgetClass = '\common\extensions\html\editable\MEditableField';
            $options = array(
                'model'        => $data,
                'attribute'    => empty($this->editable['attribute']) ? $this->name : $this->editable['attribute'],
            );

            //if value defined in column config --> we should evaluate it
            //and pass to widget via `text` option: set flag `passText` = true
            $passText = !empty($this->value);
        } else {
            $widgetClass = '\common\extensions\html\editable\MEditable';
            $options = array(
                'pk'           => $data[$this->grid->dataProvider->keyField],
                'name'         => empty($this->editable['name']) ? $this->name : $this->editable['name'],
            );

            $passText = true;
            //if autotext will be applied, do not pass `text` option (pass `value` instead)
            if(empty($this->value) && \TbEditable::isAutotext($this->editable, isset($this->editable['type']) ? $this->editable['type'] : '')) {
                $options['value'] = $data[$this->name];
                $passText = false;
            }
        }

        //for live update
        $options['liveTarget'] = $this->grid->id;

        $options = \CMap::mergeArray($this->editable, $options);

        //if value defined for column --> use it as element text
        if($passText) {
            ob_start();
            parent::renderDataCellContent($row, $data);
            $text = ob_get_clean();
            $options['text'] = $text;
            $options['encode'] = false;
        }

        //apply may be a string expression, see https://github.com/vitalets/x-editable-yii/issues/33
        if (isset($options['apply']) && is_string($options['apply'])) {
            $options['apply'] = $this->evaluateExpression($options['apply'], array('data'=>$data, 'row'=>$row));
        }

        //evaluate htmlOptions inside editable config as they can depend on $data
        //see https://github.com/vitalets/x-editable-yii/issues/40
        if (isset($options['htmlOptions']) && is_array($options['htmlOptions'])) {
            foreach($options['htmlOptions'] as $k => $v) {
                if(is_string($v) && (strpos($v, '$data') !== false || strpos($v, '$row') !== false)) {
                    $options['htmlOptions'][$k] = $this->evaluateExpression($v, array('data'=>$data, 'row'=>$row));
                }
            }
        }

        $this->grid->controller->widget($widgetClass, $options);
    }
} 