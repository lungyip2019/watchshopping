<?php
namespace TemplateMonster\Blog\Block\Post\View;

use Magento\Framework\Registry;
use TemplateMonster\Blog\Block\Post\View;
use TemplateMonster\Blog\Helper\Data as HelperData;

class Products extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Framework\DataObject\IdentityInterface
{

    protected $_registry;

    protected $_post;

    protected $_helper;

    /**
     * @var Collection
     */
    protected $_itemCollection;

    /**
     * Checkout session
     *
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;

    /**
     * Checkout cart
     *
     * @var \Magento\Checkout\Model\ResourceModel\Cart
     */
    protected $_checkoutCart;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Checkout\Model\ResourceModel\Cart $checkoutCart,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Module\Manager $moduleManager,
        HelperData $helper,
        //Registry $registry,
        array $data = []
    ) {
        $this->_checkoutCart = $checkoutCart;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_checkoutSession = $checkoutSession;
        $this->moduleManager = $moduleManager;
        $this->_helper = $helper;
        //$this->_registry = $registry;
        $this->_registry = $context->getRegistry();
        parent::__construct(
            $context,
            $data
        );
    }

    public function getProducts()
    {
        if ($this->_helper->isRelatedProductsEnabled()) {
            if ($numberOfProducts = $this->_helper->getRelatedProductsNumber()) {
                return $this->getPost()->getRelatedProducts()->setPageSize($numberOfProducts)->setCurPage(1);
            }
        }
        return null;
    }

    public function getPost()
    {
        if (!$this->_post) {
            $this->_post = $this->_registry->registry('tm_blog_post');
        }
        return $this->_post;
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [];
        if ($items = $this->getItems()) {
            foreach ($items as $item) {
                $identities = array_merge($identities, $item->getIdentities());
            }
        }
        return $identities;
    }

    public function getItems()
    {
        return $this->_itemCollection;
    }

    protected function _beforeToHtml()
    {
        $this->_prepareData();
        return parent::_beforeToHtml();
    }

    protected function _prepareData()
    {
        if ($this->_helper->isRelatedProductsEnabled() && ($numberOfProducts = $this->_helper->getRelatedProductsNumber())) {
            $post = $this->getPost();
            /* @var $product \Magento\Catalog\Model\Product */

            $this->_itemCollection = $post->getRelatedProducts()->addAttributeToSelect(
                'required_options'
            );

            if ($this->moduleManager->isEnabled('Magento_Checkout')) {
                $this->_addProductAttributesAndPrices($this->_itemCollection);
            }
            $this->_itemCollection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

            $this->_itemCollection->getSelect()->order('position', 'ASC')->limit($numberOfProducts);

            $this->_itemCollection->load();

            foreach ($this->_itemCollection as $product) {
                $product->setDoNotUseCategoryId(true);
            }
        }
        return $this;
    }

    public function canItemsAddToCart()
    {
        foreach ($this->getItems() as $item) {
            if (!$item->isComposite() && $item->isSaleable() && !$item->getRequiredOptions()) {
                return true;
            }
        }
        return false;
    }

    public function getRelatedProductsNumberPerView()
    {
        return $this->_helper->getRelatedProductsNumberPerView();
    }
}
