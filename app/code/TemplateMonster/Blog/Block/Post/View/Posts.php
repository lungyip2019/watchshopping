<?php
namespace TemplateMonster\Blog\Block\Post\View;

use TemplateMonster\Blog\Block\Post\View;

class Posts extends View
{

    protected $_itemCollection;

    public function getItems()
    {
        return $this->_itemCollection;
    }

    protected function _beforeToHtml()
    {
        $this->_prepareData();
        return parent::_beforeToHtml();
    }

    protected function _prepareData()
    {
        if ($this->_helper->isRelatedPostsEnabled() && ($numberOfPosts = $this->_helper->getRelatedPostsNumber())) {
            $post = $this->getPost();
            $this->_itemCollection = $post->getRelatedPosts();
            $this->_itemCollection->addFieldToFilter('is_visible', 1);
            $this->_itemCollection->getSelect()->order('position', 'ASC')->limit($numberOfPosts);
        }
        return $this;
    }

    public function getPostUrl($post)
    {
        return $this->getUrl($this->_urlModel->getPostRoute($post));
    }

    public function getRelatedPostsLayoutView()
    {
        return $this->_helper->getRelatedPostsLayoutView();
    }

    public function getRelatedPostsNumberPerView()
    {
        return $this->_helper->getRelatedPostsNumberPerView();
    }
}
