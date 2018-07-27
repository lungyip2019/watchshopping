<?php
namespace TemplateMonster\Blog\Block\Post\PostList;

use \Magento\Framework\Registry;
use \TemplateMonster\Blog\Model\PostList\Toolbar as ToolbarModel;
use \TemplateMonster\Blog\Model\ResourceModel\Post\Collection;
use \TemplateMonster\Blog\Block\Post\PostList;
use \TemplateMonster\Blog\Helper\Data;

class Toolbar extends \Magento\Framework\View\Element\Template
{
    const DEFAULT_SORT_ORDER = 'creation_time';
    const DEFAULT_POSTS_LIMIT = 3;

    protected $_postCollection = null;
    protected $_direction = PostList::DEFAULT_SORT_DIRECTION;
    protected $_availableLimits;
    protected $_helper;
    protected $_defaultLimits = [3 => 3, 6 => 6, 9 => 9];
    protected $_registry;

    protected $_availableOrders = array(
        'title' => 'Title',
        'author' => 'Author',
        'creation_time' => 'Date'
    );

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Session\SessionManager $sessionsManager,
        ToolbarModel $toolbarModel,
        Collection $postCollection,
        Data $helper,
        Registry $registry,
        array $data = []
    ) {
        $this->_registry = $registry;
        $this->_helper = $helper;
        $this->_context = $context;
        $this->_toolbarModel = $toolbarModel;
        $this->_postCollection = $postCollection;
        $this->_session = $sessionsManager;

        parent::__construct($context, $data);
    }

    /**
     * Memorize parameter value for session
     *
     * @param $param
     * @param $value
     * @return $this
     */
    protected function _memorizeParam($param, $value)
    {
        $this->_session->setData($param, $value);
        return $this;
    }

    /**
     * Update collection. Set order
     *
     * @return $this
     */
    public function setCollection()
    {
        $category = $this->_registry->registry('tm_blog_category');
        if ($category && $category->getId()) {
            $this->_postCollection = $category->getRelatedPosts();
        }

        $this->_postCollection->setCurPage($this->getCurrentPage());

        $limit = (int)$this->getLimit();

        if ($limit) {
            $this->_postCollection->setPageSize($limit);
        }

        $this->_postCollection->setOrder($this->getCurrentOrder(), $this->getCurrentDirection());
        return $this;
    }

    /**
     * Get posts collection object
     *
     * @return null|Collection
     */
    public function getCollection()
    {
        $this->setCollection();
        return $this->_postCollection;
    }

    /**
     * Get current blog posts sorting order
     *
     * @return mixed|string
     */
    public function getCurrentOrder()
    {
        $order = $this->_getData('_current_posts_order');
        if ($order) {
            return $order;
        }

        $orders = $this->getAvailableOrders();
        $defaultOrder = self::DEFAULT_SORT_ORDER;

        $order = $this->_toolbarModel->getOrder();
        if (!$order || !$orders[$order]) {
            $order = $defaultOrder;
        }

        if ($order != $defaultOrder) {
            $this->_memorizeParam('sort_order', $order);
        }
        $this->setData('_current_posts_order', $order);
        return $order;
    }

    /**
     * @return string
     */
    public function getCurrentDirection()
    {
        $direction = $this->_getData('_current_posts_direction');
        if ($direction) {
            return $direction;
        }

        $directions = ['asc', 'desc' ];
        $direction = strtolower($this->_toolbarModel->getDirection());

        if (!$direction || !in_array($direction, $directions)) {
            $direction = $this->_direction;
        }

        if ($direction != $this->_direction) {
            $this->_memorizeParam('sort_direction', $direction);
        }

        $this->setData('_current_posts_direction', $direction);
        return $direction;
    }

    /**
     * Get specified products limit display per page
     *
     * @return string
     */
    public function getLimit()
    {
        $limit = $this->_getData('_current_limit');
        if ($limit) {
            return $limit;
        }

        $limits = $this->getAvailableLimits();
        $defaultLimit = $this->getDefaultLimit();
        if (!$defaultLimit || !isset($limits[$defaultLimit])) {
            $keys = array_keys($limits);
            $defaultLimit = $keys[0];
        }

        $limit = $this->_toolbarModel->getLimit();
        if (!$limit || !isset($limits[$limit])) {
            $limit = $defaultLimit;
        }

        if ($limit != $defaultLimit) {
            $this->_memorizeParam('limit_page', $limit);
        }

        $this->setData('_current_limit', $limit);
        return $limit;
    }

    /**
     * Return current page from request
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->_toolbarModel->getCurrentPage();
    }

    /**
     * @param int $limit
     * @return bool
     */
    public function isLimitCurrent($limit)
    {
        return $limit == $this->getLimit();
    }

    /**
     * Get array of available sorting orders
     *
     * @return array
     */
    public function getAvailableOrders()
    {
        return $this->_availableOrders;
    }

    /**
     * @return array
     */
    public function getAvailableLimits()
    {
        if (!$this->_availableLimits) {
            $limitsString = $this->_helper->getLimits();
            $limits = explode('/', $limitsString);
            foreach ($limits as $limit) {
                if ($limit) {
                    $this->_availableLimits[(int)$limit] = (int)$limit;
                }
            }
            if (!$this->_availableLimits) {
                $this->_availableLimits = $this->_defaultLimits;
            }
        }
        return $this->_availableLimits;
    }

    /**
     * @return int
     */
    public function getDefaultLimit()
    {
        $limits = $this->getAvailableLimits();
        return reset($limits);
    }

    /**
     * Compare defined order field with current order field
     *
     * @param string $order
     * @return bool
     */
    public function isOrderCurrent($order)
    {
        return $order == $this->getCurrentOrder();
    }

    /**
     * Return current URL with rewrites and additional parameters
     *
     * @param array $params Query parameters
     * @return string
     */
    public function getPagerUrl($params = [])
    {
        $urlParams = [];
        $urlParams['_current'] = true;
        $urlParams['_escape'] = false;
        $urlParams['_use_rewrite'] = true;
        $urlParams['_query'] = $params;
        return $this->getUrl('*/*/*', $urlParams);
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        $pagerBlock = $this->getChildBlock('product_list_toolbar_pager');

        if ($pagerBlock instanceof \Magento\Framework\DataObject) {
            $pagerBlock ->setAvailableLimit($this->getAvailableLimits());

            $pagerBlock->setUseContainer(
                false
            )->setShowPerPage(
                false
            )->setShowAmounts(
                false
            )->setFrameLength(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )->setJump(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame_skip',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )->setLimit(
                $this->getLimit()
            )->setCollection(
                $this->getCollection()
            );

            return $pagerBlock->toHtml();
        }
        return '';
    }

    /**
     * Prepare javascript widget initialization options
     *
     * @param array $customOptions
     * @return string
     */
    public function getWidgetOptionsJson(array $customOptions = [])
    {
        $options = [
            'order' => ToolbarModel::ORDER_PARAM_NAME,
            'direction' => ToolbarModel::DIRECTION_PARAM_NAME,
            'orderDefault' => self::DEFAULT_SORT_ORDER,
            'directionDefault' => $this->_direction,
            'url' => $this->getPagerUrl(),
            'limitDefault' => $this->getDefaultLimit(),
        ];
        $options = array_replace_recursive($options, $customOptions);
        return json_encode(['productListToolbarForm' => $options]);
    }
}
