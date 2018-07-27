<?php
namespace TemplateMonster\Blog\Block\Adminhtml\Comment\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('tm_blog_comment');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $form->setHtmlIdPrefix('comment_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getCommentId()) {
            $fieldset->addField('comment_id', 'hidden', ['name' => 'comment_id']);
        }

        if ($model->getPostId()) {
            $fieldset->addField('post_id', 'hidden', ['name' => 'post_id']);
        }

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
            'content',
            'textarea',
            [
                'name' => 'content',
                'label' => __('Comment'),
                'title' => __('Comment'),
                'required' => true,
                'class' => 'validate-length maximum-length-255',
            ]
        );

        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
                'required' => true,
                'options' => ['1' => __('Approved'), '0' => __('Hidden')]
            ]
        );

        if (!$model->getId()) {
            $model->setData('status', '1');
        }

        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $fieldset->addField(
            'creation_time',
            'date',
            [
                'label' => __('Comment Date'),
                'title' => __('Comment Date'),
                'name' => 'creation_time',
                'date_format' => $dateFormat,
                'note' => __('Will be set to current date if empty'),
                'class' => 'validate-date'
                //'required' => true
            ]
        );


        $form->setValues($model->getData());

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
