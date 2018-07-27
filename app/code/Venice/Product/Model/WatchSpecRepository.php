<?php

namespace Venice\Product\Model;
use Venice\Product\Model\WatchSpecFactory;
use Magento\Store\Model\StoreManagerInterface;
use Venice\Product\Api\WatchSpecRepositoryInterface;
use Venice\Product\Api\Data\WatchSpecInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Venice\Product\Logger\Logger;
use Magento\Framework\Exception\NoSuchEntityException;

class WatchSpecRepository implements WatchSpecRepositoryInterface{

    protected $watchSpecFactory;
    protected $logger;
    protected $storeManager;

    public function __construct(
    WatchSpecFactory $watchSpecFactory,
    Logger $logger,
    StoreManagerInterface $storeManager){
    
        $this->logger = $logger;
        $this->watchSpecFactory = $watchSpecFactory;                
        $this->storeManager = $storeManager;
    }
    

    /**
     * @param WatchSpecInterface
     * @return WatchSpecInterface|null
     */
    public function save(WatchSpecInterface $watchspec){
                        
        $productId = $watchspec->getProductId();
        $this->logger->debug("saving watch spec for product id",array('product_id'=>$productId));
        if (empty($watchspec->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $watchspec->setStoreId($storeId);
        }
        
        try {
            $this->logger->debug("saving watch spec",array('watch_spec',$watchspec->getData()));
            $watchspec->save();
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the watch_spec: %1',
                $exception->getMessage()
            ));
        }
        return $watchspec;    
    }

    
    /**
     * @param int $specId
     * @return WatchSpecInterface|null
     */
    public function getById($specId){
        $this->logger->debug("get by id",array('watch_spec_id'=>$specId));
        $watchSpec = $this->watchSpecFactory->create();
        $data = $watchSpec->load($specId);
        if(!$data->getId()){            
            NoSuchEntityException::singleField('watch_spec_id',$specId);            
        }
        return $data;
    }

    

    /**
     * @param int $productId
     * @return WatchSpecInterface|null
     */
    public function getByProductId($productId){
        $this->logger->debug("get by product id",array('product_id'=>$productId));
        $watchSpec = $this->watchSpecFactory->create();
        //database operation        
        $data = $watchSpec->getByProductId($productId);        
        if($data->getId()){
            return $data;
        }else{
            return null;
        }        
    }

    /**
     * @param string $sku
     * @return WatchSpecInterface|null
     */
    public function getBySku($sku){
        $this->logger->debug("get by sku",array('sku'=>$sku));
        $watchSpec = $this->watchSpecFactory->create();
        //database operation        
        $data = $watchSpec->getBySku($sku);
        $this->logger->debug("data retrieved",array('data'=>$data));
        if($data->getId()){
          return $data;
        }else{
          $this->logger->debug("no related watchspec is found");
          return null;
        }        
        
    }

    
    /**
     * @param WatchSpecInterface $watchspec
     * @return WatchSpecInterface|null
     */
    public function delete(WatchSpecInterface $watchspec){
        $specId = $watchSpec->getId();
        $this->logger->debug("delete by id",array('watch_spec_id'=>$specId));
        $watchSpec = $this->watchSpecFactory->create();
        $data = $watchspec->load($specId);
        return $data->delete();
    }
    
    /**
     * @param int $specId
     * @return WatchSpecInterface|null
     */
    public function deleteById($specId){
        $this->logger->debug("delete by id",array('watch_spec_id'=>$specId));
        $watchSpec = $this->watchSpecFactory->create();
        $data = $watchspec->load($specId);
        return $data->delete();
    }
}
?>