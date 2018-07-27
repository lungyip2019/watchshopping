<?php

namespace Zemez\Amp\Model\Attribute;
class Mode extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    const DM_DEFAULT = 'DEFAULT';
    const DM_PAGE = 'PAGE';
    const DM_PRODUCT = 'PRODUCTS';
    const DM_MIXED = 'PRODUCTS_AND_PAGE';

    /**
     * Amp Display Mode options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $options = [
            ['value' => self::DM_DEFAULT, 'label' => __('Default Mode')],
            ['value' => self::DM_PRODUCT, 'label' => __('Products only')],
            ['value' => self::DM_PAGE, 'label' => __('Static block only')],
            ['value' => self::DM_MIXED, 'label' => __('Static block and products')]
        ];

        return $options;
    }
}
