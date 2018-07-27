<?php

namespace Venice\Product\Controller\Family;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\TestFramework\Inspection\Exception;
use Magento\Catalog\Helper\ImageFactory;
use Magento\Catalog\Model\ProductFactory;
use Venice\Product\Model\FamilyRepository;
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
     * @var \Venice\Product\Model\FamilyRepository
     */
    protected $familyRepository;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Registry $coreRegistry
     * @param ProductStatus $productStatus
     * @param ProductVisibility $productVisibility
     * @param \Magento\Framework\View\Result\JsonFactory $resultJsonFactory
     * @param \Venice\Product\Model\FamilyRepository $familyRepository
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $coreRegistry,    
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        ProductFactory $productFactory,
        ImageFactory $imageFactory,
        FamilyRepository $familyRepository
    ) {
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
        $this->coreRegistry = $coreRegistry;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->imageFactory = $imageFactory;
        $this->productFactory = $productFactory;
        $this->familyRepository = $familyRepository;
        parent::__construct($context);
    }
    /**
     * Show list of product
     *
     * @return \Magento\Framework\Controller\Result\JsonFactory
     */
    public function execute()
    {
       
        
        if ($id = $this->getRequest()->getParam('productId')) {
        
            $curPage = $this->getRequest()->getParam('page')?$this->getRequest()->getParam('page'):0;
            $size = $this->getRequest()->getParam('size')?$this->getRequest()->getParam('size'):4;
            $product = $this->productFactory->create()->load($id);
            $itemCollection = $product->getCollection()
            ->addAttributeToSelect(["brand_id","name","sku","image","small_image","thumbnail"])
            ->addAttributeToFilter('family_id',array('eq'=>$product->getFamilyId()))            
            ->addStoreFilter()
            ->setVisibility(array(2,4))
            ->setPageSize($size)
            ->setCurPage($curPage);            
            $itemCollection->load(); 
            $lastpage = $itemCollection->getLastPageNumber();       
            $productData = [];
            foreach($itemCollection as $item){
                array_push($productData,array(
                    'entity_id' => $item->getId(),
                    'name' => $item->getAttributeText('family_id'),
                    'brand' => $item->getAttributeText('brand_id'),
                    'sku' => $item->getSku(),
                    'url' => $item->getProductUrl(),
                    'src' => $this->imageFactory->create()->init($item, 'small_image')->getUrl()
                ));
            }
	    $final = array("data"=>$productData,"lastpage"=>$lastpage);
            return  $this->resultJsonFactory->create()->setData($final);

        }else if($brandId = $this->getRequest()->getParam('brandId')){
                  
            $familyData = [];
            // get family model and get collection
            $productFamily = $this->familyRepository->getByBrand($brandId);         
            $productFamilyData = [];
            foreach($productFamily as $item){
                array_push($productFamilyData,array(
                    'brand_id' => $item->getBrandId(),
                    'family_id'=> $item->getId(),
                    'logo' => $item->getLogo(),
                    'name' => $item->getName()
                ));
            }
            return $this->resultJsonFactory->create()->setData($productFamilyData);

        }

    }
}