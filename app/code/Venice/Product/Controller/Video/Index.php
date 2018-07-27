<?php

namespace Venice\Product\Controller\Video;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Registry;

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
