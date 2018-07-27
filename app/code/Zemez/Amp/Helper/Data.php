<?php

namespace Zemez\Amp\Helper;

class Data extends Main
{
    /**
     * Default constants values for module
     */
    const MODULE_LOG_PREFIX = 'Zemez_Amp::';
    const AMP_HOME_PAGE_KEYWORD = 'amp_homepage';
    const AMP_FOOTER_LINKS_KEYWORD = 'amp_footer_links';
    const AMP_ONLY_OPTIONS_KEYWORD = 'only-options';
    const AMP_ROOT_TEMPLATE_NAME_1COLUMN = '1column_amp';
    const AMP_ROOT_TEMPLATE_NAME_OPTIONS = '1column-options';
    const DEFAULT_ACCESS_CONTROL_ORIGIN = 'cdn.ampproject.org';
    const AMP_DEFAULT_IFRAME_PATH = 'ampiframe.php';

    /**
     * Checking request
     * @var bool
     */
    protected $_isAmpCall;

    /**
     * [$_ignorePath description]
     * @var string
     */
    protected $_ignorePath;

    /**
     * Use for getBaseUrl
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Used in Processing HTTP-Headers for cross domain requests
     * @var \Magento\Framework\App\Response\Http
     */
    protected $response;

    /**
     * Form Key for getAddToCart method
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $formKey;

    /**
     * @var \Magento\Config\Model\Config
     */
    protected $config;

    /**
     * needed for disable modules
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var array
     */
    protected $_allowedPages;

    /**
     * @var string
     */
    protected $_configSectionId = 'tm_amp';


    /**
     * Helper Construct
     * @param \Magento\Store\Model\StoreManagerInterface
     * @param \Magento\Framework\ObjectManagerInterface
     * @param \Magento\Framework\App\Response\Http
     * @param \Magento\Framework\Data\Form\FormKey
     * @param \Magento\Framework\App\Helper\Context
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Response\Http $response,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Config\Model\Config $config,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    ) {
        $this->storeManager         = $storeManager;
        $this->response             = $response;
        $this->formKey              = $formKey;
        $this->resourceConnection   = $resourceConnection;
        $this->config               = $config;
        parent::__construct($objectManager, $context);
    }

    /**
     * Retrieve allowed full action names
     * @param  int $store
     * @return array
     */
    public function getAllowedPages($store = null)
    {
        if ($this->_allowedPages === null) {
            $this->_allowedPages = explode(',', $this->getConfig($this->_configSectionId . '/general/pages', $store));

            $this->_allowedPages[] = 'turpentine_esi_getBlock';
        }

        return $this->_allowedPages;
    }

    /**
     * Is current page allowed
     * @return boolean
     */
    public function isAllowedPage()
    {
        return in_array($this->getFullActionName(), $this->getAllowedPages());
    }

    /**
     * Get full name of action
     * @return string
     */
    public function getFullActionName()
    {
        if (!$this->_request) {
            return '__';
        }

        return $this->_request->getFullActionName();
    }

    /**
     * Is module enabled
     * @param  int $store
     * @return boolean
     */
    public function extEnabled($store = null)
    {
        return (bool)$this->getConfig($this->_configSectionId . '/general/enabled', $store);
    }


    /**
     * Is AMP the current request
     * @return bool
     */
    public function isAmpCall()
    {
        if ($this->_isAmpCall === null) {
            if (!$this->extEnabled()) {
                return $this->_isAmpCall = false;
            }

            if (!$this->isAllowedPage()) {
                if ($this->getFullActionName() == '__') {
                    return false;
                } else {
                    return $this->_isAmpCall = false;
                }
            }

            if ($this->_request->getParam(self::AMP_ONLY_OPTIONS_KEYWORD) == 1) {
                return $this->_isAmpCall = false;
            }

            if ($this->_request->getParam('canonical') == 1) {
                return false;
            }

            if ($this->_request->getParam('amp') == 1) {
                return $this->_isAmpCall = true;
            }

        }
        return $this->_isAmpCall;
    }

    public function setAmpRequest($value)
    {
        $this->_isAmpCall = (bool)$value;
        return $this;
    }

    /**
     * @return bool
     * Return true if module enabled and exist request param only-options
     */
    public function isOnlyOptReq()
    {
        return $this->extEnabled()
            && ($this->_request->getParam('only-options') == 1)
            && ($this->getFullActionName() == 'catalog_product_view');
    }

    /**
     * @param  string $url
     * @return array $urlData
     */
    protected function _parseUrl($url)
    {
        $url = filter_var($url, FILTER_VALIDATE_URL);
        $url = $url ? $url : $this->_urlBuilder->getCurrentUrl();
        $urlData = parse_url($url);

        if (isset($urlData['query'])) {
            parse_str($urlData['query'], $dataQuery);
            $urlData['query'] = $dataQuery;
        } else {
            $urlData['query'] = [];
        }

        $urlData['fragment'] = isset($urlData['fragment']) ? $urlData['fragment'] : '';

        return $urlData;
    }

