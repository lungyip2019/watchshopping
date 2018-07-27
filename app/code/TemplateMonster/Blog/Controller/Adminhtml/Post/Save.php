<?php
namespace TemplateMonster\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

class Save extends \Magento\Backend\App\Action
{
    private $postFactory;

    protected $_session;

    protected $cacheTypeList;

    public function __construct(
        Action\Context $context,
        \TemplateMonster\Blog\Model\PostFactory $postFactory,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
    ) {
        //$this->_session = $session;
        $this->_session = $context->getSession();
        $this->postFactory = $postFactory;
        $this->cacheTypeList = $cacheTypeList;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('TemplateMonster_Blog::post_save');
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            /** @var \TemplateMonster\Blog\Model\Post $model */
            $model = $this->postFactory->create();

            $id = $this->getRequest()->getParam('post_id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);

            $this->_eventManager->dispatch(
                'blog_post_prepare_save',
                ['post' => $model, 'request' => $this->getRequest()]
            );

            try {
                $imageField = 'post_image';
                $fileSystem = $this->_objectManager->create('Magento\Framework\Filesystem');
                $mediaDirectory = $fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);

                if (isset($data[$imageField]) && isset($data[$imageField]['value'])) {
                    if (isset($data[$imageField]['delete'])) {
                        unlink($mediaDirectory->getAbsolutePath() . $data[$imageField]['value']);
                        //$model->setData($imageField, '');
                        $model->setData('image', null);
                    } else {
                        $model->unsetData($imageField);
                    }
                }
                try {
                    $uploader = $this->_objectManager->create('Magento\MediaStorage\Model\File\UploaderFactory');
                    $uploader = $uploader->create(['fileId' => $imageField]);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $uploader->setAllowCreateFolders(true);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath('tm_blog')
                    );
                    $model->setData('image', 'tm_blog' . $result['file']);
                } catch (\Exception $e) {
                    if ($e->getCode() != \Magento\Framework\File\Uploader::TMP_NAME_EMPTY) {
                        $this->messageManager->addError($e->getMessage());
                    }
                }
                $model->save();
                $this->messageManager->addSuccess(__('You saved this Post.'));

                $this->cacheTypeList->invalidate('full_page');

                $this->_session->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['post_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the post.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['post_id' => $this->getRequest()->getParam('post_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
