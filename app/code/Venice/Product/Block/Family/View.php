<?php
namespace Venice\Product\Block\Family;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Venice\Product\Api\Data\ProductFamilyInterface;
use Magento\Catalog\Block\Category\View as CategoryView;
use Magento\Catalog\Helper\Category;
use Magento\Catalog\Model\Layer\Resolver;
use TemplateMonster\ShopByBrand\Helper\Data as BrandHelper;


class View extends CategoryView
{

    protected $brandHelper;

    public function __construct(
        Context $context,
        Resolver $layerResolver,
        Registry $registry,
        Category $categoryHelper,
        BrandHelper $brandHelper,
        array $data=[])
    {
        $this->brandHelper = $brandHelper;
        parent::__construct(
            $context,
            $layerResolver,
            $registry,
            $categoryHelper,
            $data
        );
    }

    /**
     * @return ProductFamilyInterface
     */
    public function getFamily()
    {
        $family = $this->_coreRegistry->registry('current_family');
        return $family;
    }

    public function getBrand(){
        $brand = $this->_coreRegistry->registry('current_brand');
        return $brand;
    }

    public function getBrandHelper(){
        return $this->brandHelper;
    }


    /**
     * @return string
     */
    public function getProductListHtml()
    {
        return $this->getChildHtml('family_product_list');
    }

}
