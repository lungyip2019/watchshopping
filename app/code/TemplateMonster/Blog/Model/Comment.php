<?php

namespace TemplateMonster\Blog\Model;

use Magento\Framework\DataObject\IdentityInterface;

class Comment  extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{
    const STATUS_APPROVED = 1;
    const STATUS_HIDDEN = 0;
    const STATUS_PENDING = -1;

    /**
     * Cache tag
     */
    const CACHE_TAG = 'blog_comment';

    /**
     * @var string
     */
    protected $_cacheTag = 'blog_comment';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'blog_comment';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('TemplateMonster\Blog\Model\ResourceModel\Comment');
    }


    /**
     * Prepare comment's statuses.
     * Available event blog_comment_get_available_statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [
            self::STATUS_APPROVED => __('Approved'),
            self::STATUS_HIDDEN => __('Hidden'),
            self::STATUS_PENDING => __('Pending')
        ];
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function validate()
    {
        $errors = [];

        if (!\Zend_Validate::is($this->getEmail(), 'NotEmpty')) {
            $errors[] = __('Please enter a email.');
        }

        if (!\Zend_Validate::is($this->getAuthor(), 'NotEmpty')) {
            $errors[] = __('Please enter a nickname.');
        }

        if (!\Zend_Validate::is($this->getContent(), 'NotEmpty')) {
            $errors[] = __('Please enter a review.');
        }

        if (empty($errors)) {
            return true;
        }
        return $errors;
    }
}
