<?php

namespace Zemez\Amp\Block\Page\Head\Tm;

class Cms extends AbstractTm
{
	/**
	 * Retrieve additional data
	 * @return array
	 */
    public function getTmParams()
    {
        $params = parent::getTmParams();
        return array_merge($params, [
            'type' => 'website',
            'url' => $this->_helper->getCanonicalUrl(
                $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true])
            ),
        ]);
    }
}
