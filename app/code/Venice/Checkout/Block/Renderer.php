<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Venice\Checkout\Block;

use \Magento\Catalog\Model\Product;
use \TemplateMonster\ShopByBrand\Model\BrandRepository;

class Renderer extends \Magento\Checkout\Block\Cart\Item\Renderer
{


    public function getProductCollection($product_id)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($product_id);
        return $product;
    }

    public function getBrandName($brandId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $brand= $objectManager->create('TemplateMonster\ShopByBrand\Model\BrandRepository')->getById($brandId);
        $brandName = $brand->getName();
        return $brandName;
    }

    public function getDiscount($product_id){
        $product = $this->getProductCollection($product_id);
        $retail_price = intval($product->getRetailPrice());
        $price = intval($product->getPrice());
        if($price > 0 && $retail_price >0){
            $discount = (int) 100 - intval(($price/$retail_price) * 100);
        }else{
            $discount = 0 ;
        }
        return $discount;

    }

}
