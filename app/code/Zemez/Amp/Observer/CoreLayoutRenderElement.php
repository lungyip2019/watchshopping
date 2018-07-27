<?php

namespace Zemez\Amp\Observer;
use Magento\Framework\Event\ObserverInterface;

class CoreLayoutRenderElement implements ObserverInterface
{
    /**
     * @var \Zemez\Amp\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * Array of elements names that need to disable for output
     * @var array
     */
    protected $_disabledElements = [];

    /**
     * Name prefix for all price blocks
     * @var string
     */
    protected $_priceElementPrefix = ' product.price';

    /**
     * @param \Magento\Framework\App\Response\Http $response
     * @param \Zemez\Amp\Helper\Data $dataHelper
     */
    public function __construct(
        \Zemez\Amp\Helper\Data $dataHelper,
        \Magento\Framework\Registry $registry
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_registry = $registry;
    }

    /**
     * @param  \Magento\Framework\Event\Observer
     * @return \Magento\Framework\Event\Observer this object
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /**
         * Checking module status
         */
        if (!$this->_dataHelper->extEnabled()){
            return $this;
        }

        /**
         * Get elemnt html and replace it
         */
            if ($this->_dataHelper->isAmpCall()) {
            $currentElementName = $observer->getElementName();
            $transport = $observer->getTransport();

            /**
             * Remove price if irame exist
             */
            if (strpos($currentElementName, $this->_priceElementPrefix) === 0) {
                $product = $this->_registry->registry('current_product');

                if ($product && $product->isSaleable()
                    && $product->getTypeInstance()->hasOptions($product)
                    && $product->isInStock()
                    && $this->_dataHelper->getIframeSrc($product)
                ) {
                    $transport->setOutput('');
                    return $this;
                }
            }

            /**
             * Disable output for disallowed elements
             */
            if ($this->_disableOutput($currentElementName)) {
                $html = '';
            } else {
                $html = $transport->getOutput();
                /* moved to ResultInterfacePlugin
                $html = $this->_replaceHtml($html);
                */
            }

            /**
             * Set final Html output
             */
            $transport->setOutput($html);
        }

        return $this;
    }

    /**
     * @param  string $elementName
     * @return boolean
     */
    protected function _disableOutput($elementName)
    {
        return in_array($elementName, $this->_disabledElements) ? true : false;
    }

}
