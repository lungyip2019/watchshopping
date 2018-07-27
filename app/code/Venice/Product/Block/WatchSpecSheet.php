<?php
namespace Venice\Product\Block;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Venice\Product\Model\WatchSpecRepository;
use Magento\Framework\Registry;
use Venice\Product\Logger\Logger;

class WatchSpecSheet extends Template
{

    protected $coreRegistry = null;
    protected $watchSpecRepository = null;
    protected $logger;
    protected $_product;
    
    public function __construct(Context $context,
    WatchSpecRepository $watchSpecRepository,
    Registry $registry,
    Logger $logger,
    array $data=[])
	{
        $this->logger = $logger;
        $this->logger->debug("initalizing watch spec block");
        $this->watchSpecRepository = $watchSpecRepository;
        $this->coreRegistry = $registry;
		parent::__construct($context,$data);
	}

    /**
     * @return Product
     */
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->coreRegistry->registry('product');
        }
        return $this->_product;
    }

    /**
     * @return array
     */
	public function getDetail()
	{
         // get current product
         $product = $this->getProduct();         
         $sku = $product->getSku();
         // should wrap this with try catch
         $spec = $this->watchSpecRepository->getBySku($sku);                  
         if($spec){
            $data = json_decode($spec->getDetail(), TRUE);
            return $data;
         }else{
            return null;
         }        
	}
}