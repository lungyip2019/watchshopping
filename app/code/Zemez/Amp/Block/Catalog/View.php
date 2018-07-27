<?php

namespace Zemez\Amp\Block\Catalog;

use Zemez\Amp\Model\Attribute\Source\Mode;

/**
 * Class View
 * @package Zemez\Amp\Block\Catalog
 */
class View extends  \Magento\Catalog\Block\Category\View
{
    /**
     * Core registry
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * Catalog layer
     * @var \Magento\Catalog\Model\Layer
     */
    protected $catalogLayer;

    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $categoryHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Helper\Category $categoryHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Helper\Category $categoryHelper,
        array $data = []
    ) {
        $this->categoryHelper = $categoryHelper;
        $this->catalogLayer = $layerResolver->get();
        $this->coreRegistry = $registry;
        parent::__construct($context, $layerResolver, $registry, $categoryHelper, $data);
    }
}
