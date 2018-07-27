<?php
namespace TemplateMonster\Blog\Block\Post;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Store\Model\ScopeInterface;
use Magento\Cms\Model\Template\FilterProvider;
use TemplateMonster\Blog\Model\Url;
use TemplateMonster\Blog\Helper\Data as HelperData;

class View extends Template
{
    protected $_registry;

    protected $_post;

    protected $_urlModel;

    protected $_helper;

    protected $_filterProvider;

    public function __construct(
        Registry $registry,
        HelperData $helper,
        FilterProvider $filterProvider,
        Template\Context $context,
        Url $url,
        array $data = []
    ) {
        $this->_urlModel = $url;
        $this->_helper = $helper;
        $this->_registry = $registry;
        $this->_filterProvider = $filterProvider;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        $post = $this->getPost();
        $this->_addBreadcrumbs($post);
        $this->pageConfig->addBodyClass('blog-post-' . $post->getIdentifier());
        $this->pageConfig->getTitle()->set($post->getTitle());
        $this->pageConfig->setKeywords($post->getMetaKeywords());
        $this->pageConfig->setDescription($post->getMetaDescription());

        return parent::_prepareLayout();
    }

    protected function _addBreadcrumbs($post)
    {
        if ($this->_scopeConfig->getValue('web/default/show_cms_breadcrumbs', ScopeInterface::SCOPE_STORE)
            && ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs'))) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            );
            $breadcrumbsBlock->addCrumb(
                'tm_blog',
                [
                    'label' => __($this->_helper->getTitle()),
                    'title' => __($this->_helper->getTitle()),
                    'link' => $this->getUrl($this->_helper->getRoute())
                ]
            );
            if ($category = $post->getOneCategory()) {
                if ($category->getId()) {
                    $breadcrumbsBlock->addCrumb(
                        'tm_category',
                        [
                            'label' => __($category->getName()),
                            'title' => __($category->getName())
                            //'link' => $this->getUrl($this->_helper->getRoute())
                        ]
                    );
                }
            }
            $breadcrumbsBlock->addCrumb(
                'tm_blog_post',
                [
                    'label' => __($post->getTitle()),
                    'title' => __($post->getTitle())
                ]
            );
        }
    }

    public function getPost()
    {
        if (!$this->_post) {
            $this->_post = $this->_registry->registry('tm_blog_post');
        }
        return $this->_post;
    }

    public function filterContent($data)
    {
        return $this->_filterProvider->getBlockFilter()->filter($data);
    }

    /*public function getDate($creationTime, $format)
    {
        return date($format, time($creationTime));
    }*/

    public function getDateFormat()
    {
        return $this->_helper->getDataFormat();
    }
}
