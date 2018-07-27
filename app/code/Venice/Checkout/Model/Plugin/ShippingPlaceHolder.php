<?php
namespace Venice\Checkout\Model\Plugin;

use Magento\Checkout\Block\Checkout\AttributeMerger;

// Todo add the placehoder to the address.street

class ShippingPlaceHolder
{
    public function afterMerge(AttributeMerger $subject, $result)
    {
        if (array_key_exists('street', $result)) {
            $result['street']['children'][0]['label'] = __('Street Address*');
            $result['street']['children'][1]['label'] = __('Street Address 2');
        }

        return $result;
    }
}