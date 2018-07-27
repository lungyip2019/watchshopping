<?php

namespace Zemez\Amp\Model\Plugin\Framework;

use Magento\Framework\UrlInterface;

/**
 * Plugin for processing builtin cache
 */
class Url
{
    /**
     * @var \Zemez\Amp\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @param \Zemez\Amp\Helper\Data $dataHelper
     */
    public function __construct(
        \Zemez\Amp\Helper\Data $dataHelper
    ) {
        $this->_dataHelper = $dataHelper;
    }

    /**
     * Add amp parameter for each url
     * @param  \Magento\Framework\UrlInterface $subject
     * @param  string
     * @return string
     */
    public function afterGetUrl(UrlInterface $subject, $url)
    {
        if ($this->_dataHelper->isAmpCall()){
            return $this->_dataHelper->getAmpUrl($url);
        }

        return $url;
    }

}