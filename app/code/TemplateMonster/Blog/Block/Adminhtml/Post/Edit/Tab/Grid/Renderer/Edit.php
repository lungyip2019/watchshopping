<?php
namespace TemplateMonster\Blog\Block\Adminhtml\Post\Edit\Tab\Grid\Renderer;

class Edit extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * Render action
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $href = $this->getUrl('tm_blog/comment/edit', ['comment_id' => $this->_getValue($row)]);
        return '<a href="' . $href . '" target="_blank">' . __('Edit') . '</a>';
    }
}
