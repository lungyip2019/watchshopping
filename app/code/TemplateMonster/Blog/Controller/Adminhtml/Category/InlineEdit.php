<?php

namespace TemplateMonster\Blog\Controller\Adminhtml\Category;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use TemplateMonster\Blog\Api\Data\CategoryInterface;

/**
 * Cms category grid inline edit controller
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    private $categoryFactory;

    /** @var CategoryDataProcessor */
    protected $dataProcessor;

    /** @var CategoryRepository  */
    protected $categoryRepository;

    /** @var JsonFactory  */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param CategoryDataProcessor $dataProcessor
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        //CategoryDataProcessor $dataProcessor,
        JsonFactory $jsonFactory,
        \TemplateMonster\Blog\Model\CategoryFactory $categoryFactory
    ) {
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context);
        //$this->dataProcessor = $dataProcessor;
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

        $categoryItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->isAjax() && count($categoryItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach ($categoryItems as $categoryReceived) {
            /** @var \TemplateMonster\Blog\Model\Category $category */
            $category = $this->categoryFactory->create()->load($categoryReceived['category_id']);
            try {
                $categoryData = $this->filterCategory($categoryReceived);
                //$this->validateCategory($categoryData, $category, $error, $messages);
                $extendedCategoryData = $category->getData();
                $this->setCategoryData($category, $extendedCategoryData, $categoryData);
                $category->save();
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithCategoryId($category, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithCategoryId($category, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithCategoryId(
                    $category,
                    __('Something went wrong while saving the category.')
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
     * Filtering categoryed data.
     *
     * @param array $categoryData
     * @return array
     */
    protected function filterCategory($categoryData = [])
    {
        //$categoryData = $this->dataProcessor->filter($categoryData);
        return $categoryData;
    }

    /**
     * Validate category data
     *
     * @param array $categoryData
     * @param \TemplateMonster\Blog\Model\Category $category
     * @param bool $error
     * @param array $messages
     * @return void
     */
    protected function validateCategory(array $categoryData, \TemplateMonster\Blog\Model\Category $category, &$error, array &$messages)
    {
        if (!($this->dataProcessor->validate($categoryData) && $this->dataProcessor->validateRequireEntry($categoryData))) {
            $error = true;
            foreach ($this->messageManager->getMessages(true)->getItems() as $error) {
                $messages[] = $this->getErrorWithCategoryId($category, $error->getText());
            }
        }
    }

    /**
     * Add category title to error message
     *
     * @param CategoryInterface $category
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithCategoryId(CategoryInterface $category, $errorText)
    {
        return '[Category ID: ' . $category->getId() . '] ' . $errorText;
    }

    /**
     * Set blog category data
     *
     * @param \TemplateMonster\Blog\Model\Category $category
     * @param array $extendedCategoryData
     * @param array $categoryData
     * @return $this
     */
    public function setCategoryData(\TemplateMonster\Blog\Model\Category $category, array $extendedCategoryData, array $categoryData)
    {
        $category->setData(array_merge($category->getData(), $extendedCategoryData, $categoryData));
        return $this;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('TemplateMonster_Blog::category_save');
    }
}
