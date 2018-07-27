<?php
namespace Venice\Product\Block;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Venice\Product\Logger\Logger;
use Venice\Product\Model\FamilyRepository;
use Venice\Product\Api\Data\ProductFamilyInterface;

class BrandFamily extends Template
{

    protected $coreRegistry;
    protected $logger;
    protected $familyRepository;
    protected $familyCollection;

    public function __construct(Context $context,
                                Registry $registry,
                                FamilyRepository $familyRepository,
                                Logger $logger,
                                array $data=[])
    {

        $this->familyRepository = $familyRepository;
        $this->coreRegistry = $registry;
        parent::__construct($context,$data);
    }

    /**
     * @return ProductFamilyInterface[]
     */
    public function getFamily()
    {
        $brand = $this->coreRegistry->registry('current_brand');
        $brandId = $brand->getId();
        $this->familyCollection = $this->familyRepository->getByBrand($brandId);
        return $this->familyCollection;
    }

    public function getBrandName(){
        $brand = $this->coreRegistry->registry('current_brand');
        return $brand->getName();
    }

    public function getAdvertisements(){
        return [1,2,3,4,5,6];
    }


}