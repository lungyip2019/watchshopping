<?php
namespace Venice\Product\Block;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Venice\Product\Logger\Logger;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Helper\Image;
use Magento\CatalogInventory\Api\StockRegistryInterface;

class SimilarProduct extends Template
{

    protected $coreRegistry;
    protected $logger;
    protected $productRepository;
    protected $similarCollection;
    protected $imageHelper;
    protected $stockRegistry;

    public function __construct(Context $context,
    Registry $registry,
    ProductRepositoryInterface $productRepository,
    StockRegistryInterface $stockRegistry,
    Logger $logger,
    array $data=[],
    Image $imageHelper)
	{
       
        $this->productRepository = $productRepository;
        $this->stockRegistry= $stockRegistry;
        $this->coreRegistry = $registry;
        $this->imageHelper = $imageHelper;
        $this->stockRegistry = $stockRegistry;
		parent::__construct($context,$data);
	}

    /**
     * @return ProductInterface[]
     */
    public function getSimilarProducts($pagesize,$curpage)
    {   

        $product = $this->coreRegistry->registry("current_product");        
        $this->similarCollection = $product->getCollection()
        ->addAttributeToSelect(["brand_id","name","sku","image","small_image","thumbnail","style"])
        ->addAttributeToFilter("style",["in"=>$product->getStyle()])
        ->addAttributeToFilter("brand_id",["eq"=>$product->getBrandId()])
        ->addStoreFilter()
        ->setVisibility(array(2,4))
        ->setPageSize(5)
        ->setCurPage(1);

        $this->similarCollection = $this->similarCollection->load();
        return  $this->similarCollection;
    
    }

    public function getCurrentProduct(){
        return $this->coreRegistry->registry("current_product");
    }

    public function getStockItem($productId){
        return $this->stockRegistry->getStockItem($productId);
    }

    public function getImage($product){
        return $this->imageHelper->init($product, 'product_thumbnail_image')->getUrl();
    }

}