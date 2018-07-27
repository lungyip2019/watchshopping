<?php

namespace Venice\Product\Api\Data;
use Magento\Framework\Api\ExtensibleDataInterface;



/**
 * Watch Specification Interface
 *
 * @api
 */
interface WatchSpecInterface extends ExtensibleDataInterface{

    const DETAIL = 'detail';
    const WATCH_SPEC_ID = 'watch_spec_id';
    const PRODUCT_ID = 'product_id';
    const STATUS = 'status';
    const SKU = 'sku';
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
     /**
     * Get id.
     *
     * @return int
     */
    public function getId();

    /**
     * Set id.
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);


     /**
     * Get Detail 
     * @return string
     */
    public function getDetail();

    /**
     * Set Detail, string is JSON stringify
     * @param string $detail 
     * @return $this
     */
    public function setDetail($detail);

     /**
     * Get product id.
     *
     * @return int
     */
    public function getProductId();

    /**
     * Set related product id.
     *
     * @param int $productId
     *
     * @return $this
     */
    public function setProductId($productId);


    /**
     * Get related SKU
     *
     * @return string
     */
    public function getSku();
    
    

    /**
     * set related SKU
     *
     * @param string $sku
     *
     * @return $this
     */
    public function setSku($sku);
    


    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus();

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status);

    
    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Venice\Product\Api\Data\WatchSpecExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Venice\Product\Api\Data\WatchSpecExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Venice\Product\Api\Data\WatchSpecExtensionInterface $extensionAttributes);
}


?>