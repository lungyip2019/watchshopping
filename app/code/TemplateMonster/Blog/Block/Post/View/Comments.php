<?php
namespace TemplateMonster\Blog\Block\Post\View;

use TemplateMonster\Blog\Block\Post\View;

class Comments extends View
{

    protected $_itemCollection;


    public function getItems()
    {
        return$this->_itemCollection;
    }

    protected function _beforeToHtml()
    {
        $this->_prepareData();
        return parent::_beforeToHtml();
    }

    protected function _prepareData()
    {
        $post = $this->getPost();
        $this->_itemCollection = $post->getApprovedComments()
            ->setOrder('creation_time', 'DESC');
    }

    public function getAction()
    {
        return $this->getUrl($this->_urlModel->getSaveCommentRoute());
    }

    public function isReCaptchaActive()
    {
        return $this->_helper->isReCaptchaActive();
    }

    public function getReCaptchaApi()
    {
        return$this->_helper->getReCaptchaApi();
    }

}
