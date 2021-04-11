<?php

class Kaba_CustomCatalog_IndexController extends Mage_Core_Controller_Front_Action
{
	public function testAction()
	{
		$collection = Mage::getResourceModel('catalog/product_attribute_collection');
        $collection
            ->setItemObjectClass('catalog/resource_eav_attribute')
            ->setAttributeSetFilter(4)
            ->addStoreLabel(Mage::app()->getStore()->getId())
            ->setOrder('position', 'ASC');
        $collection->load();
		
		Zend_Debug::dump($collection->getData());		
	}
	
	public function campaignBuilderAction()
	{		
		$category = $this->getRequest()->getParam('category');		
		$categories = explode(',', $category);
		$productSkuArray = array();
		
		foreach($categories as $categoryId) {		
			$products = Mage::getModel('catalog/category')->load($categoryId)->getProductCollection();
			Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
			
			foreach($products as $product) {
				$productSkuArray[] = $product->getSku();
			}
		}
		
		$productSkus = array_unique($productSkuArray);
		
		echo "Product count: " . count($productSkus);
		echo "<hr>";
		echo "SKUs:<br>" . implode(',', $productSkus);
	}
	
	protected function _validatePage()
	{
		$page = Mage::getModel('customcatalog/page')->getPage();
		if(!$page->isActive() || !$page->validateStoreIds() || !$page->validateTimeframe()) {
			$this->_redirect('no-route'); 
		}
	}
	
	public function indexAction()
	{		
		$this->_validatePage();		
		$this->loadLayout();
		$this->renderLayout();
	}
}