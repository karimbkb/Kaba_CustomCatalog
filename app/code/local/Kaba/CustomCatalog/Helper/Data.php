<?php

class Kaba_CustomCatalog_Helper_Data extends Mage_Core_Helper_Abstract
{
	const DS = '/';
	const PATH = 'customcatalog/pages';
	
	const TYPE_SKU = 'sku';
	const TYPE_FILTER = 'filter';
		
	public function getImage($file, $pageId)
	{
		$path = self::PATH . DS . $pageId . DS . $file;
		return Mage::getBaseUrl('media') . $path;
	}
}