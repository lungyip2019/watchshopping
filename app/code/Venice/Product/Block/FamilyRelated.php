<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/**
 * Family related items block
 */
namespace Venice\Product\Block;

use Magento\Catalog\Block\Product\Context ;
use Venice\Product\Logger\Logger;
use Magento\Catalog\Helper\Image;

class FamilyRelated extends \Magento\Catalog\Block\Product\AbstractProduct
{

    protected $imageHelper;

    public function __construct(Context $context,
    array $data=[],
    Image $imageHelper)
	{    
        $this->imageHelper = $imageHelper;
		parent::__construct($context,$data);
    }
    
    /**
     * Product collection
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    protected $_itemCollection;

    /**
     * indicate the last page of this collection
     *
     * @var int $lastpage
     */
    protected $lastpage;

    /**
     * Prepare family related product data
     *
     * @return \Venice\Product\Block\FamilyRelated
     */
    protected function _prepareData()
    {
        $product = $this->_coreRegistry->registry('current_product');
        /* @var $product \Magento\Catalog\Model\Product */
        $this->_itemCollection = $product->getCollection()
            ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
            ->addAttributeToFilter('family_id',array('eq'=>$product->getFamilyId()))
            ->addFieldToFilter('entity_id',array('neq'=>$product->getId()))
            ->setVisibility(array(2,4))   
            ->addStoreFilter()
            ->setPageSize(4)
            ->setCurPage(1);

        $this->_itemCollection->load();                    
        $this->lastpage = $this->_itemCollection->getLastPageNumber();
        foreach ($this->_itemCollection as $product) {
            $product->setDoNotUseCategoryId(true);           
        }
        return $this;
    }
    /**
     * Before rendering html process
     * Prepare items collection
     *
     * @return \Venice\Product\Block\FamilyRelated
     */
    protected function _beforeToHtml()
    {
        $this->_prepareData();
        return parent::_beforeToHtml();
    }
    /**
     * Retrieve items collection related to same family
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getItems()
    {
        return $this->_itemCollection;
    }

    public function getItemsJson()
    {
        $productData = [];
        foreach ($this->_itemCollection as $product) {
            array_push($productData,
                array(
                    'entity_id' => $product->getId(),
                    'name' => $product->getAttributeText('family_id'),
                    'brand' => $product->getAttributeText('brand_id'),
                    'sku' => $product->getSku(),
                    'url' => $product->getProductUrl(),
                    'src' => $this->imageHelper->init($product, 'product_base_image')->getUrl()
                )
            );
        }  
        return json_encode($productData);
    }

    public function getCurrentProductId(){
        return $this->_coreRegistry->registry('current_product')->getId();
    }

    public function getItemCount(){
        return sizeof($this->_itemCollection);
    }

    public function getLastPage(){
        return $this->lastpage;
    }
}