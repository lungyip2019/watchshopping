<?php 

namespace TemplateMonster\Blog\Model;

use TemplateMonster\Blog\Api\Data\PostInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Post  extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{
    const STATUS_VISIBLE = 1;

    const STATUS_HIDDEN = 0;


    const CACHE_TAG = 'blog_post';
    

    protected $_cacheTag = 'blog_post';
    

    protected $_eventPrefix = 'blog_post';
    

    protected $_urlBuilder;


    protected $_productCollectionFactory;


    protected $_relatedPostsCollection;


    protected $_relatedCommentsCollection;

    protected $_storeManager;

    protected $_categoryCollectionFactory;


    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \TemplateMonster\Blog\Model\ResourceModel\Comment\CollectionFactory $commentCollectionFactory,
        \TemplateMonster\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_storeManager = $storeManager;
        $this->_urlBuilder = $urlBuilder;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_commentCollectionFactory = $commentCollectionFactory;
        $this->_relatedPostsCollection = clone($this->getCollection());
    }

    protected function _construct()
    {
        $this->_init('TemplateMonster\Blog\Model\ResourceModel\Post');
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_VISIBLE => __('Visible'), self::STATUS_HIDDEN => __('Hidden')];
    }
    
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getRelatedPosts($storeId = null)
    {
        if (!$this->hasData('related_posts')) {
            $collection = $this->_relatedPostsCollection
                ->addFieldToFilter('post_id', array('neq' => $this->getId()))
            ->addStoreFilter(is_null($storeId) ? $this->getStoreId() : $storeId);
            $collection->getSelect()->joinLeft(
                ['rl' => $this->getResource()->getTable('tm_blog_post_related_post')],
                'main_table.post_id = rl.related_id',
                ['position']
            )->where(
                'rl.post_id = ?',
                $this->getId()
            );
            $this->setData('related_posts', $collection);
        }

        return $this->getData('related_posts');
    }

    public function getRelatedProducts($storeId = null)
    {
        if (!$this->hasData('related_products')) {
            if ($this->getId()) {
                $collection = $this->_productCollectionFactory->create();

                if (!is_null($storeId)) {
                    $collection->addStoreFilter($storeId);
                } elseif ($storeIds = $this->getStoreId()) {
                    $collection->addStoreFilter($storeIds[0]);
                }

                $collection->getSelect()->joinLeft(
                    ['rl' => $this->getResource()->getTable('tm_blog_post_related_product')],
                    'e.entity_id = rl.related_id',
                    ['position']
                )->where(
                    'rl.post_id = ?',
                    $this->getId()
                );

                $this->setData('related_products', $collection);
            } else {
                $this->setData('related_products', null);
            }
        }

        return $this->getData('related_products');
    }

    public function getRelatedCategories()
    {
        if (!$this->hasData('related_products')) {
            if ($this->getId()) {
                $collection = $this->_categoryCollectionFactory->create();

                $collection->getSelect()->joinLeft(
                    ['rl' => $this->getResource()->getTable('tm_blog_post_category')],
                    'main_table.category_id = rl.category_id'
                )->where(
                    'rl.post_id = ?',
                    $this->getId()
                );

                $this->setData('related_categories', $collection);
            } else {
                $this->setData('related_categories', null);
            }
        }

        return $this->getData('related_categories');
    }

    public function getOneCategory()
    {
        return $this->getRelatedCategories()->getFirstItem();
    }

    public function loadCategories()
    {

        $options = [];
        if ($categories = $this->getRelatedCategories()) {
            foreach ($categories as $category) {
                $options [] = $category->getId();
            }
        }
        $this->setData('categories', $options);
    }

    public function getRelatedComments()
    {
        if (!$this->hasData('related_comments')) {
            if ($this->getId()) {
                $collection = $this->_commentCollectionFactory->create();

                $collection->getSelect()->joinLeft(
                    ['rl' => $this->getResource()->getTable('tm_blog_post')],
                    'main_table.post_id = rl.post_id',
                    'rl.post_id'
                )->where(
                    'rl.post_id = ?',
                    $this->getId()
                );

                $this->setData('related_comments', $collection);
            } else {
                $this->setData('related_comments', null);
            }
        }

        return $this->getData('related_comments');
    }

    public function getApprovedComments()
    {
        if (!$this->hasData('approved_comments')) {
            if ($this->getId()) {
                $filteredCollection = clone $this->getRelatedComments();
                $filteredCollection->addFieldToFilter('status', 1);
                $this->setData('approved_comments', $filteredCollection);
            } else {
                $this->setData('approved_comments', null);
            }
        }
        return $this->getData('approved_comments');
    }

    public function getImage()
    {
        if (!$this->hasData('image_url')) {
            if ($file = $this->getData('image')) {
                $image = $this->_storeManager->getStore()
                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $file;
            } else {
                $image = false;
            }
            $this->setData('image_url', $image);
        }

        return $this->getData('image_url');
    }
}
