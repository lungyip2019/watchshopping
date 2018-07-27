<?php

namespace Venice\Product\Block;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class VideoGallery extends Template
{
    protected $_registry;

    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    )
    {
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }


    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

}
