<?php
namespace common\factories\parser\validators;
/**
 * Created by PhpStorm.
 */
abstract class Base
{
    /** @var null */
    private $_html = null;

    /** @var \phpQueryObject */
    private $_dom = null;

    /**
     * @param string $html
     */
    public function __construct($html)
    {
        $this->setHtml($html)
            ->setDom(\phpQuery::newDocument($html));
    }

    /**
     * @return null
     */
    protected function getHtml()
    {
        return $this->_html;
    }

    /**
     * @param null $html
     * @return $this
     */
    protected function setHtml($html)
    {
        $this->_html = $html;
        return $this;
    }

    /**
     * @return \phpQueryObject
     */
    protected function getDom()
    {
        return $this->_dom;
    }

    /**
     * @param \phpQueryObject $dom
     * @return $this
     */
    protected function setDom($dom)
    {
        $this->_dom = $dom;
        return $this;
    }

    public function getTemplateName()
    {
        $reflect = new \ReflectionClass($this);
        return strtolower($reflect->getShortName());
    }
}