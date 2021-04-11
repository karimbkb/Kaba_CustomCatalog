<?php

class Kaba_CustomCatalog_Block_Adminhtml_Page_Index_Edit_Tab_Seo extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
    }
	
	/**
     * Prepare attributes form
     *
     * @return null
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('page_model');

        $form = new Varien_Data_Form();
		$form->setUseContainer(false);
        $this->setForm($form);

        $fieldset = $form->addFieldset('seo_fieldset',
            array('legend' => Mage::helper('customcatalog')->__('Seo Information'))
        );  		     
       
        $fieldset->addField('meta_title', 'text', array(
            'name' => 'meta_title',
            'label' => Mage::helper('customcatalog')->__('Meta Title'),
            'title' => Mage::helper('customcatalog')->__('Meta Title'),
            'required' => false,
        ));
		
		$fieldset->addField('meta_keywords', 'text', array(
            'name' => 'meta_keywords',
            'label' => Mage::helper('customcatalog')->__('Meta Keywords'),
            'title' => Mage::helper('customcatalog')->__('Meta Keywords'),
            'required' => false,
        ));
		
        $fieldset->addField('meta_description', 'textarea', array(
            'name' => 'meta_description',
            'label' => Mage::helper('customcatalog')->__('Meta Description'),
            'title' => Mage::helper('customcatalog')->__('Meta Description'),
            'style' => 'height: 100px;',
        ));	
		
		if(is_object($model)) {
			$form->setValues($model->getData()); 
		}   
    }	
}
