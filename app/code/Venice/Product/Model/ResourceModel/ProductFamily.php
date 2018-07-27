<?php

namespace Venice\Product\Model\ResourceModel;

use Venice\Product\Api\Data\ProductFamilyInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Venice\Product\Logger\Logger;

class ProductFamily extends AbstractDb{

    protected $_logger;
    public function __construct(
        Context $context,
        Logger $logger)           
	{
        $this->_logger = $logger;
		parent::__construct($context);
	}

    protected function _construct()
    {
        $this->_init('product_family', 'family_id');
    }
  
}

?>