<?php

namespace Venice\Product\Helper;


use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use Magento\Store\Model\StoreManagerInterface;

class ProductFamilyHelper extends AbstractHelper
{
    protected $_resource;

    /**
     * @var ImageFactory
     */
    protected $_imageFactory;

    /**
     * @var FileSystem
     */
    protected $_filesystem;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;



    public function __construct(
        Context $context,
        AdapterFactory $imageFactory,
        Filesystem $filesystem,
        StoreManagerInterface $storeManager
    )
    {
        $this->_imageFactory = $imageFactory;
        $this->_filesystem = $filesystem;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }


    /**
     * Get resize image url .
     *
     * @return string
     */
    public function resizeImageUrl($image,$type,$width=null,$height=null)
    {


        if ($type == 'family-photo') {
            $path = 'brandcollection/brandcollection/';
        }else{
            return '';
        }

        if(empty($image) && $type == 'family-photo'){
            return $this ->_storeManager-> getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$path.'NoImages.jpg';
        }

        if(empty($image)) {
            return '';
        }

        $absolutePath = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath($path).$image;
        // check if width and height is specified, if specified, create a new image base on the $width,$height specified
        if(isset($width) && isset($height)){
            $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath($path . 'resized/'.$width.'/').$image;
            //create image factory...
            $imageResize = $this->_imageFactory->create();
            $imageResize->open($absolutePath);
            $imageResize->constrainOnly(TRUE);
            $imageResize->keepTransparency(TRUE);
            $imageResize->keepFrame(FALSE);
            $imageResize->keepAspectRatio(TRUE);
            $imageResize->resize($width,$height);
            //destination folder
            $destination = $imageResized ;
            //save image
            $imageResize->save($destination);
            $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$path.'resized/'.$width.'/'.$image;
            return $resizedURL;
        }else{
            return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$path.$image;
        }



    }

}