<?php
namespace TemplateMonster\Blog\Model\Category\Source;

use TemplateMonster\Blog\Model\Category;

class IsVisible implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \TemplateMonster\Blog\Model\Category
     */
    protected $category;

    /**
     * Constructor
     *
     * @param \TemplateMonster\Blog\Model\Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->category->getAvailableStatuses();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
