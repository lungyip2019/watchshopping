<?php

namespace TemplateMonster\Blog\Controller\Adminhtml\Comment;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use TemplateMonster\Blog\Api\Data\CommentInterface;

/**
 * Blog comment grid inline edit controller
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    private $commentFactory;

    /** @var JsonFactory  */
    protected $jsonFactory;

    protected $cacheTypeList;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \TemplateMonster\Blog\Model\CommentFactory $commentFactory
    ) {
        $this->commentFactory = $commentFactory;
        parent::__construct($context);
        $this->cacheTypeList = $cacheTypeList;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $commentItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->isAjax() && count($commentItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach ($commentItems as $commentReceived) {
            /** @var \TemplateMonster\Blog\Model\Comment $comment */
            $comment = $this->commentFactory->create()->load($commentReceived['comment_id']);
            try {
                $extendedCommentData = $comment->getData();
                $this->setCommentData($comment, $extendedCommentData, $commentReceived);
                $comment->save();
                $this->cacheTypeList->invalidate('full_page');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithCommentId($comment, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithCommentId($comment, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithCommentId(
                    $comment,
                    __('Something went wrong while saving the comment.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add comment title to error message
     *
     * @param \TemplateMonster\Blog\Model\Comment $comment
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithCommentId($comment, $errorText)
    {
        return '[Comment ID: ' . $comment->getId() . '] ' . $errorText;
    }

    /**
     * Set blog comment data
     *
     * @param \TemplateMonster\Blog\Model\Comment $comment
     * @param array $extendedCommentData
     * @param array $commentData
     * @return $this
     */
    public function setCommentData(\TemplateMonster\Blog\Model\Comment $comment, array $extendedCommentData, array $commentData)
    {
        $comment->setData(array_merge($comment->getData(), $extendedCommentData, $commentData));
        return $this;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('TemplateMonster_Blog::comment_save');
    }
}
