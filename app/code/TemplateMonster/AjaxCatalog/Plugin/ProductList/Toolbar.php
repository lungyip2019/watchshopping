<?php

/**
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace TemplateMonster\AjaxCatalog\Plugin\ProductList;

use TemplateMonster\AjaxCatalog\Helper\Catalog\View\ContentAjaxResponse;

class Toolbar
{
    /**
     * @var ContentAjaxResponse
     */
    protected $_helper;

    public function __construct(ContentAjaxResponse $helper)
    {
        $this->_helper = $helper;
    }

    /**
     * Add custom options for ToolBar widget.
     *
     * @param \Magento\Catalog\Block\Product\ProductList\Toolbar $subject
     * @param $result
     *
     * @return mixed
     */
    public function afterGetWidgetOptionsJson(\Magento\Catalog\Block\Product\ProductList\Toolbar $subject, $result)
    {
        return $this->_helper->addActiveAjaxFilter($result);
    }
}
