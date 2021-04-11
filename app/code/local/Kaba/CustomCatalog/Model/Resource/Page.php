<?php

class Kaba_CustomCatalog_Model_Resource_Page extends Mage_Core_Model_Resource_Db_Abstract
{
	protected function _construct()
    {
        $this->_init('customcatalog/page', 'page_id');      
    }
}