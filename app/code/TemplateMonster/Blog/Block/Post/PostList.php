<?php
namespace TemplateMonster\Blog\Block\Post;

use \Magento\Framework\View\Element\Template;
use \Magento\Framework\DataObject;
use \Magento\Framework\Registry;
use \TemplateMonster\Blog\Model\ResourceModel\Post\Collection;
use \TemplateMonster\Blog\Block\Post\PostList\Toolbar;
use \TemplateMonster\Blog\Model\Url;
use \Magento\Cms\Model\Template\FilterProvider;
use TemplateMonster\Blog\Helper\Data as HelperData;

class PostList extends Template
{
    const DEFAULT_SORT_DIRECTION = 'asc';

    protected $_postCollection;

    protected $_urlModel;

    protected $_registry;

    protected $_helper;

    public function __construct(
        Template\Context $context,
        Collection $postCollection,
        FilterProvider $filterProvider,
        Toolbar $toolbar,
        Url $url,
        HelperData $helper,
        Registry $registry,
        array $data = []
    ) {
        $this->_helper = $helper;
        $this->_registry = $registry;
        $this->_urlModel = $url;
        $this->_postCollection = $postCollection;
        $this->_toolbar = $toolbar;
        $this->_filterProvider = $filterProvider;
        parent::__construct($context, $data);
    }

    /**
     * Get modified collection object with set order
     *
     * @return Collection
     */
    public function getCollection()
    {
        return $this->_getPostCollection();
    }

    protected function _getPostCollection()
    {
        return $this->_postCollection;
    }

    protected function _prepareData()
    {
        $category = $this->_registry->registry('tm_blog_category');
        if ($category && $category->getId()) {
            $this->_postCollection = $category->getRelatedPosts();
        }

        $this->_postCollection
            ->addFieldToFilter('is_visible', true)
            ->addStoreFilter($this->_storeManager->getStore()->getId());
    }

    protected function _beforeToHtml()
    {
        $this->_prepareData();
        $this->_toolbar->setCollection($this->_getPostCollection());
        $this->_getPostCollection()->load();

        return parent::_beforeToHtml();
    }

    public function filterContent($data)
    {
        return $this->_filterProvider->getBlockFilter()->filter($data);
    }

    public function getPostUrl($post)
    {
        return $this->getUrl($this->_urlModel->getPostRoute($post));
    }

    public function getCategory()
    {
        return $this->_registry->registry('tm_blog_category');
    }

    public function getDateFormat()
    {
        return $this->_helper->getDataFormat();
    }
}
