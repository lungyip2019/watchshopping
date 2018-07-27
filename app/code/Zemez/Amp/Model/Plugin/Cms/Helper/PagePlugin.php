<?php

namespace Zemez\Amp\Model\Plugin\Cms\Helper;

use Magento\Framework\App\Action\Action;

class PagePlugin
{
    /**
     * @var \Zemez\Amp\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param \Zemez\Amp\Helper\Data $dataHelper
     * @return  void
     */
    public function __construct(
        \Zemez\Amp\Helper\Data $dataHelper
    ) {
        $this->dataHelper = $dataHelper;
    }

    /**
     * @param  controller
     * @param  \Magento\Framework\App\Action\Action $action
     * @param  string                               $pageId
     * @return array
     */
    public function beforePrepareResultPage($subject, $action, $pageId = null)
    {
        /**
         * Set PageId amp-homepage
         * (Only for amp request)
         */
        if ($this->dataHelper->isAmpCall() && 'cms_index_index' == $this->dataHelper->getFullActionName()) {
            $pageId = \Zemez\Amp\Helper\Data::AMP_HOME_PAGE_KEYWORD;
        }

        return [$action, $pageId];
    }

}
