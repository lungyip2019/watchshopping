<?php

namespace Venice\Product\Api;
use Venice\Product\Api\Data\WatchSpecInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface WatchSpecRepositoryInterface{



    public function save(WatchSpecInterface $watchspec);
    /**
     * Save watch spec
     * @api
     * @param \Venice\Product\Api\Data\WatchSpecInterface $watchspec
     * @return \Venice\Product\Api\Data\WatchSpecInterface
     */
    /**
     * Get watch spec by product id.
     * @param int $productId
     * @return \Venice\Product\Api\Data\WatchSpecInterface
     */
    public function getByProductId($productId);


    /**
     * Get watch spec by sku.
     * @param string $sku
     * @return \Venice\Product\Api\Data\WatchSpecInterface
     */
    public function getBySku($sku);


    /**
     * Get watch spec by spec id.
     * @api
     * @param int $specId
     * @return \Venice\Product\Api\Data\WatchSpecInterface
     */
    
     public function getById($specId);
     // public function getList(SearchCriteriaInterface $searchCriteria);
    
     /**
     * Delete WatchSpec By Object
     * @param \Venice\Product\Api\Data\WatchSpecInterface $watchspec
     * @return \Venice\Product\Api\Data\WatchSpecInterface
     */
    
     public function delete(WatchSpecInterface $watchspec);
    
     /**
     * Delete WatchSpec By Id
     * @param int $specId
     * @return WatchSpecInterface
     */
    public function deleteById($specId);
}
?>
