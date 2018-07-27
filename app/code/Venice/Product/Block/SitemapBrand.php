<?php

namespace Venice\Product\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use TemplateMonster\ShopByBrand\Model\ResourceModel\Brand\CollectionFactory as BrandCollectionFactory;
use Venice\Product\Model\ProductFamilyFactory;
use \Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\StoreManagerInterface;

class SitemapBrand extends Template
{
    protected $storeManager;
    protected $productFamilyFactory;
    protected $brandCollectionFactory;
    protected $resource;

    public function __construct(

        Context $context,
        StoreManagerInterface $storeManager,
        BrandCollectionFactory $brandCollectionFactory,
        ProductFamilyFactory $productFamilyFactory,
        ResourceConnection $resource,
        array $data = []
    ) {
        $this->brandCollectionFactory = $brandCollectionFactory;
        $this->productFamilyFactory = $productFamilyFactory;
        $this->resource = $resource;
        $this->storeManager  = $storeManager;
        parent::__construct($context, $data);
    }


    public function getBrands($storeId = null)
    {
        $store = $this->storeManager->getStore($storeId);
        if (!$store) {
            return false;
        }

        $collections = $this->brandCollectionFactory->create();

        $second_table_name = $this->resource->getTableName('product_family');
        $select = $collections->getSelect()->join(array('family' => $second_table_name),
            'main_table.brand_id = family.brand_id',
            ['family_name' =>'family.name', 'family_url' =>'family.url_key', 'brand_name' => 'main_table.name']
        )->where(
            'main_table.website_id = ?', $store->getId()
        )->order('brand_name')->order( 'family_name');

        $query = $collections->getConnection()->query($select);
        $brandName="";
        $pages = [];

        while ($rows   = $query->fetch()) {
            $object = new \Magento\Framework\DataObject();
            $object->setId($rows['brand_id']);
            $object->setfamilyName($rows['family_name']);
            $object->setUrl($rows['url_key']);
            $object->setFamilyUrl($rows['family_url']);
            $name = $rows['name'];

            if ($name != $brandName) {
                $object->setName(strtoupper($name));
            } else
                $object->setName(null);

            $brandName =$rows['brand_name'];
            $page = $object;
            $pages[$page->getFamilyName()] = $page;
        }

        return $pages;
    }

    public function getStoreUrl(){
        return $this->storeManager->getStore()->getBaseUrl();
    }
}








