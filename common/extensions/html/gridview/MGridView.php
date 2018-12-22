<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 09.08.14
 * Time: 3:32
 */

namespace common\extensions\html\gridview;

use TbExtendedGridView;
use Yii;

Yii::import('booster.widgets.TbExtendedGridView');
class MGridView extends TbExtendedGridView
{
    public function renderHiddentotal()
    {
        echo '<div style="display:none;" id="total_items">'.$this->dataProvider->getItemCount().'</div>';
    }

    public function init(){

        parent::init();

        $this->selectionChanged = 'js:function(id){ $("#"+id+" input[type=checkbox]").change(); }';
    }
} 