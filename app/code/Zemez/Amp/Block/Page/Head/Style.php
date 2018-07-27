<?php

namespace Zemez\Amp\Block\Page\Head;

class Style extends \Magento\Framework\View\Element\Template
{
    /**
     * @param Magento\Framework\View\Element\Template\Context $context
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
     * Retrieve view url without cdn url
     * @param  string $file
     * @param  array  $params
     * @return string
     */
    public function getAmpSkinUrl($file = null, array $params = array())
    {
        $url = $this->getViewFileUrl($file, $params);
        $fontInfo = parse_url($url);
        $baseInfo = parse_url($this->getUrl());
        $url = str_replace($fontInfo['host'], $baseInfo['host'], $url);

        return $url;
    }

    /**
     * Retrieve minified css
     * @return string
     */
    protected function _toHtml()
    {
        $html = parent::_toHtml();

        if ($html) {
            $html = str_replace(
                array(' {', "}\n"),
                array('{', '}'),
                $html
            );
        }

        return $html;
    }

    /**
     * Get max-width and max-height for category image
     * @return array
     */
    public function getCategoryImageSize()
    {
        $categoryImageBlock = $this->getLayout()->getBlock(' category.image');

        return [
            'max-width' => $categoryImageBlock ? $categoryImageBlock->getWidth() : 'none',
            'max-height' => $categoryImageBlock ? $categoryImageBlock->getHeight() : 'none',
        ];
    }
}