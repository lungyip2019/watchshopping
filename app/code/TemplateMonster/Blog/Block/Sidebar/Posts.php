<?php
namespace TemplateMonster\Blog\Block\Sidebar;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use TemplateMonster\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use TemplateMonster\Blog\Model\Url;
use TemplateMonster\Blog\Helper\Data as HelperData;

class Posts extends Template
{
    protected $_registry;

    protected $_urlModel;

    protected $_helper;

    protected $_postCollection;

    protected $_postCollectionFactory;

    public function __construct(
        Registry $registry,
        HelperData $helper,
        Template\Context $context,
        Url $url,
        PostCollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_urlModel = $url;
        $this->_helper = $helper;
        $this->_registry = $registry;
        $this->_postCollectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        $this->_prepareCollection();

        return parent::_prepareLayout();
    }

    protected function _prepareCollection()
    {
        $this->_postCollection = $this->_postCollectionFactory->create()
            ->setPageSize($this->getPostsLimit())
            ->setCurPage(1)
            ->addFieldToFilter('is_visible', true)
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('creation_time','DESC');
    }

    public function getPosts()
    {
        return $this->_postCollection;
    }

    public function getPostUrl($post)
    {
        return $this->getUrl($this->_urlModel->getPostRoute($post));
    }

    public function getPostsLimit()
    {
        return $this->_helper->getSidebarPostsNumber();
    }
}
