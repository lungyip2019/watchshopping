<?php
namespace Venice\Checkout\Model\Plugin;

use Magento\Checkout\Block\Checkout\LayoutProcessor;

// Todo change the checkout display into two field

/**
* Class AddressLayoutProcessor
*/
class AddressLayoutProcessor
{
/**
* @param LayoutProcessor $subject
* @param array $jsLayout
* @return array
*/
public function afterProcess(LayoutProcessor $subject, array $jsLayout)
{

// the orinal directory of shipping address
$shippingAddressFields = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];

// New directory field of Contact Information
$contactFieldGroup = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
['children']['shippingAddress']['children']['shipping-address-fieldset']
['children']['contact-field-group']['children']['field-group']['children'];

// Add attribution to field
$contactFieldGroup['firstname'] =  $shippingAddressFields['firstname'];
$contactFieldGroup['lastname'] =  $shippingAddressFields['lastname'];
$contactFieldGroup['company'] =  $shippingAddressFields['company'];
$contactFieldGroup['telephone'] =  $shippingAddressFields['telephone'];

// New directory field of Address
$fieldGroup = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
['children']['shippingAddress']['children']['shipping-address-fieldset']
['children']['custom-field-group']['children']['field-group']['children'];

// Add attribution to field
$fieldGroup['street'] =  $shippingAddressFields['street'];
$fieldGroup['city'] =  $shippingAddressFields['city'];
$fieldGroup['region_id'] = $shippingAddressFields['region_id'];
$fieldGroup['postcode'] = $shippingAddressFields['postcode'];
$fieldGroup['country_id'] = $shippingAddressFields['country_id'];

// disable the orginal show in checkout
$shippingAddressFields['firstname']['visible'] = false;
$shippingAddressFields['lastname']['visible'] = false;
$shippingAddressFields['company']['visible'] = false;
$shippingAddressFields['telephone']['visible'] = false;
$shippingAddressFields['street']['children']['0']['visible'] = false;
$shippingAddressFields['street']['children']['1']['visible'] = false;
$shippingAddressFields['city']['visible'] = false;
$shippingAddressFields['region_id']['visible'] = false;
$shippingAddressFields['country_id']['visible'] = false;
$shippingAddressFields['postcode']['visible'] = false;

return $jsLayout;
}


}