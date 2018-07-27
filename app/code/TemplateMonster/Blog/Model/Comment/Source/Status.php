<?php
namespace TemplateMonster\Blog\Model\Comment\Source;

use TemplateMonster\Blog\Model\Comment;

class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \TemplateMonster\Blog\Model\Comment
     */
    protected $comment;

    /**
     * Constructor
     *
     * @param \TemplateMonster\Blog\Model\Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->comment->getAvailableStatuses();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
