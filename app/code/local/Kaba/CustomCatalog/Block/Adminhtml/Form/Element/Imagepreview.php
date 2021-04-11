<?php

class Kaba_CustomCatalog_Block_Adminhtml_Form_Element_Imagepreview extends Varien_Data_Form_Element_Abstract
{	
	protected $_element;
    
	public function getElementHtml()
	{
		$model = Mage::registry('page_model');
		if($model) {
			return '<img src="' . Mage::helper('customcatalog')->getImage($model->getBannerImageTop(), $model->getId()) . '" width="450"/>';
		}	
	}
}
