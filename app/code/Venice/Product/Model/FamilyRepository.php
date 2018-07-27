<?php

namespace Venice\Product\Model;
use Venice\Product\Model\ProductFamilyFactory;
use Magento\Store\Model\StoreManagerInterface;
use Venice\Product\Api\FamilyRepositoryInterface;
use Venice\Product\Api\Data\ProductFamilyInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Venice\Product\Logger\Logger;
use Magento\Framework\Exception\NoSuchEntityException;

class FamilyRepository implements FamilyRepositoryInterface{

    protected $familyFactory;
    protected $logger;
    protected $storeManager;

    public function __construct(
    ProductFamilyFactory $familyFactory,
    Logger $logger,
    StoreManagerInterface $storeManager){
    
        $this->logger = $logger;
        $this->familyFactory = $familyFactory;                
        $this->storeManager = $storeManager;
    }
    

    /**
     * @param ProductFamilyInterface
     * @return ProductFamilyInterface|null
     */
    public function save(ProductFamilyInterface $family){
                        
        $this->logger->debug("saving family entry",array('name'=>$family->getName()));
        if (empty($family->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $family->setStoreId($storeId);
        }
        
        try {
            $this->logger->debug("saving family",array('product_family'=>$family->getData()));
            $family->save();
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the watch_spec: %1',
                $exception->getMessage()
            ));
        }
        return $family;    
    }

    
    /**
     * @param int $family_id
     * @return ProductFamilyInterface|null
     */
    public function getById($family_id){
        $this->logger->debug("get by id",array('family_id'=>$family_id));
        $family = $this->familyFactory->create();
        $data = $family->load($family_id);
        if(!$data->getId()){            
            NoSuchEntityException::singleField('family_id',$family_id);            
        }
        return $data;
    }

    

    /**
     * @param int $brand_id
     * @return ProductFamilyInterface[]|null
     */
    public function getByBrand($brand_id){
        $this->logger->debug("get family by brand",array('brand_id'=>$brand_id));
        $productFamily = $this->familyFactory->create();
        //database operation        
        $data = $productFamily
        ->getCollection()
        ->addFieldToSelect('*')
        ->addFieldToFilter('brand_id',array('eq'=>$brand_id))
        ->addFieldToFilter('status',array('eq'=>1))
        ->load();        
        
        return $data;
    }

    /**
     * @param string $identifier
     * @return ProductFamilyInterface|null
     */
    public function getByIdentifier($identifier){
        $this->logger->debug("get by identifier",array('identifier'=>$identifier));
        $family = $this->familyFactory->create();
        $data = $family
            ->load($identifier,'url_key');

        return $data;
    }



    /**
     * @param ProductFamilyInterface $productfamily
     * @return ProductFamilyInterface|null
     */
    public function delete(ProductFamilyInterface $productfamily){
        $family_id = $productfamily->getId();
        $this->logger->debug("delete by id",array('family_id'=>$family_id));
        $target= $this->familyFactory->create();
        $data = $target->load($family_id);
        return $data->delete();
    }
    
    /**
     * @param int $family_id
     * @return ProductFamilyInterface|null
     */
    public function deleteById($family_id){
        $this->logger->debug("delete by id",array('family_id'=>$family_id));
        $productFamily = $this->familyFactory->create();
        $data = $productFamily->load($family_id);
        return $data->delete();
    }
}
?>

