<?php
namespace Zemez\Amp\Block\Page\Head;

class Googletagcode extends \Magento\Framework\View\Element\Text
{
    /**
     * @var \Zemez\Amp\Helper\Data
     */
    protected $_dataHelper;


    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Zemez\Amp\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Zemez\Amp\Helper\Data $dataHelper
    ) {
        $this->_dataHelper = $dataHelper;
        parent::__construct($context);
    }

    /**
     * Override parent method
     * @var void
     * @return string
     */
    public function getText()
    {
        return trim($this->_dataHelper->getGoogleTagCode());
    }
}
