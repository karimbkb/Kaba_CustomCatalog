<?php

class Kaba_CustomCatalog_Block_Adminhtml_Page_Index_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    /**
     * Initialize form
     * Add standard buttons
     * Add "Save and Continue" button
     */
    public function __construct()
    {		
		parent::__construct();
		$this->_objectId = 'page_id';
		$this->_blockGroup = 'customcatalog';
		$this->_controller = 'adminhtml_page_index';
		#$this->_mode = 'edit';
		
        $this->_addButton('save_and_continue_edit', array(
            'class'   => 'save',
            'label'   => Mage::helper('customcatalog')->__('Save and Continue Edit'),
			'onclick'   => 'editForm.submit($(\'edit_form\').action + \'back/edit/\')', 
        ), 10);
    }

    /**
     * Getter for form header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        $page = Mage::registry('page_model');
        if (is_object($page) && $page->getPageId()) {
            return Mage::helper('customcatalog')->__("Edit Page '%s'", $this->escapeHtml($page->getPageName()));
        }
        else {
            return Mage::helper('customcatalog')->__('New Page');
        }
    }
}
