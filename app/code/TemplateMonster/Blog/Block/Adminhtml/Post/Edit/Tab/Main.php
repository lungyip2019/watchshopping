<?php
namespace TemplateMonster\Blog\Block\Adminhtml\Post\Edit\Tab;

/**
 * Blog post edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    protected $_categoryCollectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \TemplateMonster\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        array $data = []
    ) {
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \TemplateMonster\Blog\Model\Post */
        $model = $this->_coreRegistry->registry('tm_blog_post');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('post_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Post Information')]);

        if ($model->getId()) {
            $fieldset->addField('post_id', 'hidden', ['name' => 'post_id']);
        }

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Post Title'),
                'title' => __('Post Title'),
                'required' => true,
                'class' => 'validate-length maximum-length-255',

            ]
        );

        $fieldset->addField(
            'author',
            'text',
            [
                'name' => 'author',
                'label' => __('Author'),
                'title' => __('Author'),
                'class' => 'validate-length maximum-length-255',
                'required' => false,

            ]
        );

        $fieldset->addField(
            'identifier',
            'text',
            [
                'name' => 'identifier',
                'label' => __('Url key'),
                'title' => __('Url key'),
                'class' => 'validate-identifier validate-length maximum-length-255',

                'required' => true
            ]
        );

        /**
         * Check is single store mode
         */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store_id',
                'multiselect',
                [
                    'name' => 'stores[]',
                    'label' => __('Store View'),
                    'title' => __('Store View'),
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true),

                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'store_id',
                'hidden',
                ['name' => 'stores[]', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }

        $fieldset->addField(
            'is_visible',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Post Status'),
                'name' => 'is_visible',
                'required' => true,
                'options' => $model->getAvailableStatuses(),

            ]
        );

        $fieldset->addField(
            'comments_enabled',
            'select',
            [
                'label' => __('Comments'),
                'title' => __('Comments enabled'),
                'name' => 'comments_enabled',
                'required' => true,
                'options' => [ 1 => 'Enabled', 2 => 'Disabled'],

            ]
        );
        if (!$model->getId()) {
            $model->setData('is_visible', '1');
        }

        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $fieldset->addField(
            'creation_time',
            'date',
            [
                'label' => __('Post Date'),
                'title' => __('Post Date'),
                'name' => 'creation_time',
                'date_format' => $dateFormat,
                'note' => __('Will be set to current date if empty. Format depends on admin interface locale - "' . $dateFormat . '"'),
                // 'class' => 'validate-date'
                //'required' => true
            ]
        );

        $fieldset->addField(
            'categories',
            'multiselect',
            [
                'name' => 'categories[]',
                'label' => __('Categories'),
                'title' => __('Categories'),
                'required' => false,
                'values' => $this->_getCategoriesAsOptions(),

            ]
        );

        $this->_eventManager->dispatch('adminhtml_tm_blog_post_edit_tab_main_prepare_form', ['form' => $form]);

        $model->loadCategories();
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    private function _getCategoriesAsOptions()
    {
        $collection = $this->_categoryCollectionFactory->create();
        $options = [];
        foreach ($collection as $category) {
            $options[] = ['label' => $category->getName(), 'value' => $category->getId()];
        }
        return $options;
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Post Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Post Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
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
}
