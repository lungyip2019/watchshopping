<?php

namespace Venice\Product\Model\ResourceModel;

use Venice\Product\Api\Data\WatchSpecInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Venice\Product\Logger\Logger;

class WatchSpec extends AbstractDb{

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
        $this->_init('product_watch_spec', 'watch_spec_id');
    }
  
}

?>