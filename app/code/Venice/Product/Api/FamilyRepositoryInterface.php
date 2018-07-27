<?php

namespace Venice\Product\Api;
use Venice\Product\Api\Data\ProductFamilyInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface FamilyRepositoryInterface{


    /**
     * Save watch spec 
     * @api
     * @param \Venice\Product\Api\Data\ProductFamilyInterface $family
     * @return \Venice\Product\Api\Data\ProductFamilyInterface
     */
    public function save(ProductFamilyInterface $family);


    /**
     * Get family by sku.
     * @param int $brandId
     * @return \Venice\Product\Api\Data\ProductFamilyInterface
     */
    public function getByBrand($brandId);


    /**
     * Get family by id.
     * @api
     * @param int $familyId
     * @return \Venice\Product\Api\Data\ProductFamilyInterface
     */
    
     public function getById($familyId);
     // public function getList(SearchCriteriaInterface $searchCriteria);
    
     /**
     * Delete Family By Object
     * @param \Venice\Product\Api\Data\ProductFamilyInterface $productFamily
     * @return \Venice\Product\Api\Data\ProductFamilyInterface
     */
    
     public function delete(ProductFamilyInterface $productFamily);
    
     /**
     * Delete Family By Id
     * @param int $familyId
     * @return ProductFamilyInterface
     */
    public function deleteById($familyId);


    /**
     * Get Family By Identifier
     * @param string $identifer
     * @return ProductFamilyInterface
     */
    public function getByIdentifier($identifer);
}
?>
