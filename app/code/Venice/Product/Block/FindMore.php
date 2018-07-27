<?php

namespace Venice\Product\Block;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\ResourceModel\Eav;

class FindMore extends Template
{
    protected $_registry;
    protected $_categoryFactory;
    protected $_attribute;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute,
        array $data = []
    )
    {
        $this->_registry = $registry;
        $this->_categoryFactory = $categoryFactory;
        $this->_attribute = $attribute;
        parent::__construct($context, $data);
    }


    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    public function getCategoryName($categoryId)
    {
        $category = $this->_categoryFactory->create()->load($categoryId);
        $categoryParent = $category->getParentCategory();
        $levelParent = $categoryParent->getLevel();
        try {
            while ($categoryParent->getParentCategory()->getParentCategory()->getLevel() !== "0") {
                $categoryParent = $categoryParent->getParentCategory();
                $levelParent = $categoryParent->getLevel();
            }
        } catch (\Exception $exception){

        }

        $categoryParentName = $categoryParent->getName();
        if ($levelParent === "1" || $levelParent === "0"){
            $categoryParentName = "";
        }
        $childName = $category->getName();

        $link = "<a href=".$category->getUrl().">".$categoryParentName." ".$childName."</a>";
        return $link;
    }

}
