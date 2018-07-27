<?php

namespace TemplateMonster\Blog\Block\Widget;

use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\View\Element\Template\Context;
use TemplateMonster\Blog\Model\ResourceModel\Post\Collection;
use TemplateMonster\Blog\Model\Url;

use TemplateMonster\Blog\Helper\Data as HelperData;

class PostList extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var FilterProvider
     */
    protected $_filterProvider;

    /**
     * @var Collection
     */
    protected $_postCollection;

    /**
     * @var Url
     */
    protected $_urlModel;

    /**
     * @var HelperData
     */
    protected $_helper;

    /**
     * @var null|\Magento\Framework\Filter\Truncate
     */
    private $_truncateFilter = null;

    /**
     * PostList constructor.
     *
     * @param ObjectManagerInterface $objectManager
     * @param Context                $context
     * @param FilterProvider         $filterProvider
     * @param Collection             $postCollection
     * @param Url                    $url
     * @param HelperData             $helper
     * @param array                  $data
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        Context $context,
        FilterProvider $filterProvider,
        Collection $postCollection,
        Url $url,
        HelperData $helper,
        array $data = []
    ) {
        $this->_objectManager = $objectManager;
        $this->_urlModel = $url;
        $this->_helper = $helper;
        $this->_filterProvider = $filterProvider;
        $this->_postCollection = $postCollection;
        parent::__construct($context, $data);

    }

    public function getPosts()
    {
        $postAmount = (int) $this->getData('post_amount');
        $this->_postCollection
            ->addFieldToFilter('is_visible', 1)
            ->addStoreFilter($this->_storeManager->getStore()->getId());
        $this->_postCollection->getSelect()->order('creation_time desc')->limit($postAmount);
        return $this->_postCollection;
    }

    public function filterContent($data)
    {
        return $this->_filterProvider->getBlockFilter()->filter($data);
    }

    public function getPostUrl($post)
    {
        return $this->getUrl($this->_urlModel->getPostRoute($post));
    }

    public function getDateFormat()
    {
        return $this->_helper->getDataFormat();
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
       return $this->getData('title');
    }

    /**
     * Check is enable carousel.
     *
     * @return bool
     */
    public function isEnabledCarousel()
    {
        return (bool) $this->getData('is_enable_carousel');
    }


    /**
     * Get post amount per row.
     *
     * @return int
     */
    public function getPostAmountPerRow()
    {
        return (int) $this->getData('post_amount_per_row');
    }

    /**
     * Get post amount per view.
     *
     * @return int
     */
    public function getPostAmountPerView()
    {
        return (int) $this->getData('post_amount_per_view');
    }

    /**
     * Get AMP carousel height.
     *
     * @return int
     */
    public function getAmpCarouselHeight()
    {
        return (int) $this->getData('post_carousel_height');
    }
    
    /**
     * Get post image height.
     *
     * @return int
     */
    public function getPostImageHeight()
    {
        return (int) $this->getData('post_img_height');
    }
    
    /**
     * Get post image width.
     *
     * @return int
     */
    public function getPostImageWidth()
    {
        return (int) $this->getData('post_img_width');
    }
    /**
     * Get truncated Description.
     *
     * @param $string
     * @param $length
     *
     * @return string
     */
    public function getStringTruncated($string, $length)
    {
        if(!empty($length)){
            $string = $this->_getTruncatedFilter($length)->filter($string);
        }
        return $string;
    }

    /**
     * Get truncated filter.
     *
     * @param $text
     *
     * @return \Magento\Framework\Filter\Truncate
     */
    protected function _getTruncatedFilter($text)
    {
        return $this->_objectManager->create(
            'Magento\Framework\Filter\Truncate',
            ['length' => $text]
        );
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function toHtml()
    {
        return $this->getWidgetStatus() ? parent::toHtml() : '';
    }
}
