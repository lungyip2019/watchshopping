<?php
namespace TemplateMonster\Blog\Controller;

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\RequestInterface;
use \Magento\Framework\Registry;
use TemplateMonster\Blog\Helper\Data;
use TemplateMonster\Blog\Model\Url;
use TemplateMonster\Blog\Model\PostFactory;
use TemplateMonster\Blog\Model\CategoryFactory;

class Router implements \Magento\Framework\App\RouterInterface
{
    const MODULE_PATH = 'tm_blog';

    protected $_actionFactory;

    protected $_postFactory;

    protected $_categoryFactory;

    protected $_helper;

    protected $_registry;

    protected $_urlModel;

    public function __construct(
        ActionFactory $actionFactory,
        Data $helper,
        PostFactory $postFactory,
        CategoryFactory $categoryFactory,
        Registry $registry,
        Url $url
    ) {
        $this->_postFactory = $postFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->_actionFactory = $actionFactory;
        $this->_helper = $helper;
        $this->_registry = $registry;
    }

    /**
     * Allows to use dynamic routing
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface|null
     */
    public function match(
        RequestInterface $request
    ) {
        $info = trim($request->getPathInfo(), '/');
        $pathInfo = explode('/', $info);
        $route = $this->_helper->getRoute();

        if (!$this->_helper->isModuleActive()) {
            return null;
        }

        if ($pathInfo[0] == $route) {
            $modulePath = self::MODULE_PATH;
            if (isset($pathInfo[1])) {
                if ($pathInfo[1] == 'saveComment') {
                    $modulePath .= '/post/saveComment';
                } elseif ($pathInfo[1]) {
                    $post = $this->_postFactory->create()->load($pathInfo[1]);
                    if ($post->getId() && $post->getIsVisible()) {
                        $this->_registry->register('tm_blog_post', $post);
                        $modulePath .= '/post/view';
                        $request->setParam('post_identifier', $pathInfo[1]);
                    } else {
                        $category = $this->_categoryFactory->create()->load($pathInfo[1]);
                        if ($category->getId()) {
                            $this->_registry->register('tm_blog_category', $category);
                            //$modulePath .= '/post/view';
                            $request->setParam('category_identifier', $pathInfo[1]);
                        }
                    }
                }
            }
            $request->setPathInfo($modulePath);
            return $this->_actionFactory->create('Magento\Framework\App\Action\Forward');
        }
        return null;
    }
}
