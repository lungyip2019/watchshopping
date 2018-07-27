<?php
/**
 * Copyright © 2016 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace TemplateMonster\GoogleMap\Block\Adminhtml\System\Config\Form\Field;

/**
 * Backend system config array field renderer
 */
class Markers extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{

    protected $_getMarkerImages;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    )
    {
        parent::__construct($context,$data);
    }

    protected function _construct()
    {
        $this->_addButtonLabel = __('Add Marker');
        parent::_construct();
    }

    protected function _prepareToRender() {
        $this->addColumn('icon',        ['label' => __('Icon'), 'size' => 3]);
        $this->addColumn('coordinates', ['label' => __('Coordinates')]);
        $this->addColumn('infowindow',  ['label' => __('Infowindow')]);
        $this->_addButtonLabel = __('Add Marker');
        $this->_addAfter = false;
    }


    /**
     * Render array cell for prototypeJS template
     *
     * @param string $columnName
     * @return string
     * @throws \Exception
     */
    public function renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new \Exception('Wrong column name specified.');
        }

        $column = $this->_columns[$columnName];
        $inputName = parent::_getCellInputElementName($columnName);

        if ($columnName == 'infowindow') {
            return '<textarea id="' . $this->_getCellInputElementId(
                '<%- _id %>',
                $columnName
            ) .
            '"' .
            ' name="' .
            $inputName .
            '" value="<%- ' .
            $columnName .
            ' %>" ' .
            ($column['size'] ? 'size="' .
                $column['size'] .
                '"' : '') .
            ' class="' .
            (isset(
                $column['class']
            ) ? $column['class'] : 'input-text') . '"' . (isset(
                $column['style']
            ) ? ' style="' . $column['style'] . '"' : '') . '></textarea>';
        } else {
            return parent::renderCellTemplate($columnName);
        }

    }
}
