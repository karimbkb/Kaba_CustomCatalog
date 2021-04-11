<?php
class Kaba_CustomCatalog_Block_Adminhtml_Page_Index extends Mage_Adminhtml_Block_Widget_Grid_Container
{	
	public function __construct()
	{
		$this->_headerText = Mage::helper('customcatalog')->__('Pages');
		$this->_blockGroup = 'customcatalog';
		$this->_controller = 'adminhtml_page_index'; 
		$this->_addButtonLabel = Mage::helper('customcatalog')->__('Add New Page');
		parent::__construct();
	}
		
	protected function _prepareLayout()
	{		
		return parent::_prepareLayout();
	}
}