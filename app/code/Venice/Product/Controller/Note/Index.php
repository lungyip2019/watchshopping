<?php
namespace Venice\Product\Controller\Note;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Venice\Product\Api\NoteRepositoryInterface;
use Magento\Framework\Registry;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $productRepository;
    protected $coreRegistry;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $coreRegistry;
        $this->productRepository = $productRepository;

        return parent::__construct($context);
    }

    public function execute()
    {
        $productId = (int)$this->getRequest()->getParam('id');
        $product = $this->productRepository->getById($productId);
        $this->coreRegistry->register('current_product', $product);

        return $this->resultPageFactory->create();
    }
}