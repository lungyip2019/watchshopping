<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Venice\Checkout\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Checkout\Model\Session;
use Magento\Checkout\Block\Onepage\link;
use Magento\Checkout\Block\Cart;



class CartHeader extends Template
{
    protected  $cart;
    protected  $checkoutSession;
    protected  $link;

    public function __construct(
        Session $checkoutSession,
        Cart $cart,
        link $link,
        Context $context,
        array $data = [],
        array $layoutProcessors = []
    ) {
        $this->cart =$cart;
        $this->checkoutSession = $checkoutSession;
        $this->link =$link;
        parent::__construct($context, $data);
    }




    public function getItemsSummaryQty()
    {
        return $this->cart->getItemsSummaryQty();
    }


    public function getCheckoutUrl()
    {
        return $this->cart->getCheckoutUrl();
    }

    public function getContinueShoppingUrl()
    {
        return $this->cart->getContinueShoppingUrl();
    }

    public function isDisabled(){
        return $this->link->isDisabled();

    }

    public function isPossibleCheckout()
    {
        return $this->link->isPossibleOnepageCheckout();
    }


}

?>


