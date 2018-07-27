<?php

namespace Zemez\Amp\Model\Plugin\Magento\Swatches\LayeredNavigation;

use Zemez\Amp\Helper\Data as DataHelper;
use Magento\Swatches\Block\LayeredNavigation\RenderLayered as RenderLayered;

/**
 * Block RenderLayered Magento_Swatches
 */
class RenderLayeredPlugin
{
    /**
     * @var \Zemez\Amp\Helper\Data
     */
    protected $_dataHelper;

    /**
     * Path to AMP-template file.
     *
     * @var string
     */
    protected $_template = 'Zemez_Amp::catalog/product/layered/renderer.phtml';

    /**
     * @param DataHelper $dataHelper
     */
    public function __construct(
        DataHelper $dataHelper
    ) {
        $this->_dataHelper = $dataHelper;
    }

    /**
     * @param  RenderLayered
     * @param  string $result
     * @return string $result
     */
    public function afterGetTemplate(RenderLayered $subject, $result)
    {
        if (!$this->_dataHelper->isAmpCall()){
            return $result;
        }

        return $this->_template;
    }
}
