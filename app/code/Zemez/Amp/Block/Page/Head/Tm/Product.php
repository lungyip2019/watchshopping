<?php

namespace Zemez\Amp\Block\Page\Head\Tm;

use Zemez\Amp\Block\Page\Head\Tm\AbstractTm as AbstractTm;

class Product extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * @var \Zemez\Amp\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Catalog\Helper\Product
     */
    protected $_productHelper;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Zemez\Amp\Helper\Data $helper,
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Zemez\Amp\Helper\Data $helper,
        \Magento\Catalog\Helper\Product $productHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_helper = $helper;
        $this->_productHelper = $productHelper;
    }

	/**
	 * Retrieve additional data
	 * @return array
	 */
    public function getTmParams()
    {
        $params = parent::getTmParams();
        $_product = $this->getProduct();

        return array(
            'type' => 'product',
            'url' => $this->_helper->getCanonicalUrl($_product->getProductUrl()),
            'image' => $this->getImage($_product, 'product_page_image_large', [])->getData('image_url'),
            'title' => $this->pageConfig->getTitle()->get(),
            'description' => mb_substr($this->pageConfig->getDescription(), 0, 200, 'UTF-8'),
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function _prepareLayout()
    {
        $product = $this->getProduct();

        if ($this->_productHelper->canUseCanonicalTag()) {
            $productUrl = $product->getUrlModel()->getUrl(
                $product,
                ['_ignore_category' => true]
            );
        } else {
            $productUrl = $product->getUrl();
        }

        $this->pageConfig->addRemotePageAsset(
            $this->_helper->getCanonicalUrl($productUrl),
            'canonical',
            ['attributes' => ['rel' => 'canonical']],
            AbstractTm::DEFAULT_ASSET_NAME
        );

        return parent::_prepareLayout();
    }

}
