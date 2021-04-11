<?php

class Kaba_CustomCatalog_Block_Adminhtml_Page_Index_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('page_index_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('customcatalog')->__('Page Data'));
    }
	
	protected function _prepareLayout()
    {
		$this->addTab('page_section', array(
			'label'     => Mage::helper('catalog')->__('Page Information'),
			'content'   => $this->_translateHtml($this->getLayout()
				->createBlock('customcatalog/adminhtml_page_index_edit_tab_page')->toHtml()),
			'active' 	=> true,
		));	
		
		$this->addTab('seo_section', array(
			'label'     => Mage::helper('catalog')->__('Seo'),
			'content'   => $this->_translateHtml($this->getLayout()
				->createBlock('customcatalog/adminhtml_page_index_edit_tab_seo')->toHtml()),
		));
			
		$this->addTab('products_section', array(
			'label'     => Mage::helper('catalog')->__('Manage Product Collection'),
			'url'       => $this->getUrl('*/*/products', array('_current' => true)),
			'class'     => 'ajax'
		));
		
				
	}
	
	/*public function getBase64Array()
	{
		$pageId = (int)$this->getRequest()->getParam('id');
		$page = Mage::getModel('customcatalog/page')->load($pageId);
		$serializedFilter = $page->getCatalogFilterSerialized();
		$filter = unserialize($serializedFilter);			
		$filter = http_build_query($filter);
		
		return base64_encode($filter);
	}*/
	
	/**
     * Translate html content
     *
     * @param string $html
     * @return string
     */
    protected function _translateHtml($html)
    {
        Mage::getSingleton('core/translate_inline')->processResponseBody($html);
        return $html;
    }
}
