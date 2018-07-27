<?php
namespace TemplateMonster\Blog\Block\Sidebar;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use TemplateMonster\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use TemplateMonster\Blog\Model\Url;
use TemplateMonster\Blog\Helper\Data as HelperData;

class Categories extends Template
{
    protected $_registry;

    protected $_urlModel;

    protected $_helper;

    protected $_categoryCollection;

    protected $_categoryCollectionFactory;

    public function __construct(
        Registry $registry,
        HelperData $helper,
        Template\Context $context,
        Url $url,
        CategoryCollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_urlModel = $url;
        $this->_helper = $helper;
        $this->_registry = $registry;
        $this->_categoryCollectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        $this->_prepareCollection();

        return parent::_prepareLayout();
    }

    protected function _prepareCollection()
    {
        $this->_categoryCollection = $this->_categoryCollectionFactory->create()
            ->addFieldToFilter('is_active', true)
            ->addStoreFilter($this->_storeManager->getStore()->getId());;
    }

    public function getCategories()
    {
        return $this->_categoryCollection;
    }

    public function getCategoryUrl($category)
    {
        return $this->getUrl($this->_urlModel->getCategoryRoute($category));
    }

    public function getCategoriesLimit()
    {
        return $this->_helper->getSidebarCategoriesNumber();
    }
}
