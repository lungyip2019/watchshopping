<?php

namespace TemplateMonster\Blog\Model\Config;

class LayoutView implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('Grid with thumbnails')],
            ['value' => 1, 'label' => __('List with post title only')]
        ];
    }
}
