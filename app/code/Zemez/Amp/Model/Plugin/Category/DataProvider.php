<?php

namespace Zemez\Amp\Model\Plugin\Category;

class DataProvider
{
    /**
     * Store manager
     * @var \Magento\Store\Model\StoreManagerInterface
     */
	protected $storeManager;

    /**
     * Constructor
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
	public function __construct(
		\Magento\Store\Model\StoreManagerInterface $storeManager
	) {
		$this->storeManager = $storeManager;
	}

    /**
     * After getting category data
     * @param  \Magento\Catalog\Model\Category\DataProvider
     * @param  array
     * @return array
     */
	public function afterGetData(\Magento\Catalog\Model\Category\DataProvider $subject, $result)
    {
        $category = $subject->getCurrentCategory();

        if ($category) {
        	$categoryData = $result[$category->getId()];
            if (isset($categoryData['amp_homepage_image'])) {
                unset($categoryData['amp_homepage_image']);
                $categoryData['amp_homepage_image'][0]['name'] = $category->getData('amp_homepage_image');
                $categoryData['amp_homepage_image'][0]['url'] = $this->getImageUrl($category->getData('amp_homepage_image'));
            }
            $result[$category->getId()] = $categoryData;
        }
        return $result;
    }

    /**
     * Retrieve image url
     * @param  string $image
     * @return string
     */
    protected function getImageUrl($image)
    {
        if (is_array($image) && isset($image[0]['url'])) {
            return $image[0]['url'];
        }
    	return $this->storeManager->getStore()->getBaseUrl(
	            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
	        ) . 'catalog/category/' . $image;
    }
}