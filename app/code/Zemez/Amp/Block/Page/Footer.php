<?php

namespace Zemez\Amp\Block\Page;

class Footer extends \Magento\Theme\Block\Html\Footer
{
    /**
     * @var string
     */
    protected $_template = 'Zemez_Amp::footer.phtml';

    /**
     * @var \Zemez\Amp\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     */
    public function __construct(
        \Zemez\Amp\Helper\Data $dataHelper,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = []
    ) {
        $this->_dataHelper = $dataHelper;
        $this->httpContext = $httpContext;
        parent::__construct($context, $httpContext, $data);
    }

    /**
     * Get cache key informative items
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return [
            'PAGE_FOOTER',
            $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH),
            $this->_storeManager->getStore()->getId(),
            $this->_design->getDesignTheme()->getId(),
            $this->_dataHelper->isAmpCall(),
            (int)$this->_storeManager->getStore()->isCurrentlySecure(),
        ];
    }
}