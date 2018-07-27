<?php

namespace TemplateMonster\Blog\Model\ResourceModel;

/**
 * Blog post resource model
 */
class Post extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $dateTime;

    protected $helperJs;

    /**
     * Construct
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param string|null $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Backend\Helper\Js $helperJs,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
        $this->dateTime = $dateTime;
        $this->helperJs = $helperJs;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('tm_blog_post', 'post_id');
    }

    /**
     * Process post data before deleting
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $condition = ['post_id = ?' => (int)$object->getId()];

        $this->getConnection()->delete($this->getTable('tm_blog_post_store'), $condition);
        $this->getConnection()->delete($this->getTable('tm_blog_post_related_product'), $condition);
        $this->getConnection()->delete($this->getTable('tm_blog_post_category'), $condition);

        $this->getConnection()->delete($this->getTable('tm_blog_post_related_post'), $condition);
        $this->getConnection()->delete($this->getTable('tm_blog_post_related_post'),
            ['related_id = ?' => (int)$object->getId()]
        );

        return parent::_beforeDelete($object);
    }

    /**
     * Process post data before saving
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $value = $object->getData('creation_time');
        $object->setData('creation_time', $this->dateTime->formatDate($value));

        if (!$this->isValidPostIdentifier($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('The post URL key contains capital letters or disallowed symbols.')
            );
        }

        if ($this->isNumericPostIdentifier($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('The post URL key cannot be made of only numbers.')
            );
        }

        return parent::_beforeSave($object);
    }

    /**
     * Load an object using 'identifier' field if there's no field specified and value is not numeric
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param mixed $value
     * @param string $field
     * @return $this
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        if (!is_numeric($value) && is_null($field)) {
            $field = 'identifier';
        }

        return parent::load($object, $value, $field);
    }

    /**
     * Perform operations after object load
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }

        return parent::_afterLoad($object);
    }

    /**
     * Retrieve load select with filter by identifier, store and activity
     *
     * @param string $identifier
     * @param int $store
     * @param int $isVisible
     * @return int
     */
    protected function _getLoadByIdentifierSelect($identifier, $store, $isVisible = null)
    {
        $select = $this->getConnection()->select()->from(
            ['cp' => $this->getMainTable()]
        )->join(
            ['cps' => $this->getTable('tm_blog_post_store')],
            'cp.post_id = cps.post_id',
            []
        )->where(
            'cp.identifier = ?',
            $identifier
        )->where(
            'cps.store_id IN (?)',
            $store
        );

        if (!is_null($isVisible)) {
            $select->where('cp.is_visible = ?', $isVisible);
        }
        return $select;
    }

    /**
     *  Check whether post identifier is numeric
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool
     */
    protected function isNumericPostIdentifier(\Magento\Framework\Model\AbstractModel $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('identifier'));
    }

    /**
     *  Check whether post identifier is valid
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool
     */
    protected function isValidPostIdentifier(\Magento\Framework\Model\AbstractModel $object)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('identifier'));
    }

    /**
     * Check if post identifier exist for specific store
     * return post id if post exists
     *
     * @param string $identifier
     * @param int $storeId
     * @return int
     */
    public function checkIdentifier($identifier, $storeId)
    {
        $stores = [\Magento\Store\Model\Store::DEFAULT_STORE_ID, $storeId];
        $select = $this->_getLoadByIdentifierSelect($identifier, $stores, 1);
        $select->reset(\Zend_Db_Select::COLUMNS)->columns('cp.post_id')->order('cps.store_id DESC')->limit(1);

        return $this->getConnection()->fetchOne($select);
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $postId
     * @return array
     */
    public function lookupStoreIds($postId)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            $this->getTable('tm_blog_post_store'),
            'store_id'
        )->where(
            'post_id = ?',
            (int)$postId
        );

        return $connection->fetchCol($select);
    }

    protected function _lookupIds($postId, $tableName, $field)
    {
        $adapter = $this->getConnection();

        $select = $adapter->select()->from(
            $this->getTable($tableName),
            $field
        )->where(
            'post_id = ?',
            (int)$postId
        );

        return $adapter->fetchCol($select);
    }

    public function lookupRelatedPostIds($postId)
    {
        return $this->_lookupIds($postId, 'tm_blog_post_related_post', 'related_id');
    }

    public function lookupRelatedProductIds($postId)
    {
        return $this->_lookupIds($postId, 'tm_blog_post_related_product', 'related_id');
    }

    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->_decodeGridSerializedInput($object);
        $this->_updateStoreLinks($object);
        $this->_updateRelatedEntities($object);

        return parent::_afterSave($object);
    }

    protected function _updateRelatedEntities(\Magento\Framework\Model\AbstractModel $object)
    {
        $linkTypes = ['related_posts', 'related_products'];
        foreach ($linkTypes as $type) {
            $linksData = $object->getData($type . '_links');
            if (is_array($linksData)) {
                $prevIds = $this->_lookupIds($object->getId(), 'tm_blog_post_' . substr($type, 0, -1), 'related_id');
                $this->_updateLinks(
                    $object,
                    array_keys($linksData),
                    $prevIds,
                    'tm_blog_post_' . substr($type, 0, -1),
                    'related_id',
                    $linksData
                );
            }
        }
        $categories = $object->getCategories()?$object->getCategories():[];
        //if (is_array($categories)) {
            $prevIds = $this->_lookupIds($object->getId(), 'tm_blog_post_category', 'category_id');
            $this->_updateLinks($object, $categories, $prevIds, 'tm_blog_post_category', 'category_id');
        //}
    }

    protected function _decodeGridSerializedInput(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($links = $object->getData('links')) {
            $jsHelper = $this->helperJs;
            $links = is_array($links) ? $links : [];
            $linkTypes = ['related_posts', 'related_products'];
            foreach ($linkTypes as $type) {
                if (isset($links[$type])) {
                    $links[$type] = $jsHelper->decodeGridSerializedInput($links[$type]);

                    $object->setData($type . '_links', $links[$type]);
                }
            }
        }
    }

    protected function _updateStoreLinks(\Magento\Framework\Model\AbstractModel $object)
    {
        $prevIds = $this->lookupStoreIds($object->getId());
        $newIds = (array)$object->getStores();
        if (empty($newIds)) {
            $newIds = (array)$object->getStoreId();
        }
        $this->_updateLinks($object, $newIds, $prevIds, 'tm_blog_post_store', 'store_id');
    }

    protected function _updateLinks(
        \Magento\Framework\Model\AbstractModel $object,
        array $newIds,
        array $prevIds,
        $tableName,
        $field,
        $rowData = []
    ) {
        $table = $this->getTable($tableName);

        $insert = $newIds;
        $delete = $prevIds;

        if ($delete) {
            $where = ['post_id = ?' => (int)$object->getId(), $field.' IN (?)' => $delete];
            $this->getConnection()->delete($table, $where);
        }

        if ($insert) {
            $data = [];
            foreach ($insert as $id) {
                $id = (int)$id;
                $data[] = array_merge(['post_id' => (int)$object->getId(), $field => $id],
                    (isset($rowData[$id]) && is_array($rowData[$id])) ? $rowData[$id] : []
                );
            }

            $this->getConnection()->insertMultiple($table, $data);
        }
    }
}
