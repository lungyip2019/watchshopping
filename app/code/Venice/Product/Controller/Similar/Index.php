<?php

namespace Venice\Product\Controller\Similar;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\TestFramework\Inspection\Exception;
use Magento\Catalog\Helper\ImageFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * Catalog product model
     *
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * Core model store manager interface
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;
    
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;
    
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    /**
     *
     */
    protected $catalogConfig;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Registry $coreRegistry
     * @param ProductStatus $productStatus
     * @param ProductVisibility $productVisibility
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $coreRegistry,    
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        ProductFactory $productFactory,
        ImageFactory $imageFactory
    ) {
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
        $this->coreRegistry = $coreRegistry;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->imageFactory = $imageFactory;
        $this->productFactory = $productFactory;
        parent::__construct($context);
    }
    /**
     * Show list of product
     *
     * @return \Magento\Framework\Controller\Result\JsonFactory
     */
    public function execute()
    {
       
        if($id = $this->getRequest()->getParam('productId')){
            $curPage = $this->getRequest()->getParam('page')?$this->getRequest()->getParam('page'):0;
            $size = $this->getRequest()->getParam('size')?$this->getRequest()->getParam('size'):4;
            $product = $this->productFactory->create()->load($id);
    
            $itemCollection = $product->getCollection()
            ->addAttributeToSelect(["brand_id","name","sku","image","small_image","thumbnail","style"])
            ->addAttributeToFilter("style",["in"=>$product->getStyle()])
            ->addAttributeToFilter("brand_id",["eq"=>$product->getBrandId()])
            ->addStoreFilter()
            ->setVisibility(array(2,4))
            ->setPageSize($size)
            ->setCurPage($curPage);
    
            $itemCollection->load();
            $lastpage =  $itemCollection->getLastPageNumber();
            $productData = [];
            foreach($itemCollection as $item){
                array_push($productData,array(
                    'entity_id' => $item->getId(),
                    'name' => $item->getName(),
                    'sku' => $item->getSku(),
                    'url' => $item->getProductUrl(),
                    'src' => $this->imageFactory->create()->init($item, 'product_small_image')->getUrl()
                ));
            }
    
            $final = array("data"=>$productData,"lastpage"=>$lastpage);
            return  $this->resultJsonFactory->create()->setData($final);
    
        }

    }
}