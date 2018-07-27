<?php
namespace TemplateMonster\Blog\Block\Product;

use Magento\Framework\View\Element\Template;
use TemplateMonster\Blog\Model\Url;
use \TemplateMonster\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use TemplateMonster\Blog\Helper\Data as HelperData;

class RelatedPosts extends Template
{
    protected $_registry;

    protected $_postCollectionFactory;

    protected $_collection;

    protected $_urlModel;

    protected $_helper;

    protected $_filterProvider;

    public function __construct(
        HelperData $helper,
        Template\Context $context,
        Url $url,
        PostCollectionFactory $postCollectionFactory,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_urlModel = $url;
        $this->_helper = $helper;
        $this->_registry = $registry;
        $this->_postCollectionFactory = $postCollectionFactory;
        $this->prepareCollection();
        parent::__construct($context, $data);
    }

    public function getProductId()
    {
        $product = $this->_registry->registry('product');
        return $product ? $product->getId() : null;
    }

    protected function prepareCollection()
    {
        $this->_collection = $this->_postCollectionFactory->create();
        $this->_collection->joinRelatedProductTable();
        $this->_collection->addFieldToFilter('related_id', array('eq' => $this->getProductId()));
        //$this->setData('related_posts', $this->_collection);
    }

    public function getRelatedPosts()
    {
        return $this->_collection;
    }

    public function getPostUrl($post)
    {
        return $this->getUrl($this->_urlModel->getPostRoute($post));
    }
}
