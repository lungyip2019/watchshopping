<?php
namespace TemplateMonster\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use TemplateMonster\Blog\Model\ResourceModel\Comment\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class MassShow
 */
class RelatedCommentsShow  extends \Magento\Backend\App\Action
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;


    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }
    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $commentIds = $this->getRequest()->getParam('comments');
        $collection = $this->collectionFactory->create()->addFieldToFilter('comment_id', ['in' => $commentIds]);

        foreach ($collection as $item) {
            $item->setStatus(true);
            $item->save();
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been approved.', $collection->getSize()));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/post/edit', ['post_id' => $this->getRequest()->getParam('post_id'), 'active_tab' => 'related_comments']);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('TemplateMonster_Blog::comment_save');
    }
}
