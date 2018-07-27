<?php
namespace Zemez\Amp\Model\Plugin\Framework\View\Page;

use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Asset\GroupedCollection;
use Zemez\Amp\Block\Page\Head\Tm\AbstractTm as AbstractTm;

/**
 * Plugin for processing builtin cache
 */
class ConfigPlugin
{
    /**
     * @var \Zemez\Amp\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @param \Zemez\Amp\Helper\Data $dataHelper
     * @return  void
     */
    public function __construct(
        \Zemez\Amp\Helper\Data $dataHelper
    ) {
        $this->_dataHelper = $dataHelper;
    }

    /**
     * @param  \Magento\Framework\View\Page\Config
     * @param  \Magento\Framework\View\Asset\GroupedCollection $result
     * @return \Magento\Framework\View\Asset\GroupedCollection $result
     */
    public function afterGetAssetCollection(Config $subject, $result)
    {
        if (!$this->_dataHelper->isAmpCall()){
            return $result;
        }

        foreach ($result->getGroups() as $group) {
            $type = $group->getProperty(GroupedCollection::PROPERTY_CONTENT_TYPE);
            if (!in_array($type, ['canonical', 'ico'])) {
                foreach ($group->getAll() as $identifier => $asset) {
                    $result->remove($identifier);
                }
            }

            if ($type == 'canonical') {
                $assetsCollection = $group->getAll();

                if (!count($assetsCollection)) {
                    $subject->addRemotePageAsset(
                        $this->_dataHelper->getCanonicalUrl(),
                        'canonical',
                        ['attributes' => ['rel' => 'canonical']]
                    );
                } else {
                    foreach ($assetsCollection as $identifier => $asset) {
                        if ($identifier != AbstractTm::DEFAULT_ASSET_NAME) {
                            $result->remove($identifier);
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param  \Magento\Framework\View\Page\Config
     * @param  array $result
     * @param  string $elementType
     * @return array $result
     */
    public function aroundGetElementAttributes(Config $subject, \Closure $proceed, $elementType)
    {
        /**
         * Get result by original method
         */
        $result = $proceed($elementType);

        /**
         * Add attributes in tags by $elementType
         * (Only for amp request)
         */
        if ($this->_dataHelper->isAmpCall()) {
            switch (strtolower($elementType)) {
                case \Magento\Framework\View\Page\Config::ELEMENT_TYPE_HTML:
                    $result['amp'] = '';                                            // add 'amp' attribute to 'html' tag
                    break;
                case \Magento\Framework\View\Page\Config::ELEMENT_TYPE_BODY:
                    $result = array_diff_key($result, array_count_values(['itemtype', 'itemscope', 'itemprop']));
                    break;
                default:
                    break;
            }

        }

        return $result;
    }

}
