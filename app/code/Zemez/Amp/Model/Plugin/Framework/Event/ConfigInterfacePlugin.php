<?php

namespace Zemez\Amp\Model\Plugin\Framework\Event;

use Magento\Framework\Event\ConfigInterface as ConfigInterface;

/**
 * Plugin for processing builtin cache
 */
class ConfigInterfacePlugin
{
    /**
     * List of observers that need to be disabled
     * @var array
     */
    protected $_disabledObservers;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
        $this->_disabledObservers = [];
    }

    /**
     * Check of observer instance by list of disabled observers
     * @param  string $instance
     * @return boolean
     */
    protected function _isAllowedObserver($instance)
    {
        if ($instance) {
            return !in_array($instance, $this->_disabledObservers);
        }

        return true;
    }

    /**
     * Add amp parameter for each url
     * @param  ConfigInterface $subject
     * @param  array $result
     * @return array
     */
    public function afterGetObservers(ConfigInterface $subject, $result)
    {
        if (PHP_SAPI === 'cli' || !count($result)) {
            return $result;
        }

        //Need to use object manager to omit issues during setup:static-content:deploy
        $dataHelper = $this->objectManager->get('\Zemez\Amp\Helper\Data'); 

        if ($dataHelper->isAmpCall()){
            foreach ($result as $key => $item) {
                if (isset($item['instance']) && !$this->_isAllowedObserver($item['instance'])) {
                    $result[$key]['disabled'] = true;
                }
            }
        }

        return $result;
    }

}
