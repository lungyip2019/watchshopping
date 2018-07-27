<?php
namespace Venice\Product\Model\ResourceModel\WatchSpec;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Venice\Product\Model\WatchSpec','Venice\Product\Model\ResourceModel\WatchSpec');
    }
}

?>