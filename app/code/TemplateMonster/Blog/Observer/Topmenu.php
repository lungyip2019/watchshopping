<?php
namespace TemplateMonster\Blog\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use TemplateMonster\Blog\Helper\Data as HelperData;
use Magento\Framework\Data\Tree\Node;
use \Magento\Framework\UrlInterface;

class Topmenu implements ObserverInterface
{
    /**
     * @var HelperData
     */
    protected $_helper;

    /**
     * @var UrlInterface
     */
    protected $_urlBuilder;

    /**
     * Topmenu constructor.
     *
     * @param HelperData   $helper
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        HelperData $helper,
        UrlInterface $urlBuilder
    ) {
        $this->_helper = $helper;
        $this->_urlBuilder = $urlBuilder;
    }

    public function execute(EventObserver $observer)
    {
        if (!($this->_helper->isModuleActive() && $this->_helper->isAllowMenu())) {
            return false;
        }

        $menu = $observer->getMenu();
        $tree = $menu->getTree();

        $data = [
            //Menu title is taken from Title general option of TemplateMonster Blog config
            'name'  => __($this->_helper->getTitle()),
            'id'    => 'tmblog',
            'url'   => $this->_urlBuilder->getUrl($this->_helper->getRoute()),
            'is_active' => ''
        ];

        $node = new Node($data, 'tmblog', $tree, $menu);
        $menu->addChild($node);

        return $this;
    }
}
