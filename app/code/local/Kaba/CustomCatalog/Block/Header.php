<?php

class Kaba_CustomCatalog_Block_Header extends Mage_Core_Block_Template
{
	public function getPageData()
	{
		$page = Mage::getModel('customcatalog/page');
		$pageUrl = $page->getPageUrl();
		$pageData = Mage::getModel('customcatalog/page')->load($pageUrl, 'page_url');
		
		return $pageData;
	}
}