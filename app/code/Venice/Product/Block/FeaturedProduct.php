<?php


namespace Venice\Product\Block;
use Magento\Customer\Model\Context as CustomerContext;
use \Magento\Cms\Model\Template\FilterProvider;

class FeaturedProduct extends \TemplateMonster\FeaturedProduct\Block\FeaturedProduct\Widget\Product{


    public function __construct(
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        FilterProvider $filterProvider,
        \Magento\Directory\Model\PriceCurrency $priceCurrency,
        \Magento\Widget\Helper\Conditions $conditionsHelper,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\ProductVideo\Helper\Media $mediaHelper,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Block\Product\Context $context,
        \TemplateMonster\FeaturedProduct\Model\ProductFactory $productFactory,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = [])
    {

        $this->_stockItemRepository = $stockItemRepository;
        parent::__construct($filterProvider,$priceCurrency,$conditionsHelper,
            $jsonEncoder,$mediaHelper,$categoryCollectionFactory,
            $context,
            $productFactory,
            $httpContext, $data);
    }

    /**
     * @param $productId
     * @return boolean
     */
    public function isInStock($productId){
        return $this->_stockItemRepository->get($productId)->getIsInStock();
    }


    protected $_template = 'Venice_Product::widget/products-grid.phtml';


}

?>