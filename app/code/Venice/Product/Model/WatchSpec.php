<?php
namespace Venice\Product\Model;
use Venice\Product\Api\Data\WatchSpecInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class WatchSpec extends AbstractModel implements IdentityInterface,WatchSpecInterface
{
    
    const CACHE_TAG = 'venice_product_watchspec';
    protected $_cacheTag = 'venice_product_watchspec';
	protected $_eventPrefix = 'venice_product_watchspec';
    private $extenstionAttributes;

    protected function _construct()
    {
        $this->_init('Venice\Product\Model\ResourceModel\WatchSpec');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
    
    public function getDefaultValues()
	{
		$values = [];
		return $values;
    }
    
    public function getId(){
        return $this->getData(self::WATCH_SPEC_ID);    
    }

    public function setId($id){
        return $this->setData(self::WATCH_SPEC_ID,$id);    
    }

    public function getProductId(){
        return $this->getData(self::PRODUCT_ID);    
    }

    public function setProductId($productId){
        return $this->setData(self::PRODUCT_ID,$productId);    
    }

    public function getSku(){
        return $this->getData(self::SKU);    
    }

    public function setSku($sku){
        return $this->setData(self::SKU,$sku);    
    }

    public function getDetail(){
        return $this->getData(self::DETAIL);    
    }

    public function setDetail($detail){
        return $this->setData(self::DETAIL,$detail);    
    }

    public function getStatus(){
        return $this->getData(self::STATUS);    
    }

    public function setStatus($status){
        return $this->setData(self::STATUS,$status);    
    }

    public function getByProductId($product_id)
    {
        return $this->getCollection()
        ->addFieldToFilter('product_id',array('eq'=>$product_id))
        ->getFirstItem();
    }
    
    public function getBySku($sku){
        return $this->getCollection()
        ->addFieldToFilter('sku',array('eq'=>$sku))
        ->getFirstItem();
    }

    /**
     * @inheritdoc
     */
    public function setExtensionAttributes(
        \Venice\Product\Api\Data\WatchSpecExtensionInterface $extensionAttributes
    )
    {
        $this->extenstionAttributes = $extensionAttributes;
        return $this;
    }
    /**
     * @inheritdoc
     */
    public function getExtensionAttributes()
    {
        return $this->extenstionAttributes;
    }
}
?>