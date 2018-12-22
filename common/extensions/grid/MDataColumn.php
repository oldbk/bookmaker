<?php
namespace common\extensions\grid;
/**
 * Created by PhpStorm.
 */
use Yii;
use CActiveDataProvider;
use CHtml;

Yii::import('booster.widgets.TbDataColumn');
class MDataColumn extends \TbDataColumn
{
    protected function renderHeaderCellContent()
    {
        if ($this->grid->enableSorting && $this->sortable && $this->name !== null) {
            $sort = $this->grid->dataProvider->getSort();
            $label = isset($this->header) ? $this->header : $sort->resolveLabel($this->name);


            if ($sort->resolveAttribute($this->name) !== false)
                $label .= ' <span class="caret"></span>';

            echo $sort->link($this->name, $label, array('class' => 'sort-link', 'data-type' => 'ajax'));
        } else {
            if ($this->name !== null && $this->header === null) {
                if ($this->grid->dataProvider instanceof CActiveDataProvider) {
                    echo CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
                } else {
                    echo CHtml::encode($this->name);
                }
            } else {
                parent::renderHeaderCellContent();
            }
        }
    }
}