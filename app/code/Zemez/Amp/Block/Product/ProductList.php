<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\FeaturedProduct\Block\Product\ProductsList;

use Magento\Customer\Model\Context as CustomerContext;
use \Magento\Cms\Model\Template\FilterProvider;

class ProductList extends \Magento\CatalogWidget\Block\Product\ProductsList
{
    const DEFAULT_SORT_BY = 'id';
    
    const DEFAULT_SORT_ORDER = 'asc';
    
    public function createCollection()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());
        
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setPageSize($this->getPageSize())
            ->setCurPage($this->getRequest()->getParam($this->getData('page_var_name'), 1));
            
        $conditions = $this->getConditions();
        $conditions->collectValidatedAttributes($collection);
        $this->sqlBuilder->attachConditionToCollection($collection, $conditions);
        
        return $collection;
    }

}