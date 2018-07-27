<?php
namespace TemplateMonster\Blog\Block\Sidebar;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use TemplateMonster\Blog\Model\ResourceModel\Comment\CollectionFactory as CommentCollectionFactory;
use TemplateMonster\Blog\Model\Url;
use TemplateMonster\Blog\Helper\Data as HelperData;

class Comments extends Template
{
    protected $_registry;

    protected $_urlModel;

    protected $_helper;

    protected $_commentCollection;

    protected $_commentCollectionFactory;

    public function __construct(
        Registry $registry,
        HelperData $helper,
        Template\Context $context,
        Url $url,
        CommentCollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_urlModel = $url;
        $this->_helper = $helper;
        $this->_registry = $registry;
        $this->_commentCollectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        $this->_prepareCollection();

        return parent::_prepareLayout();
    }

    protected function _prepareCollection()
    {
        $this->_commentCollection = $this->_commentCollectionFactory->create()
            ->setPageSize($this->getCommentsLimit())
            ->setCurPage(1)
            ->addFieldToFilter('status', 1)
            ->setOrder('creation_time','DESC')
            ->joinPostTable();
    }

    public function getComments()
    {
        return $this->_commentCollection;
    }

    public function getPostUrl($post)
    {
        return $this->getUrl($this->_urlModel->getPostRoute($post));
    }

    public function getCommentsLimit()
    {
        return $this->_helper->getSidebarCommentsNumber();
    }
}
