<?php
namespace TemplateMonster\Blog\Controller\Adminhtml\Comment;

use Magento\Backend\App\Action;

class Edit extends \Magento\Backend\App\Action
{
    private $commentFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    protected $_session;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \TemplateMonster\Blog\Model\CommentFactory $commentFactory
        //\Magento\Backend\Model\Session $session
    ) {
        //$this->_session = $session;
        $this->_session = $context->getSession();
        $this->commentFactory = $commentFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('TemplateMonster_Blog::comment_save');
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('TemplateMonster_Blog::comment')
            ->addBreadcrumb(__('Blog'), __('Blog'))
            ->addBreadcrumb(__('Manage Blog Comments'), __('Manage Blog Comments'));
        return $resultPage;
    }

    /**
     * Edit Blog comment
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('comment_id');
        $model = $this->commentFactory->create();

        $post_id = $this->getRequest()->getParam('post_id');
        $post_title = $this->getRequest()->getParam('post_title');

        if ($post_id) {
            $model->setData('post_id', $post_id);
        }

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This comment no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_session->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('tm_blog_comment', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Blog Comment') : __('New Blog Comment'),
            $id ? __('Edit Blog Comment') : __('New Blog Comment')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Blog Comments'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Blog Comment For ' . $post_title));

        return $resultPage;
    }
}
