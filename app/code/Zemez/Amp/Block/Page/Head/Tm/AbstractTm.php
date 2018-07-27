<?php

namespace Zemez\Amp\Block\Page\Head\Tm;

class AbstractTm extends \Magento\Framework\View\Element\Template
{
    const DEFAULT_ASSET_NAME = 'pramp-asset';

    /**
     * @var Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var Zemez\Amp\Helper\Data
     */
    protected $_helper;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $coreRegistry,
     * @param \Zemez\Amp\Helper\Data $helper,
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Zemez\Amp\Helper\Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_coreRegistry = $coreRegistry;
        $this->_helper = $helper;
    }

    /**
     * Retrieve common data
     * @return array
     */
    public function getTmParams()
    {
        return [
            'title' => $this->pageConfig->getTitle()->get(),
            'description' => mb_substr($this->pageConfig->getDescription(), 0, 200, 'UTF-8'),
        ];

    }

    /**
     * Preparing global layout
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->pageConfig->addRemotePageAsset(
            $this->_helper->getCanonicalUrl(),
            'canonical',
            ['attributes' => ['rel' => 'canonical']],
            self::DEFAULT_ASSET_NAME
        );

        return parent::_prepareLayout();
    }

}
