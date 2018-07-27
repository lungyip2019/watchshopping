<?php
namespace Zemez\Amp\Block\Page\Html;

class Logo extends \Magento\Theme\Block\Html\Header\Logo
{
    /**
     * Retrieve logo image URL
     * @return string
     */
    protected function _getLogoUrl()
    {
        $folder = 'theme_options/logo';
        $logoPath = $this->_scopeConfig->getValue(
            'zemez/logo/image',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $path = $folder . '/' . $logoPath;

        $logoUrl = $this->_urlBuilder
                ->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $path;

        if ($logoPath !== null && $this->_isFile($path)) {
            $url = $logoUrl;
        } else {
            $url = parent::_getLogoUrl();
        }

        return $url;
    }

}