<?php

namespace Zemez\Amp\Block\Review\Product;

class ReviewRenderer extends \Magento\Review\Block\Product\ReviewRenderer
{

    protected $_availableTemplates = [
        self::SHORT_VIEW => 'Zemez_Amp::review/helper/summary_short.phtml',
        self::FULL_VIEW => 'Zemez_Amp::review/helper/summary.phtml',
    ];

}
