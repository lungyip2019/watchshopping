<?php
namespace TemplateMonster\Blog\Block\Adminhtml\Post\Edit;

/**
 * Admin post left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('post_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Post Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab(
            'related_products',
            [
                'label' => __('Related Products'),
                'url' => $this->getUrl('*/*/relatedProducts', ['_current' => true]),
                'class' => 'ajax',
            ]
        );
        $this->addTab(
            'related_posts',
            [
                'label' => __('Related Posts'),
                'url' => $this->getUrl('*/*/relatedPosts', ['_current' => true]),
                'class' => 'ajax',
            ]
        );
        if ($post = $this->_coreRegistry->registry('tm_blog_post')) {
            if ($post->getId()) {
                $this->addTab(
                    'related_comments',
                    [
                        'label' => __('Post Comments'),
                        'url' => $this->getUrl('*/*/relatedComments', ['_current' => true]),
                        'class' => 'ajax',
                    ]
                );
            }
        }
        return parent::_beforeToHtml();
    }
}
