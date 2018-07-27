<?php

namespace Zemez\Amp\Observer\Category\Adminhtml;


class CategorySaveBefore extends CategoryObserver
{

    /**
     * Save category AMP home page image
     * @param  \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (class_exists('Magento\Catalog\Model\ImageUploader')) {

            $eventImage = $this->request->getParam('amp_homepage_image');
            $category = $observer->getEvent()->getCategory();
            if ($eventImage && is_array($eventImage) && isset($eventImage[0]['tmp_name'])) {
                $category->setData('amp_homepage_image', $eventImage[0]['name']);
                $this->imageUploader->moveFileFromTmp($eventImage[0]['name']);
            } elseif (empty($eventImage)) {
                $category->setData('amp_homepage_image', '');
            }
        }
    }
}
