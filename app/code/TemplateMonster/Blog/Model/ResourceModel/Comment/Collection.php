<?php
namespace TemplateMonster\Blog\Model\ResourceModel\Comment;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Blog comment collection
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'comment_id';

    /**
     * Constructor
     * Configures collection
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('TemplateMonster\Blog\Model\Comment', 'TemplateMonster\Blog\Model\ResourceModel\Comment');
    }

    public function joinPostTable()
    {
        $this->getSelect()->join(
            ['tm_blog_post' => $this->getTable('tm_blog_post')],
            'main_table.' . 'post_id' . ' = tm_blog_post.' . 'post_id',
            ['title', 'identifier']
        );
        return $this;
    }
}
