<?php

namespace Venice\Product\Api\Data;
use Magento\Framework\Api\ExtensibleDataInterface;



/**
 * Family Interface
 *
 * @api
 */
interface ProductFamilyInterface {

    const FAMILY_ID = 'family_id';
    const BRAND_ID = 'brand_id';
    const NAME = 'name';
    const STATUS = 'status';
    const URL_KEY = 'url_key';
    const TITLE = 'title';
    const LOGO =  'logo';    
    const FAMILY_BANNER = 'family_banner';
    const SHORT_DESCRIPTION = "short_description";
    const MAIN_DESCRIPTION = "main_description";
    const META_KEYWORDS = 'meta_keywords';
    const META_DESCRIPTION = 'meta_description';
    const WEBSITE_ID = 'website_id';
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
     * Get Name of the family 
     * @return string
     */
    public function getName();

    /**
     * Set name of the family
     * @param string $name
     * @return $this
     */
    public function setName($name);

     /**
     * Get url key
     *
     * @return string
     */
    public function getUrlKey();

    /**
     * Set url key
     *
     * @param int $url_key
     *
     * @return $this
     */
    public function setUrlKey($url_key);


    /**
     * Get related title
     *
     * @return string
     */
    public function getTitle();
    
    

    /**
     * set related title
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title);



    /**
     * Get related logo filename
     *
     * @return string
     */
    public function getLogo();
    
    

    /**
     * set related logo filename
     *
     * @param string $logo
     *
     * @return $this
     */
    public function setLogo($logo);
    

    /**
     * Get related brand
     *
     * @return int
     */
    public function getBrandId();
    
    

    /**
     * set related brand
     *
     * @param int $brand_id
     *
     * @return $this
     */
    public function setBrandId($brand_id);
    



    /**
     * Get related family banner filename
     *
     * @return string
     */
    public function getFamilyBanner();
    
    

    /**
     * set related family banner filename
     *
     * @param string $family_banner
     *
     * @return $this
     */
    public function setFamilyBanner($family_banner);
    


 

    /**
     * Get short description 
     *
     * @return string
     */
    public function getShortDescription();
    
    

    /**
     * set short description
     *
     * @param string $short_description
     *
     * @return $this
     */
    public function setShortDescription($short_description);


      /**
     * Get main description 
     *
     * @return string
     */
    public function getMainDescription();
    
    

    /**
     * set main description
     *
     * @param string $main_description
     *
     * @return $this
     */
    public function setMainDescription($main_description);

     /**
     * Get meta keywords
     *
     * @return string
     */
    public function getMetaKeywords();
    
    /**
     * set meta keywords
     *
     * @param string $meta_keywords
     *
     * @return $this
     */
    public function setMetaKeywords($meta_keywords);


    

    /**
     * set meta description
     *
     * @param string $meta_description
     *
     * @return $this
     */
    public function setMetaDescription($meta_description);


     /**
     * Get meta description 
     *
     * @return string
     */
    public function getMetaDescription();
    
    

    /**
     * get web site id
     *
     *
     * @return int
     */
    public function getWebSiteId();


    /**
     * set short description
     *
     * @param int $website_id
     *
     * @return $this
     */
    public function setWebSiteId($website_id);

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

}


?>