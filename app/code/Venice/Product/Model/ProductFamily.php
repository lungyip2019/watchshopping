<?php
namespace Venice\Product\Model;
use Venice\Product\Api\Data\ProductFamilyInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class ProductFamily extends AbstractModel implements ProductFamilyInterface
{
    
    const CACHE_TAG = 'venice_product_productfamily';
    protected $_cacheTag = 'venice_product_productfamily';
	protected $_eventPrefix = 'venice_product_productfamily';
    private $extenstionAttributes;

    protected function _construct()
    {
        $this->_init('Venice\Product\Model\ResourceModel\ProductFamily');
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
        return $this->getData(self::FAMILY_ID);    
    }

    public function setId($id){
        return $this->setData(self::FAMILY_ID,$id);    
    }

    public function getName(){
        return $this->getData(self::NAME);   
    }
    public function setName($name){
        return $this->setData(self::NAME,$name);    
    }

    public function getUrlKey(){
        return $this->getData(self::URL_KEY);   
    }

    public function setUrlKey($url_key){
        return $this->setData(self::URL_KEY,$url_key);    
    }
    
    public function getTitle(){
        return $this->getData(self::TITLE);   
    }
     
    public function setTitle($title){
        return $this->setData(self::TITLE,$title);    
    }

    public function getBrandId(){
        return $this->getData(self::BRAND_ID);   
    }
     
    public function setBrandId($brand_id){
        return $this->setData(self::BRAND_ID,$brand_id);    
    }


    public function getLogo(){
        return $this->getData(self::LOGO);   
    }
    
    public function setLogo($logo){
        return $this->setData(self::LOGO,$logo);    
    }
    

    public function getFamilyBanner(){
        return $this->getData(self::BRAND_BANNER);   
    }
    
    
    public function setFamilyBanner($brand_banner){
        return $this->setData(self::BRAND_BANNER,$brand_banner);    
    }
    

    public function getShortDescription(){
        return $this->getData(self::SHORT_DESCRIPTION);   
    }
    

    public function setShortDescription($short_description){
        return $this->setData(self::SHORT_DESCRIPTION,$short_description);
    }


    public function getMainDescription(){
        return $this->getData(self::MAIN_DESCRIPTION);   
    }
    
    public function setMainDescription($main_description){
        return $this->setData(self::MAIN_DESCRIPTION,$main_description);
    }

    public function getMetaKeywords(){
        return $this->getData(self::META_KEYWORDS);   
    }
    
    public function setMetaKeywords($meta_keywords){
        return $this->setData(self::META_KEYWORDS,$meta_keywords);
    }

    public function setMetaDescription($meta_description){
        return $this->setData(self::META_DESCRIPTION,$meta_description);
    }


    public function getMetaDescription(){
        return $this->getData(self::META_DESCRIPTION);   
    }
    

    public function getWebSiteId(){
        return $this->getData(self::WEBSITE_ID);   
    }

    
    public function setWebSiteId($website_id){
        return $this->setData(self::WEBSITE_ID,$website_id);
    }

    public function getStatus(){
        return $this->getData(self::STATUS);   
    }

    public function setStatus($status){
        return $this->setData(self::STATUS,$status);
    }

}
?>