<?php

namespace Zemez\Amp\Observer\Category\Adminhtml;

use Magento\Framework\Event\ObserverInterface;

class CategoryObserver implements ObserverInterface
{
    /**
     * Virtual \Zemez\Amp\ImageUploader
     * @var \Magento\Catalog\Model\ImageUploader
     */
    protected $imageUploader;

    /**
     * Request
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * Constructor
     * @param \Magento\Catalog\Model\ImageUploader $imageUploader
     * @param \Magento\Framework\App\Request\Http  $request
     */
    public function __construct(
        \Magento\Catalog\Model\ImageUploader $imageUploader,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->imageUploader = $imageUploader;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

    }
}
