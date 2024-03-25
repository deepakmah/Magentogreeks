<?php

namespace Magentogreeks\Customwork\Plugin;

class ProductPlugin {


  protected function beforeGetProduct(\Magento\Catalog\Block\Product\View $subject)
    {
        // logging to test override    
        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');
        $logger->debug(__METHOD__ . ' - ' . __LINE__);        
    }


 
  



}