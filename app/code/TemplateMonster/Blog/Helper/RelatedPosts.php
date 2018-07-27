<?php
namespace TemplateMonster\Blog\Helper;

use Magento\Framework\View\Element\Template;
use TemplateMonster\Blog\Model\Url;
use \TemplateMonster\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use TemplateMonster\Blog\Helper\Data as HelperData;

class RelatedPosts extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_registry;

    protected $_postCollectionFactory;

    protected $_collection;

    protected $_urlModel;

    protected $_urlBuilder;

    protected $_helper;

    protected $_filterProvider;

    public function __construct(
        HelperData $helper,
        Url $url,
        PostCollectionFactory $postCollectionFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\UrlInterface $urlBuilder
    ) {
        $this->_urlModel = $url;
        $this->_urlBuilder = $urlBuilder;
        $this->_helper = $helper;
        $this->_registry = $registry;
        $this->_postCollectionFactory = $postCollectionFactory;
        $this->prepareCollection();
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
        return $this->_urlBuilder->getUrl($this->_urlModel->getPostRoute($post));
    }

    public function isEnabled()
    {
        return $this->_helper->getRelatedPostsShowLinks();
    }

    public function getRelatedPostsUrls()
    {
        $return = array();
        foreach ($posts = $this->getRelatedPosts()->getItems() as $post) {
            $return []= '<a href="' . $this->getPostUrl($post) . '">' . $post->getTitle() . '</a>';
        }
        return $return;
    }


}