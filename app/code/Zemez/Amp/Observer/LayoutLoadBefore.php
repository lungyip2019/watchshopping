<?php

namespace Zemez\Amp\Observer;
use Magento\Framework\Event\ObserverInterface;

class LayoutLoadBefore implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Response\Http
     */
    protected $_response;

    /**
     * @var \Zemez\Amp\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @param \Magento\Framework\App\Response\Http $response
     * @param \Zemez\Amp\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Framework\App\Response\Http $response,
        \Zemez\Amp\Helper\Data $dataHelper
    ) {
        $this->_response = $response;
        $this->_dataHelper = $dataHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_dataHelper->extEnabled()){
            return;
        }

        // Get full action name and update object
        $currentFullAction = $this->_dataHelper->getFullActionName();
        $update = $observer->getEvent()->getLayout()->getUpdate();

        if($this->_dataHelper->isOnlyOptReq()) {
            $update->addHandle('zamp_catalog_product_view_only_options');
            return true;
        }

        // Check get parameter amp
        if ($this->_dataHelper->isAmpCall()) {
            if (function_exists('newrelic_disable_autorum')) {
                newrelic_disable_autorum();
            }

            //  Add layout handlers
            $update->addHandle('tm_amp');
            foreach ($update->getHandles() as $handleName) {
                $update->addHandle('zamp_' . $handleName);
            }
        }

        /**
         * Add layout changes
         */
        if ($this->_dataHelper->isAllowedPage()) {
            $update->addHandle('zamp_ampurl_page');
        }

    }

}
