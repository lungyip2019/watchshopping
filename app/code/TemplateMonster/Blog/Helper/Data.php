<?php
namespace TemplateMonster\Blog\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const CONFIG_PATH_ACTIVE = 'tm_blog/general/active';
    const CONFIG_PATH_MENU_ACTIVE = 'tm_blog/general/menu_active';
    const CONFIG_PATH_TITLE = 'tm_blog/general/title';
    const CONFIG_PATH_TOPLINK = 'tm_blog/general/toplink_active';
    const CONFIG_PATH_TOPLINK_LABEL = 'tm_blog/general/toplink_label';
    const CONFIG_PATH_ROUTE = 'tm_blog/general/route';
    const CONFIG_PATH_LIMITS = 'tm_blog/general/limits';
    const CONFIG_PATH_POST_LAYOUT = 'tm_blog/general/post_layout';
    const CONFIG_PATH_POST_LIST_LAYOUT = 'tm_blog/general/list_layout';
    const CONFIG_PATH_META_KEYWORDS = 'tm_blog/general/meta_keywords';
    const CONFIG_PATH_META_DESCRIPTION = 'tm_blog/general/meta_description';
    const CONFIG_PATH_RECAPTCHA_ACTIVE = 'tm_blog/general/recaptcha';
    const CONFIG_PATH_RECAPTCHA_API = 'tm_blog/general/recaptcha_api';
    const CONFIG_PATH_RECAPTCHA_SECRET = 'tm_blog/general/recaptcha_secret';
    const CONFIG_PATH_DATA_FORMAT = 'tm_blog/general/data_format';
    const CONFIG_PATH_RELATED_POSTS_ENABLE = 'tm_blog/post_view/related_posts/enabled';
    const CONFIG_PATH_RELATED_POSTS_NUMBER = 'tm_blog/post_view/related_posts/posts_number';
    const CONFIG_PATH_RELATED_POSTS_NUMBER_PER_VIEW = 'tm_blog/post_view/related_posts/posts_number_per_view';
    const CONFIG_PATH_RELATED_LAYOUT_VIEW = 'tm_blog/post_view/related_posts/layout_view';
    const CONFIG_PATH_RELATED_PRODUCTS_ENABLE = 'tm_blog/post_view/related_products/enabled';
    const CONFIG_PATH_RELATED_PRODUCTS_NUMBER = 'tm_blog/post_view/related_products/products_number';
    const CONFIG_PATH_RELATED_PRODUCTS_NUMBER_PER_VIEW = 'tm_blog/post_view/related_products/products_number_per_view';
    const CONFIG_PATH_RELATED_SHOW_LINKS = 'tm_blog/post_view/related_products/show_links';
    const CONFIG_PATH_SIDEBAR_SHOW_CATEGORIES = 'tm_blog/sidebar/show_categories';
    const CONFIG_PATH_SIDEBAR_CATEGORIES_NUMBER = 'tm_blog/sidebar/categories_number';
    const CONFIG_PATH_SIDEBAR_SHOW_POSTS = 'tm_blog/sidebar/show_posts';
    const CONFIG_PATH_SIDEBAR_POSTS_NUMBER = 'tm_blog/sidebar/posts_number';
    const CONFIG_PATH_SIDEBAR_SHOW_COMMENTS = 'tm_blog/sidebar/show_comments';
    const CONFIG_PATH_SIDEBAR_COMMENTS_NUMBER = 'tm_blog/sidebar/comments_number';

    const DEFAULT_TITLE = 'Blog';

    protected $_scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->_scopeConfig = $scopeConfig;
    }

    protected function getConfigValue($path, $scope)
    {
        return $this->_scopeConfig->getValue($path, $scope);
    }

    public function isModuleActive()
    {
        return $this->getConfigValue(self::CONFIG_PATH_ACTIVE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function isReCaptchaActive()
    {
        return $this->getConfigValue(self::CONFIG_PATH_RECAPTCHA_ACTIVE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getReCaptchaApi()
    {
        return $this->getConfigValue(self::CONFIG_PATH_RECAPTCHA_API, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getReCaptchaSecret()
    {
        return $this->getConfigValue(self::CONFIG_PATH_RECAPTCHA_SECRET, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getDataFormat()
    {
        return $this->getConfigValue(self::CONFIG_PATH_DATA_FORMAT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function isAllowTopLink()
    {
        return $this->getConfigValue(self::CONFIG_PATH_TOPLINK, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function isAllowMenu()
    {
        return $this->getConfigValue(self::CONFIG_PATH_MENU_ACTIVE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getLinkLabel()
    {
        return $this->getConfigValue(self::CONFIG_PATH_TOPLINK_LABEL, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getTitle()
    {
        $value = $this->getConfigValue(self::CONFIG_PATH_TITLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $value ? $value : self::DEFAULT_TITLE;
    }

    public function getRoute()
    {
        return $this->getConfigValue(self::CONFIG_PATH_ROUTE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getLimits()
    {
        return $this->getConfigValue(self::CONFIG_PATH_LIMITS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getPostLayout()
    {
        return $this->getConfigValue(self::CONFIG_PATH_POST_LAYOUT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getPostListLayout()
    {
        return $this->getConfigValue(
            self::CONFIG_PATH_POST_LIST_LAYOUT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getMetaKeywords()
    {
        return $this->getConfigValue(self::CONFIG_PATH_META_KEYWORDS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getMetaDescription()
    {
        return $this->getConfigValue(
            self::CONFIG_PATH_META_DESCRIPTION,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function isRelatedPostsEnabled()
    {
        return $this->getConfigValue(
            self::CONFIG_PATH_RELATED_POSTS_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getRelatedPostsNumber()
    {
        return $this->getConfigValue(
            self::CONFIG_PATH_RELATED_POSTS_NUMBER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getRelatedPostsLayoutView()
    {
        $options = [
            0 => 'grid',
            1 => 'list'
        ];
        return $options[$this->getConfigValue(
            self::CONFIG_PATH_RELATED_LAYOUT_VIEW,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        )];
    }

    public function getRelatedPostsNumberPerView()
    {
        return $this->getConfigValue(
            self::CONFIG_PATH_RELATED_POSTS_NUMBER_PER_VIEW,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function isRelatedProductsEnabled()
    {
        return $this->getConfigValue(
            self::CONFIG_PATH_RELATED_PRODUCTS_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getRelatedProductsNumber()
    {
        return $this->getConfigValue(
            self::CONFIG_PATH_RELATED_PRODUCTS_NUMBER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getRelatedProductsNumberPerView()
    {
        return $this->getConfigValue(
            self::CONFIG_PATH_RELATED_PRODUCTS_NUMBER_PER_VIEW,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getRelatedPostsShowLinks()
    {
        return $this->getConfigValue(
            self::CONFIG_PATH_RELATED_SHOW_LINKS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getSidebarShowCategories()
    {
        return $this->getConfigValue(
            self::CONFIG_PATH_SIDEBAR_SHOW_CATEGORIES,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getSidebarCategoriesNumber()
    {
        return $this->getConfigValue(
            self::CONFIG_PATH_SIDEBAR_CATEGORIES_NUMBER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getSidebarShowPosts()
    {
        return $this->getConfigValue(
            self::CONFIG_PATH_SIDEBAR_SHOW_POSTS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getSidebarPostsNumber()
    {
        return $this->getConfigValue(
            self::CONFIG_PATH_SIDEBAR_POSTS_NUMBER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getSidebarShowComments()
    {
        return $this->getConfigValue(
            self::CONFIG_PATH_SIDEBAR_SHOW_COMMENTS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getSidebarCommentsNumber()
    {
        return $this->getConfigValue(
            self::CONFIG_PATH_SIDEBAR_COMMENTS_NUMBER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

}