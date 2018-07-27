<?php

namespace Zemez\Amp\Block\Page\Head;

class Ampurl extends \Magento\Framework\View\Element\Template
{
	/**
	 * Default template for block
	 * @var string
	 */
    protected $_template = 'Zemez_Amp::head/ampurl.phtml';

    /**
     * @param \Magento\Catalog\Block\Product\Context        $context
     * @param \Zemez\Amp\Helper\Data              $dataHelper
     * @param array                                         $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Zemez\Amp\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->_dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve amp url of current page
     * @return string
     */
    public function getAmpUrl()
    {
        return $this->_dataHelper->getAmpUrl();
    }
}