<?php

namespace Zemez\Amp\Block\Page\Head\Tm;

class Category extends AbstractTm
{
	/**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $_categoryHelper;

	/**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $coreRegistry,
     * @param \Zemez\Amp\Helper\Data $helper,
     * @param \Magento\Catalog\Helper\Category $categoryHelper
     * @param array $data
     */
    public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Zemez\Amp\Helper\Data $helper,
        \Magento\Catalog\Helper\Category $categoryHelper,
        array $data = []
    ) {
        parent::__construct($context, $coreRegistry, $helper, $data);
        $this->_categoryHelper = $categoryHelper;
    }

	/**
	 * Retrieve additional data
	 * @return array
	 */
    public function getTmParams()
    {
        $params = parent::getTmParams();
        $_category = $this->getCategory();

        return array_merge($params, [
            'type' => 'category',
            'url' => $this->_helper->getCanonicalUrl($_category->getUrl()),
            'image' => (string)$_category->getImageUrl(),
        ]);
    }

    /**
     * Retrieve current category object
     * @return \Magento\Catalog\Model\Category
     */
    public function getCategory()
    {
        return $this->_coreRegistry->registry('current_category');
    }

	/**
     * {@inheritDoc}
     */
    protected function _prepareLayout()
    {
        $category = $this->getCategory();
        $categoryUrl = false;

        if ($category && $category->getId()) {
            $categoryUrl = $category->getUrl();
        }

        if ($categoryUrl && !$this->_categoryHelper->canUseCanonicalTag()) {
            $this->pageConfig->addRemotePageAsset(
                $this->_helper->getCanonicalUrl($categoryUrl),
                'canonical',
                ['attributes' => ['rel' => 'canonical']],
				self::DEFAULT_ASSET_NAME
            );
        }

        return parent::_prepareLayout();
    }
}
