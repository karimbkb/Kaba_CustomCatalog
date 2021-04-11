<?php

class Kaba_CustomCatalog_Model_Resource_Page_Products_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
	/**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('customcatalog/page_products');
    }
}