<?php
namespace common\extensions\pagination;

/**
 * Created by PhpStorm.
 * User: me
 * Date: 02.10.2014
 * Time: 16:25
 */
use CHtml;
use Yii;

Yii::import('booster.widgets.TbPager');
class Pagination extends \TbPager
{
    public $loader_block = false;
    public $placeholder = false;

    public function init()
    {
        if(!isset($this->htmlOptions['class']))
            $this->htmlOptions['class']='pagination';

        if($this->placeholder)
            $this->htmlOptions['data-placeholder'] = $this->placeholderPageUrl();

        parent::init();
    }

    protected function createPageButton($label,$page,$class,$hidden,$selected)
    {
        if ($hidden || $selected) {
            $class .= ' ' . ($hidden ? 'disabled' : 'active');
        }
        $params = [];
        $anchor = CHtml::link($label, 'javascript:void(0);', $params);

        if(!$selected) {
            if(is_numeric($label)) $params['data-item'] = 'true';
            $params['data-type'] = 'ajax';
            $params['data-link'] = $this->createPageUrl($page);
            if($this->loader_block)
                $params['data-loader'] = 'true';
            $anchor = CHtml::link($label, $this->createPageUrl($page), $params);
        }

        $liOptions = [
            'class' => $class
        ];

        return CHtml::tag('li', $liOptions, $anchor);
    }

    /**
     * @return string
     */
    public function placeholderPageUrl()
    {
        $pages = $this->getPages();
        $params = $pages->params === null ? $_GET : $pages->params;
        $params[$pages->pageVar] = 'page_place';

        return $this->getController()->createUrl($pages->route,$params);
    }
}