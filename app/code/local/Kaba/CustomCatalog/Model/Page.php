<?php

class Kaba_CustomCatalog_Model_Page extends Mage_Core_Model_Abstract
{	
	protected $page = null;	

	protected function _construct()
    {
        parent::_construct();
        $this->_init('customcatalog/page');
    } 
		
	public function getPageUrl()
	{
		$url = Mage::helper('core/url')->getCurrentUrl();
		$urlParsed = Mage::getModel('core/url')->parseUrl($url);
		
		return substr($urlParsed->getPath(), 1, strlen($urlParsed->getPath()));
	}
	
	public function getPage()
	{
		if(is_null($this->page)) {
			$this->page = $this->load($this->getPageUrl(), 'page_url');
		} 
		return $this->page;
	}
	
	public function validateTimeframe()
	{		
		if(!strlen($this->page->getFromDate()) && !strlen($this->page->getToDate())) {
			return true;
		}
		
		$date = date('Y-m-d H:i:s');
		if($this->page->getFromDate() < $date && $this->page->getToDate() > $date) {
			return true;
		}
		
		return false;
	}
	
	public function isActive()
	{		
		if($this->page->getisActive() == 1) {
			return true;
		}
		return false;
	}
	
	public function validateStoreIds()
	{
		$stores = $this->page->getStores();
		$currentStoreId = Mage::app()->getStore()->getStoreId();
		
		$storeArray = explode(',', $stores);	
		
		if(in_array($currentStoreId, $storeArray)) {
			return true;
		}
		return false;
	}
	
	public function getProductCollectionOfPage($collection)
	{		
		$page = $this->getPage();
		$pageId = $page->getPageId();
		
		if($page->getProductCollectionType() == Kaba_CustomCatalog_Helper_Data::TYPE_FILTER) {	
			$filters = unserialize($page->getSerializedFilters());
			foreach($filters as $filter) {
				$operator = $this->getMageOperator($filter['operator']);
				$collection->addFieldToFilter($filter['attribute'], array($operator => $filter['value']));
			}			
		}
		
		if($page->getProductCollectionType() == Kaba_CustomCatalog_Helper_Data::TYPE_SKU) {		
			$pageProducts = Mage::getModel('customcatalog/page_products')->getCollection()
			->addFieldToSelect('product_id')
			->addFieldToFilter('page_id', $pageId);	
			
			$collection->addFieldToFilter('entity_id', array('in' => $pageProducts->getData()));
		}

		return $collection;
	}
	
	public function getMageOperator($operatorParam)
	{
		$operators = Kaba_CustomCatalog_Block_Adminhtml_Form_Element_Filter::$operators;
		
		foreach($operators as $operator => $mageOperator) {
			if($operatorParam == $operator) {
				return $mageOperator;
				break;
			}
		}
	}
}