    /**
     * @param  array $urlData
     * @param  array $params
     * @return array $urlData
     */
    protected function _mergeUrlParams($urlData, $params)
    {
        if (is_array($params) && count($params)) {
            if (isset($params['_secure'])) {
                $urlData['_secure'] = (bool)$params['_secure'];
                unset($params['_secure']);
            }

            $urlData['query'] = array_merge($urlData['query'], $params);
        }

        return $urlData;
    }

    /**
     * Retrieve port component from URL data
     * @param  array $urlData
     * @return string
     */
    protected function _getPort($urlData)
    {
        return !empty($urlData['port']) ? (':' . $urlData['port']) : '';
    }

    /**
     * String location without amp parameter
     * @return string
     */
    public function getCanonicalUrl($url = null, $params = null)
    {
        $urlData = $this->_mergeUrlParams($this->_parseUrl($url), $params);

        if (isset($urlData['query']['amp'])) {
            unset($urlData['query']['amp']);
        }

        if (isset($urlData['_secure'])) {
            $urlData['scheme'] = 'https';
        }

        $paramsStr = count($urlData['query'])
            ? '?' . urldecode(http_build_query($urlData['query']))
            : '';

        if (!empty($urlData['fragment'])) {
            $paramsStr .= '#' . $urlData['fragment'];
        }

        return $urlData['scheme'] . '://' . $urlData['host'] . $this->_getPort($urlData) . $urlData['path'] . $paramsStr;
    }

    /**
     * String location with amp parameter
     * @return string
     */
    public function getAmpUrl($url = null, $params = null)
    {
        $urlData = $this->_mergeUrlParams($this->_parseUrl($url), $params);

        if (!isset($urlData['query']['amp'])) {
            $urlData['query'] = array_merge(['amp' => 1], $urlData['query']);
        }

        if (isset($urlData['_secure'])) {
            $urlData['scheme'] = 'https';
        }

        $paramsStr = count($urlData['query'])
            ? '?' . urldecode(http_build_query($urlData['query']/*, null, '&amp'*/))
            : '';

        if (!empty($urlData['fragment'])) {
            $paramsStr .= '#' . $urlData['fragment'];
        }

        return $urlData['scheme'] . '://' . $urlData['host'] . $this->_getPort($urlData) . $urlData['path'] . $paramsStr;
    }

    /**
     * Retrieve add to cart url by product
     * @param  \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getAddToCartUrl($product)
    {
        if ($product && $productId = $product->getId()) {
            $addToCartUrl = $this->_urlBuilder->getUrl(
                    $this->_configSectionId . '/cart/add', [
                        'product' => $product->getId(),
                        'form_key'=>$this->formKey->getFormKey(),
                        '_secure'=>true
                    ]
                );

            return $this->getCanonicalUrl($addToCartUrl);
        }

        return '#';
    }

    /**
     * @var object \Magento\Catalog\Model\Product
     * @var string $store
     * @return string add to cart url
     */
    public function getIframeSrc($product, $store = null)
    {
        $secure = $this->getConfig('web/secure/use_in_frontend', $store);
        $ampIframePath = $this->getAmpIframePath();

        if ($secure && $ampIframePath && ($productUrl = $this->getOnlyOptionsUrl($product))) {
            $ampIframeUrlData = parse_url($productUrl);
            $prefix = 'www.';
            $ampIframeUrlData['host'] = (strpos($ampIframeUrlData['host'], $prefix) === 0)
                ? substr($ampIframeUrlData['host'], strlen($prefix))
                : $prefix . $ampIframeUrlData['host'];

            return 'https://' . $ampIframeUrlData['host'] . $this->_getPort($ampIframeUrlData) . '/' . $ampIframePath . '?referrer=' . base64_encode($productUrl);
        }

        return false;
    }

    /**
     * Retrieve url for review product post action
     * @param  \Magento\Catalog\Model\Product $product
     * @return string secure url
     */
    public function getActionForReviewForm($productId)
    {
        return $this->_urlBuilder->getUrl(
            'tm_amp/review_product/post',
            [
                '_secure' => true,
                'id' => $productId,
            ]
        );
    }

