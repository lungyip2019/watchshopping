<?php
/**
 * Copyright © 2017 MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\XmlSitemap\Model\Generator;

use MageWorx\XmlSitemap\Model\ResourceModel\Catalog\ProductFactory;
use MageWorx\XmlSitemap\Helper\Data as Helper;
use Magento\Framework\ObjectManagerInterface;
/**
 * {@inheritdoc}
 */
class Product extends AbstractGenerator
{

    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * Product constructor.
     * @param Helper $helper
     * @param ObjectManagerInterface $objectManager
     * @param ProductFactory $productFactory
     * @param ProductModelFactory $productModelFactory
     */
    public function __construct(
        Helper $helper,
        ObjectManagerInterface $objectManager,
        ProductFactory $productFactory
    ) {
        $this->code = 'product';
        $this->name = __('Products');
        $this->productFactory = $productFactory;
        parent::__construct($helper, $objectManager);
    }

    /**
     * @param $storeId
     * @param $writer
     */
    public function generate($storeId, $writer)
    {
        $this->storeId = $storeId;
        $this->helper->init($this->storeId);
        $this->storeBaseUrl = $writer->storeBaseUrl;

        $priority = $this->helper->getProductPriority($storeId);
        $changefreq = $this->helper->getProductChangefreq($storeId);

        $altCodes = $this->helper->getHreflangFinalCodes($this->code);
        $productModel = $this->productFactory->create();
        $this->counter = 0;

        $hasImagesLink = $this->helper->isProductImages();
        while (!$productModel->isCollectionReaded()) {
            $collection = $productModel->getLimitedCollection($storeId,  self::COLLECTION_LIMIT);
            $alternateUrlsCollection = $this->getAlternateUrlCollection($altCodes, $collection);
            foreach ($collection as $item) {
                $images = $hasImagesLink ? $item->getImages() : false;

                $writer->write(
                    $this->getItemUrl($item),
                    $this->getItemChangeDate($item),
                    $changefreq,
                    $priority,
                    $images,
                    $alternateUrlsCollection
                );
            }
            $this->counter += count($collection);
        unset($collection);
        }
    }

    /**
     * @param $item
     * @return string
     */
    protected function getItemUrl($item)
    {
        if (strpos(trim($item->getUrl()), 'http') === 0) {
            $url = $item->getUrl();
        } else {
            $url = $this->storeBaseUrl . $item->getUrl();
        }

        return $this->helper->trailingSlash($url);
    }

    /**
     * @param $altCodes
     * @param $collection
     * @return bool
     */
    protected function getAlternateUrlCollection($altCodes, $collection)
    {
        return false;
    }
}