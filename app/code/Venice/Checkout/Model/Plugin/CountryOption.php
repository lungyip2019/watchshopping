<?php
namespace Venice\Checkout\Model\Plugin;

use Magento\Directory\Model\ResourceModel\Country\Collection;

// Todo add a Country option before all attribute
class CountryOption
{
    public function afterToOptionArray(Collection $subject, array $result)
    {
            $result['0']['label'] = __('Country*');

        return $result;
    }
}