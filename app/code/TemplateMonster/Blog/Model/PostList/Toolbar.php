<?php
namespace TemplateMonster\Blog\Model\PostList;

class Toolbar
{
    const PAGE_PARAM_NAME = 'p';
    const ORDER_PARAM_NAME = 'post_list_order';
    const DIRECTION_PARAM_NAME = 'post_list_dir';
    const LIMIT_PARAM_NAME = 'product_list_limit';

    /**
     * @var
     */
    protected $request;

    /**
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->request->getParam(self::ORDER_PARAM_NAME);
    }

    /**
     * @return mixed
     */
    public function getDirection()
    {
        return $this->request->getParam(self::DIRECTION_PARAM_NAME);
    }

    /**
     * Get products per page limit
     *
     * @return string|bool
     */
    public function getLimit()
    {
        return $this->request->getParam(self::LIMIT_PARAM_NAME);
    }

    /**
     * Return current page from request
     *
     * @return int
     */
    public function getCurrentPage()
    {
        $page = (int) $this->request->getParam(self::PAGE_PARAM_NAME);
        return $page ? $page : 1;
    }
}
