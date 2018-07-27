<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace TemplateMonster\Blog\Block\Adminhtml;

/**
 * Adminhtml tm_blog blocks content block
 */
class Post extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'TemplateMonster_Blog';
        $this->_controller = 'adminhtml_block';
        $this->_headerText = __('Posts');
        $this->_addButtonLabel = __('Add New Post');
        parent::_construct();
    }
}
