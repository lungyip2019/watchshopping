<?php
namespace TemplateMonster\NewsletterPopup\Block;

use TemplateMonster\NewsletterPopup\Helper\Data as PopupHelper;
use \Magento\Framework\View\Element\Template\Context;


class Subscribe extends \Magento\Newsletter\Block\Subscribe
{
    private $_dataHelper;

    public function __construct(
        PopupHelper $dataHelper,
        Context $context,
        $data = []
    )
    {
        $this->_dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getSubmitText()
    {
        return $this->_dataHelper->getSubmitText();
    }

    /**
     * @return string
     */
    public function getCancelText()
    {
        return $this->_dataHelper->getCancelText();
    }

}