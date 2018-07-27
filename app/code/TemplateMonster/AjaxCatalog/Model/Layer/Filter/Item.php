<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\AjaxCatalog\Model\Layer\Filter;

class Item extends \Magento\Catalog\Model\Layer\Filter\Item 
{

    private $request;

    public function __construct(
        \Magento\Framework\UrlInterface $url,
        \Magento\Theme\Block\Html\Pager $htmlPagerBlock,
        \Magento\Framework\App\RequestInterface $request,
        array $data = []
    ) {
        $this->request = $request;
        parent::__construct($url,$htmlPagerBlock,$data);
    }

    /**
     *
     * Overwrite default action.
     * Check if current filter already has been applied
     * Create remove url from array
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function getRemoveUrl()
    {

        $request = $this->request;
        $currentValue = $this->getValue();
        $requestValue = $this->getFilter()->getRequestVar();
	    $requestParam = $request->getParam($requestValue);
	    $params = $request->getParams();
            unset($params['p']);
            $request->setParams($params);
	    if(isset($requestParam)){
	        // make requestParam to be an array if it is not null
            $requestParam = is_array($requestParam)?$requestParam:[$requestParam];
            $currentValue = is_array($currentValue)?$currentValue:[$currentValue];
            // consider the last id of currentValue to be the current attribute id
            $currentParam = [end($currentValue)];
            // compute the difference of the request against 2 arrays, and use the difference to be remove url
            $removeValue = array_diff($requestParam,$currentParam);
            $query = [$this->getFilter()->getRequestVar() => $removeValue];
            $params['_current'] = true;
            $params['_use_rewrite'] = true;
            $params['_query'] = $query;
            $params['_escape'] = true;
            return $this->_url->getUrl('*/*/*', $params);
        }else{
	        return parent::getRemoveUrl();
        }
    }


    public function isActive(){

        $request = $this->request;
        $currentValue = $this->getValue();
        $requestValue = $this->getFilter()->getRequestVar();
        if(is_array($currentValue)){
            $currentValue = end($currentValue);
        }else{
            $currentValue = $this->getValue();
        }

        $compare = $request->getParam($requestValue);
        if($compare && is_array($compare)
        ) {
            if (in_array($currentValue, $compare)) {
                return true;
            } else {
                return false;
            }
        }else{

            if($compare == $currentValue){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

}
