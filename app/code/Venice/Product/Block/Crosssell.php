<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/**
 * Catalog product related items block
 */
namespace Venice\Product\Block;

use Magento\Catalog\Block\Product\Context ;
use Venice\Product\Logger\Logger;

class Crosssell extends \Magento\Catalog\Block\Product\AbstractProduct
{

    public function __construct(Context $context,
    array $data=[])
	{    
		parent::__construct($context,$data);
    }
    
    /**
     * Crosssell item collection
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    protected $_itemCollection;
    /**
     * Prepare crosssell items data
     *
     * @return \Venice\Product\Block\Crosssell
     */
    protected function _prepareData()
    {
        $product = $this->_coreRegistry->registry('current_product');
        /* @var $product \Magento\Catalog\Model\Product */
        $this->_itemCollection = $product->getCrossSellProductCollection()->addAttributeToSelect(
            $this->_catalogConfig->getProductAttributes()
        )->setPositionOrder()->addStoreFilter();
        $this->_itemCollection->load();
        foreach ($this->_itemCollection as $product) {
            $product->setDoNotUseCategoryId(true);            
        }
        
        return $this;
    }
    /**
     * Before rendering html process
     * Prepare items collection
     *
     * @return \Venice\Product\Block\Crosssell
     */
    protected function _beforeToHtml()
    {
        $this->_prepareData();
        return parent::_beforeToHtml();
    }
    /**
     * Retrieve crosssell items collection
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getItems()
    {
        return $this->_itemCollection;
    }

    public function getItemCount(){
        return sizeof($this->_itemCollection);
    }
}