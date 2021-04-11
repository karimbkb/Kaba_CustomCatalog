<?php

class Kaba_CustomCatalog_Block_Adminhtml_Page_Index_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('page_index_form');
        $this->setTitle(Mage::helper('customcatalog')->__('Page Information'));
    }
    
    protected function _prepareForm()
    {	
		$form = new Varien_Data_Form(array(
			'id' => 'edit_form',
			'action' => $this->getUrl('*/*/save', array('page_id' => $this->getRequest()->getParam('page_id'))),
			'method' => 'post',
			'enctype' => 'multipart/form-data'
		));
		
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }


}
