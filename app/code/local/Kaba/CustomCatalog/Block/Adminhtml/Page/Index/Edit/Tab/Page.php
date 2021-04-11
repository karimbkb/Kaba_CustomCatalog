<?php

class Kaba_CustomCatalog_Block_Adminhtml_Page_Index_Edit_Tab_Page extends Mage_Adminhtml_Block_Widget_Form
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
		$stores = (is_object($model)) ? array('websites' => explode(',', $model->getStores())) : '';

        $form = new Varien_Data_Form();
		$form->setUseContainer(false);
        $this->setForm($form);

        $fieldset = $form->addFieldset('base_fieldset',
            array('legend' => Mage::helper('customcatalog')->__('Page Information'))
        ); 
		
		$fieldset->addField('page_id', 'hidden', array(
            'name' => 'page_id'		
        ));      
        		
		$fieldset->addField('is_active', 'select', array(
            'name' => 'is_active',
            'label' => Mage::helper('customcatalog')->__('Active'),
            'title' => Mage::helper('customcatalog')->__('Active'),
			'values' => array(array('label' => Mage::helper('customcatalog')->__('Yes'), 'value' => '1'), array('label' => Mage::helper('customcatalog')->__('No'), 'value' => '0')),
            'required' => true,			
        ));      
       
        $fieldset->addField('page_name', 'text', array(
            'name' => 'page_name',
            'label' => Mage::helper('customcatalog')->__('Page Name'),
            'title' => Mage::helper('customcatalog')->__('Page Name'),
            'required' => true,
        ));
		
		$fieldset->addField('headline', 'text', array(
            'name' => 'headline',
            'label' => Mage::helper('customcatalog')->__('Headline'),
            'title' => Mage::helper('customcatalog')->__('Headline'),
            'required' => false,
        ));
		
		$fieldset->addField('page_url', 'text', array(
            'name' => 'page_url',
            'label' => Mage::helper('customcatalog')->__('Page Url'),
            'title' => Mage::helper('customcatalog')->__('Page Url'),
            'required' => true,
			'class' => 'validate-campaign-url'
        ));
		#$form->getElement('page_url')->setOnkeyup('validateUrl(this.value);');
		
		$fieldset->addField('banner_image_top', 'imagefile', array(
            'name' => 'banner_image_top',
            'label' => Mage::helper('customcatalog')->__('Top Banner Upload'),
            'title' => Mage::helper('customcatalog')->__('Top Banner Upload'),
            'required' => false			
        ));
		
		if($model && strlen($model->getBannerImageTop())) {
			$fieldset->addType('imagepreview', 'Kaba_CustomCatalog_Block_Adminhtml_Form_Element_Imagepreview');
			$fieldset->addField('tmp', 'imagepreview', array(
				'name' => 'tmp',
				'label' => Mage::helper('customcatalog')->__('Top Banner Preview'),
				'title' => Mage::helper('customcatalog')->__('Top Banner Preview'),
				'required' => false
			));
		}

        $fieldset->addField('text', 'textarea', array(
            'name' => 'text',
            'label' => Mage::helper('customcatalog')->__('Text'),
            'title' => Mage::helper('customcatalog')->__('Text'),
            'style' => 'height: 100px;',
			'required' => false,
        ));
		
		if (Mage::app()->isSingleStoreMode()) {
            $storeId = Mage::app()->getStore(true)->getStoreId();
            $fieldset->addField('stores', 'hidden', array(
                'name'     => 'stores[]',
                'value'    => $stores
            ));
            if(is_object($model)) {
                $model->setStores($storeId);
            }
        } else {
            $field = $fieldset->addField('stores', 'multiselect', array(
                'name'     => 'stores[]',
                'label'     => Mage::helper('customcatalog')->__('Stores'),
                'title'     => Mage::helper('customcatalog')->__('Stores'),
                'required' => true,
                'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(),
				'value' 	=> $stores
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        }        

        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $fieldset->addField('from_date', 'date', array(
            'name'   => 'from_date',
            'label'  => Mage::helper('customcatalog')->__('From Date'),
            'title'  => Mage::helper('customcatalog')->__('From Date'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso
        ));
				
        $fieldset->addField('to_date', 'date', array(
            'name'   => 'to_date',
            'label'  => Mage::helper('customcatalog')->__('To Date'),
            'title'  => Mage::helper('customcatalog')->__('To Date'),
            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format'       => $dateFormatIso
        ));      
			
		if(is_object($model)) {
			$form->setValues($model->getData()); 
		}
    }
}
