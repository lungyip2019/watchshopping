<?php

namespace TemplateMonster\Blog\Model;

class Category extends \Magento\Framework\Model\AbstractModel
{
    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;

    protected $_eventPrefix = 'tm_blog_category';

    protected $_eventObject = 'blog_category';

    protected $_relatedPostsCollection;

    protected $_storeManager;


    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \TemplateMonster\Blog\Model\ResourceModel\Post\CollectionFactory $postsCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->_storeManager = $storeManager;
        $this->_relatedPostsCollection = $postsCollectionFactory;
    }

    protected function _construct()
    {
        $this->_init('TemplateMonster\Blog\Model\ResourceModel\Category');
    }

    public function isActive()
    {
        return ($this->getIsActive() == self::STATUS_VISIBLE);
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_VISIBLE => __('Visible'), self::STATUS_HIDDEN => __('Hidden')];
    }

    public function getRelatedPosts($storeId = null)
    {
        if (!$this->hasData('related_posts')) {
            if ($this->getId()) {
                $collection = $this->_relatedPostsCollection->create();

                /*if (!is_null($storeId)) {
                    $collection->addStoreFilter($storeId);
                } elseif ($storeIds = $this->getStoreId()) {
                    $collection->addStoreFilter($storeIds[0]);
                }*/

                $collection->getSelect()->joinLeft(
                    ['rl' => $this->getResource()->getTable('tm_blog_post_category')],
                    'main_table.post_id = rl.post_id'
                )->where(
                    'rl.category_id = ?',
                    $this->getId()
                );
                //$collection->load();
                $this->setData('related_posts', $collection);
            } else {
                $this->setData('related_posts', null);
            }
        }

        return $this->getData('related_posts');
    }
}
