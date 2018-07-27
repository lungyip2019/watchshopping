<?php

namespace Zemez\Amp\Model\Plugin\Magento\Catalog\Block\Product;

use Zemez\Amp\Helper\Data as DataHelper;
use Zemez\Amp\Block\Review\Product\ReviewRenderer as ReviewRenderer;

/**
 * Plugin for
 */
class ViewPlugin
{
    /**
     * @var \Zemez\Amp\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @var ReviewRendererInterface
     */
    protected $_reviewRenderer;

    /**
     * @param DataHelper $dataHelper
     * @param ReviewRenderer $reviewRenderer
     */
    public function __construct(
        DataHelper $dataHelper,
        ReviewRenderer $reviewRenderer
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_reviewRenderer = $reviewRenderer;
    }

    /**
     * Overriding review summary output
     *
     * @param  \Magento\Catalog\Block\Product\View $subject
     * @param  string $result
     * @param \Magento\Catalog\Model\Product $product
     * @param bool $templateType
     * @param bool $displayIfNoReviews
     * @return string
     */
    public function aroundGetReviewsSummaryHtml(
        \Magento\Catalog\Block\Product\View $subject,
        \Closure $proceed,
        \Magento\Catalog\Model\Product $product,
        $templateType = false,
        $displayIfNoReviews = false
    ) {
        /**
         * (Only for amp request)
         */
        if ($this->_dataHelper->isAmpCall()) {
            return $this->_reviewRenderer->getReviewsSummaryHtml(
                $product,
                $templateType,
                $displayIfNoReviews
            );
        }

        /**
        * Get result by original method
        */
        return $proceed($product, $templateType, $displayIfNoReviews);
    }

}
