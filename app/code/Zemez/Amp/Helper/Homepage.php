<?php

namespace Zemez\Amp\Helper;

class Homepage extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Lib data collection factory
     *
     * @var \Magento\Framework\Data\CollectionFactory
     */
    protected $_dataCollectionFactory;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
    ) {
        $this->_storeManager = $storeManager;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        parent::__construct($context);
    }

    /**
     * Retrieve collection of categories
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection
     */
    public function getTopLevelCategories()
    {
        $rootCategoryId = $this->_storeManager->getStore()->getRootCategoryId();

        return $this->_categoryCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->setOrder('position', 'ASC')
            ->addAttributeToFilter('level', 2)
            ->addAttributeToFilter('is_active', 1)
            ->addAttributeToFilter('include_in_menu', 1)
            ->addFieldToFilter('path', array('like' => "%/{$rootCategoryId}/%"))
            ->addIsActiveFilter();
    }

    /**
     * Retrieve base url for media resources
     * @return [type] [description]
     */
    public function getMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
}