    /**
     * Retrieve base URL for current store
     * @return string URL Link
     */
    public function getBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }

    /**
     * @deprecated No longer used by internal code and not recommended.
     * @deprecated Please use sanitizeHttpHeaders for prepare HTTP headers
     * @var void
     * @return null
     */
    public function removeSameOrigin()
    {
        return null;
    }

    /**
     * Processing HTTP-Headers for cross domain requests
     * Setting additional headers for same-origin and cross-origin requests
     * according to https://github.com/ampproject/amphtml/blob/master/spec/amp-cors-requests.md
     * @return void
     */
    public function sanitizeHttpHeaders()
    {
        $sourceOrigin = $this->_request->getParam('__amp_source_origin');

        if (!$sourceOrigin) {
            $urlData  = parse_url($this->getBaseUrl());

            if (!empty($urlData['scheme']) && !empty($urlData['host'])) {
                $sourceOrigin = $urlData['scheme'] . '://' . $urlData['host'] . $this->_getPort($urlData);
            }
        }

        $this->response->setHeader(
                'Access-Control-Allow-Origin',
                $this->getAccessControlOrigin(),
                true)
            ->setHeader(
                'AMP-Access-Control-Allow-Source-Origin',
                $sourceOrigin,
                true)
            ->setHeader('Access-Control-Expose-Headers', 'AMP-Access-Control-Allow-Source-Origin', true)
            ->setHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS', true)
            ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token', true)
            ->setHeader('Access-Control-Allow-Credentials', 'true', true);
    }

    /**
     * @param  \Magento\Catalog\Model\Product
     * @return string|bool
     */
    public function getOnlyOptionsUrl($product)
    {
        if ($product) {
            $productUrl = (!$product->getProductUrl())
            ? $this->_urlBuilder->getUrl('catalog/product/view', ['id' => $product->getId()])
            : $product->getProductUrl();

            return $this->getCanonicalUrl($productUrl, [self::AMP_ONLY_OPTIONS_KEYWORD => 1, '_secure'=>true]);
        }

        return false;
    }

    /**
     * @param  string $url
     * @return string
     */
    public function getFormReturnUrl($url = null)
    {
        $params = ['_secure'=>true];

        if (!$this->_request->getParam(self::AMP_ONLY_OPTIONS_KEYWORD)) {
            $params[self::AMP_ONLY_OPTIONS_KEYWORD] = 1;
        }

        return $this->getCanonicalUrl($url, $params);
    }

    /**
     * Retrieve source origin for current  page publisher
     * @return string
     */
    public function getAccessControlOrigin()
    {
        /**
         * Base way to detecting
         * Detecting source origin by server variable HTTP_ORIGIN
         */
        if ($this->_request) {
            $httpOrigin = $this->_request->getServer('HTTP_ORIGIN');

            if ($httpOrigin) {
                return $httpOrigin;
            }
        }

        /**
         * Alternative way to detecting
         * Detecting source origin by magento base url
         */
        if ($baseUrl = $this->getBaseUrl()) {
            $urlData = parse_url($baseUrl);
            if (!empty($urlData['host'])) {
                return ('https://' . str_replace('.', '-', $urlData['host']) . '.' . self::DEFAULT_ACCESS_CONTROL_ORIGIN);
            }
        }

        /**
         * Return source origin by default
         */
        return 'https://' . self::DEFAULT_ACCESS_CONTROL_ORIGIN;
    }

    public function isSecure()
    {
        return $this->_request->isSecure();
    }

    /**
     * Retrieve logo src attribute
     * @param  string $store
     * @return int
     */
    public function getLogoSrc($store = null)
    {
        $logo = $this->getConfig($this->_configSectionId . '/logo/image', $store);
        $mediaurl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        if ($logo) {
            $logopath = $mediaurl;
           $logo = $logopath . 'theme_options/logo/' . $logo;

        }

        return (string)$logo;
    }

    public function getLogoImageWidth($store = null) {
        $logoImageWidth = $this->getConfig($this->_configSectionId . '/logo/logo_image_width', $store);

        return (int)$logoImageWidth;
    }

    public function getLogoImageHeight($store = null) {
        $getLogoImageHeight = $this->getConfig($this->_configSectionId . '/logo/logo_image_height', $store);
        
        return (int)$getLogoImageHeight;
    }

    /**
     * Retrieve amp-iframe path
     * @param  string $store
     * @return string
     */
    public function getAmpIframePath($store = null)
    {
        if ($this->getConfig($this->_configSectionId . '/general/amp_option_iframe', $store)) {
            $path = (string)$this->getConfig($this->_configSectionId . '/general/amp_iframe_path', $store);

            return $path ? trim($path, '/') : self::AMP_DEFAULT_IFRAME_PATH;
        }

        return false;
    }


    /**
     * Retrieve design setting
     * @param  string $store
     * @return string
     */
    public function getGoogleAnalytics($store = null)
    {
        return  (string)$this->getConfig(\Magento\GoogleAnalytics\Helper\Data::XML_PATH_ACCOUNT, $store);
    }
    /**
     * Retrieve Google Tag Snippet setting
     * @param  string $store
     * @return string
     */
    public function getGoogleTagCode($store = null)
    {
        return  (string)$this->getConfig($this->_configSectionId . '/tag_manager/tag_manager_snippet', $store);
    }

}
