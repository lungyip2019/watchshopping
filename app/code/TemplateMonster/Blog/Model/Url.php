<?php

namespace TemplateMonster\Blog\Model;

use TemplateMonster\Blog\Helper\Data;

class Url
{

    protected $_helper;

    public function __construct(
        Data $helper
    ) {
        $this->_helper = $helper;
    }

    public function getPostRoute($post)
    {
        return $this->_helper->getRoute() . '/' . $post->getIdentifier();
    }

    public function getCategoryRoute($category)
    {
        return $this->_helper->getRoute() . '/' . $category->getIdentifier();
    }

    public function getSaveCommentRoute()
    {
        return $this->_helper->getRoute() . '/saveComment';
    }
}
