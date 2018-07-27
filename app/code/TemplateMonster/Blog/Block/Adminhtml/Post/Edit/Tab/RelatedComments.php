<?php
namespace TemplateMonster\Blog\Block\Adminhtml\Post\Edit\Tab;

use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;

class RelatedComments extends Extended implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_commentFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_commentsCollectionFactory = null;

    protected $formKey;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $coreRegistry,
        \TemplateMonster\Blog\Model\ResourceModel\Comment\CollectionFactory $commentCollectionFactory,
        //\Magento\Framework\Data\Form\FormKey $formKey,
        array $data = []
    ) {
        //$this->formKey = $formKey;
        $this->formKey = $context->getFormKey();
        $this->_commentCollectionFactory = $commentCollectionFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Set grid params
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('related_comments');
        $this->setDefaultSort('comment_id');
        $this->setUseAjax(true);
        if ($this->getComment() && $this->getComment()->getId()) {
            $this->setDefaultFilter(['in_comments' => 1]);
        }
        if ($this->isReadonly()) {
            $this->setFilterVisibility(false);
        }
    }

    /**
     * Retrieve currently edited comment model
     *
     * @return array|null
     */
    public function getPost()
    {
        return $this->_coreRegistry->registry('tm_blog_post');
    }

    /**
     * Add filter
     *
     * @param Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_comments') {
            $commentIds = $this->_getSelectedComments();
            if (empty($commentIds)) {
                $commentIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('comment_id', ['in' => $commentIds]);
            } else {
                if ($commentIds) {
                    $this->getCollection()->addFieldToFilter('comment_id', ['nin' => $commentIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * Prepare collection
     *
     * @return Extended
     */
    protected function _prepareCollection()
    {
        $this->setDefaultSort('creation_time');
        $this->setDefaultDir('desc');
        $collection = $this->getPost()->getRelatedComments();
        //    ->setOrder('creation_time', 'DESC');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Checks when this block is readonly
     *
     * @return bool
     */
    public function isReadonly()
    {
        return false;
    }

    /**
     * Add columns to grid
     *
     * @return $this
     */
    protected function _prepareColumns()
    {
        /*if (!$this->isReadonly()) {
            $this->addColumn(
                'in_comments',
                [
                    'type' => 'checkbox',
                    'name' => 'in_comments',
                    'values' => $this->_getSelectedComments(),
                    'align' => 'center',
                    'index' => 'comment_id',
                    'header_css_class' => 'col-select',
                    'column_css_class' => 'col-select'
                ]
            );
        }*/

        $this->addColumn(
            'column_comment_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'comment_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'column_author',
            [
                'header' => __('Author'),
                'index' => 'author',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name'
            ]
        );

        $this->addColumn(
            'content_comment',
            [
                'header' => __('Text'),
                'index' => 'content',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name'
            ]
        );

        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name',
                'type' => 'options',
                'options' => [ 1 => 'Approved', 0 => 'Hidden', -1 => 'Pending'],
            ]
        );

        $this->addColumn(
            'creation_time',
            [
                'header' => __('Added'),
                'index' => 'creation_time',
                'type' => 'datetime',
                'header_css_class' => 'col-date',
                'column_css_class' => 'col-date',
                'sortable' => false,
                'filter' => false,
            ]
        );

        $this->addColumn(
            'edit',
            [
                'header' => __('edit'),
                'index' => 'comment_id',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name',
                'renderer' => 'TemplateMonster\Blog\Block\Adminhtml\Post\Edit\Tab\Grid\Renderer\Edit',
                'filter' => false

            ]
        );


        /*$this->addColumn(
            'column_is_visible',
            [
                'header' => __('Visibility'),
                'index' => 'is_visible',
                'type' => 'options',
                'options' => [ 1 => 'Visible', 2 => 'Hidden'],
                'header_css_class' => 'col-status',
                'column_css_class' => 'col-status',
            ]
        );*/

        /*$this->addColumn(
            'position',
            [
                'header' => __('Position'),
                'name' => 'position',
                'type' => 'number',
                'validate_class' => 'validate-number',
                'index' => 'position',
                'editable' => true,
                'edit_only' => false,
                'sortable' => false,
                'filter' => false,
                'header_css_class' => 'col-position',
                'column_css_class' => 'col-position'
            ]
        );*/

        return parent::_prepareColumns();
    }

    /**
     * Prepare grid massaction actions
     *
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('in_comments');
        $this->getMassactionBlock()->setFormFieldName('comments');
        $this->getMassactionBlock()->setTemplate('TemplateMonster_Blog::widget/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->addItem(
            'Approve',
            [
                'label' => __('Approve'),
                'url' => $this->getUrl(
                    '*/*/RelatedCommentsShow',
                    array('form_key' => $this->formKey->getFormKey(),
                        'post_id' => $this->getPost()->getId())
                )
            ]
        );

        $this->getMassactionBlock()->addItem(
            'Hide',
            [
                'label' => __('Hide'),
                'url' => $this->getUrl(
                    '*/*/RelatedCommentsHide',
                    array('form_key' => $this->formKey->getFormKey(),
                        'post_id' => $this->getPost()->getId())
                )
            ]
        );

        return $this;
    }

    /**
     * Rerieve grid URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getData(
            'grid_url'
        ) ? $this->getData(
            'grid_url'
        ) : $this->getUrl(
            '*/*/relatedCommentsGrid',
            ['_current' => true]
        );
    }

    /**
     * Retrieve selected related comments
     *
     * @return array
     */
    protected function _getSelectedComments()
    {
        $comments = $this->getCommentsRelated();
        if (!is_array($comments)) {
            $comments = array_keys($this->getSelectedRelatedComments());
        }
        return $comments;
    }

    /**
     * Retrieve related comments
     *
     * @return array
     */
    public function getSelectedRelatedComments()
    {
        $comments = [];
        foreach ($this->_coreRegistry->registry('tm_blog_post')->getRelatedComments() as $comment) {
            $comments[$comment->getId()] = ['position' => $comment->getPosition()];
        }
        return $comments;
    }


    public function getTabLabel()
    {
        return __('Related Comments');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Related Comments');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
}
