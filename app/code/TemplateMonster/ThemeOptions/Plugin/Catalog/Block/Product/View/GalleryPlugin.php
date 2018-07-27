<?php

namespace TemplateMonster\ThemeOptions\Plugin\Catalog\Block\Product\View;

use \Magento\Catalog\Block\Product\View\Gallery;
use \TemplateMonster\ThemeOptions\Helper\Data;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Json\DecoderInterface;

/**
 * Config edit plugin.
 *
 * @package TemplateMonster\ThemeOptions\Plugin\Catalog\Block\Product\View
 */
class GalleryPlugin
{
    /**
     * ThemeOptions helper.
     *
     * @var helper
     */
    protected $_helper;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var \Magento\Framework\Json\DecoderInterface
     */
    protected $jsonDecoder;

    /**
     * GalleryPlugin constructor.
     * @param Data $helper
     * @param EncoderInterface $jsonEncoder
     * @param DecoderInterface $jsonDecoder
     */

    public function __construct(
        Data $helper,
        EncoderInterface $jsonEncoder,
        DecoderInterface $jsonDecoder
    ) {
        $this->_helper = $helper;
        $this->jsonEncoder = $jsonEncoder;
        $this->jsonDecoder = $jsonDecoder;
    }

    /**
     * @param Gallery $subject
     * @param callable $proceed
     * @return string
     */
    public function aroundGetMagnifier(
        Gallery $subject,
        callable $proceed)
    {
        $magnifierArray = $this->jsonDecoder->decode($proceed());
        $magnifierArray["enabled"] = (bool) $this->_helper->isImageZoom();

        return $this->jsonEncoder->encode($magnifierArray);
    }

}