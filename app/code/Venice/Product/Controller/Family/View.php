<?php
/**
 * Created by PhpStorm.
 * User: sunny
 * Date: 12/7/2018
 * Time: 2:33 PM
 */

namespace Venice\Product\Controller\Family;


use Magento\Framework\Controller\ResultFactory;
use Venice\Product\Api\Data\ProductFamilyInterface;
use Venice\Product\Api\FamilyRepositoryInterface;
use TemplateMonster\ShopByBrand\Api\BrandRepositoryInterface;
use Magento\Framework\Registry as CoreRegistry;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Catalog\Model\Layer\Resolver;

/**
 * Family view page.
 *
 * @package Venice\Product\Controller\Family
 */
class View extends Action
{

    protected $_coreRegistry;

    protected $_brandRepository;

    protected $_familyRepository;

    protected $_layerResolver;

    /**
     * View constructor.
     *
     * @param Context                  $context
     */
    public function __construct(
        FamilyRepositoryInterface $familyRepository,
        BrandRepositoryInterface $brandRepository,
        CoreRegistry $coreRegistry,
        Context $context,
        Resolver $layerResolver,
        array $data = []
    ) {

        $this->_coreRegistry = $coreRegistry;
        $this->_familyRepository = $familyRepository;
        $this->_brandRepository = $brandRepository;
        $this->_layerResolver = $layerResolver;
        parent::__construct(
            $context
        );
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $id = (int) $this->getRequest()->getParam('family_id');

        return $this->_initPage($this->_getFamily($id));
    }

    /**
     * @param int $id
     *
     * @return ProductFamilyInterface | null
     */
    protected function _getFamily($id)
    {
       return $this->_familyRepository->getById($id);
    }

    /**
     * Init page.
     *
     *
     * @return \Magento\Framework\View\Result\Page
     */
    protected function _initPage($family)
    {
        /** @var \Magento\Framework\View\Result\Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->addHandle('catalog_category_view_type_layered');

        $brandId = $family->getBrandId();
        $brandModel = $this->_brandRepository->getById($brandId);
        $collection = $this->_layerResolver->get()->getProductCollection();
        $collection->addFieldToFilter('family_id',$family->getId());
        $page->getConfig()->getTitle()->set(__($family->getTitle()));
        $this->_coreRegistry->register('current_brand',$brandModel);
        $this->_coreRegistry->register('current_family',$family);
        $list = $page->getLayout()->getBlock('category.products.list');
        $list->setProductCollection($collection);

        if ($breadcrumbs = $page->getLayout()->getBlock('breadcrumbs')) {
            /** @var \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbs */
            $breadcrumbs->addCrumb('brands', [
                'label' => __('Brand'),
                'title' => __('Go to Brand Page'),
                'link' => $this->_url->getUrl('brand')
            ]);
            $breadcrumbs->addCrumb('brands', [
                'label' => __($brandModel->getName()),
                'title' => __($brandModel->getName()),
                'link' => $this->_url->getUrl($brandModel->getUrlKey())
            ]);
            $breadcrumbs->addCrumb('brand_view', [
                'label' => $family->getName(),
                'title' => $family->getName(),
            ]);
        }

        return $page;
    }
}


