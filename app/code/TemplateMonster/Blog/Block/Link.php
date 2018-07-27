<?php

/**
 * "Blog" link
 */
namespace TemplateMonster\Blog\Block;

use \Magento\Framework\View\Element\Template\Context;
use \TemplateMonster\Blog\Helper\Data;

/**
 * Class Link
 */
class Link extends \Magento\Framework\View\Element\Html\Link
{
    /**
     * Blog helper
     */

    protected $_blogHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \TemplateMonster\Blog\Helper\Data $blogHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $blogHelper,
        array $data = []
    ) {
        $this->_blogHelper = $blogHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->_blogHelper->isModuleActive() && $this->_blogHelper->isAllowTopLink()) {
            return parent::_toHtml();
        }
        return '';
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->getUrl($this->_blogHelper->getRoute());
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __($this->_blogHelper->getLinkLabel());
    }
}
