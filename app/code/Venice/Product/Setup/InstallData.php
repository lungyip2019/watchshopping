<?php

namespace Venice\Product\Setup;


use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Venice\Product\Logger\Logger;

class InstallData implements InstallDataInterface{

    protected $_logger;

    public function __construct(Logger $logger){
        $this->_logger = $logger;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context){

            $this->_logger->debug('starting to install attributes for products'); 
            $setup->startSetup();
            $setup->endSetup();
            $this->_logger->debug('Finish install attributes for products'); 
    }

}

?>