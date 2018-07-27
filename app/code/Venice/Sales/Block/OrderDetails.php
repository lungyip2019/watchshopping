<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Venice\Sales\Block;

use Magento\Shipping\Block\Tracking\Link;
use Magento\Sales\Block\Order\Info\Buttons;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;

class OrderDetails extends Template
{
    protected  $buttons;
    protected  $coreRegistry = null;
    protected  $shippingData;

    public function __construct(
        Buttons $buttons,
        Context $context,
        Registry $registry,
        Link $shippingData,
        array $data = []
    ) {
        $this->buttons = $buttons;
        $this->coreRegistry = $registry;
        $this->shippingData = $shippingData;
        parent::__construct($context, $data);
    }

    public function getOrder(){
        return $this->coreRegistry->registry('current_order');
    }

    public function getPrintUrl($order){
        return $this->buttons->getPrintUrl($order);
    }

    public function getReorderUrl($order){
        return $this->buttons->getReorderUrl($order);
    }

    public function getWindowUrl($model)
    {
        return $this->shippingData->getWindowUrl($model);
    }


}