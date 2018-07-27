<?php
namespace TemplateMonster\Blog\Block\Adminhtml\Comment;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize tm_blog comment edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'comment_id';
        $this->_blockGroup = 'TemplateMonster_Blog';
        $this->_controller = 'adminhtml_comment';

        parent::_construct();

        if ($this->_isAllowedAction('TemplateMonster_Blog::comment_save')) {
            $this->buttonList->update('save', 'label', __('Save Comment'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }

        if ($this->_isAllowedAction('TemplateMonster_Blog::comment_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Comment'));
        } else {
            $this->buttonList->remove('delete');
        }
    }

    /**
     * Retrieve text for header element depending on loaded comment
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($comment = $this->_coreRegistry->registry('tm_blog_comment')) {
            if ($comment->getId()) {
                return __("Edit Comment by '%1'", $comment->getAuthor());
            } else {
                return __('New Comment');
            }
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('tm_blog/*/save', ['_current' => true, 'back' => 'edit']);
    }
}
