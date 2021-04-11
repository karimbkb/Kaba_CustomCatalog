<?php

class Kaba_CustomCatalog_Block_Adminhtml_Form_Element_Filter extends Varien_Data_Form_Element_Abstract
{	
	protected $_element;
	protected $_attributes = array();
	public static $operators = array(
		'>' => 'gt', 
		'>=' => 'gteq', 
		'<' => 'lt', 
		'<=' => 'lteg', 
		'=' => 'eq', 
		'!=' => 'neq'
	);	
    
	public function getElementHtml()
	{		
		$html = null;
		$model = Mage::registry('page_model');	
		$filters = unserialize($model->getSerializedFilters());
		
		for($i = 1; $i < 10; $i++) {	
			$html .= '<div id="products_filter">';
			
			$html .= '<select id="attributes-select" name="attributes-select-' . $i . '">';
			foreach($this->getFilterableAttributes() as $attrCode => $attrName) {
				$selected = null;
				if(isset($filters[$i]) && $filters[$i]['attribute'] == $attrCode) {
					$selected = 'selected="selected"';
				}
				$html .= '<option value="' . $attrCode . '" ' . $selected . '>' . $attrName . '</option>';
			}
			$html .= '</select>';	
			
			$html .= '<select id="operators-select" name="operators-select-' . $i . '">';
			foreach(self::$operators as $operator => $mageOperator) {
				$selected = null;
				if(isset($filters[$i]) && $filters[$i]['operator'] == $operator) {
					$selected = 'selected="selected"';
				}
				$html .= '<option value="' . $operator . '" ' . $selected . '>' . $operator . '</option>';
			}
			$html .= '</select>';
			
			$value  = null;
			if(isset($filters[$i]) && strlen($filters[$i]['value'])) {
				$value = $filters[$i]['value'];
			}
			$html .= '<input id="value-text" name="value-text-' . $i . '" type="text" value="' . $value . '">';
			
			$html .= '</div>';
		}
		
		return $html;
	}
	
	public function getFilterableAttributes()
	{
		if(!isset($this->_attributes)) {
			return $this->_attributes;
		}
		
		$this->_attributes[''] = Mage::helper('customcatalog')->__('Please select...');
		$attributeCollection = Mage::getResourceModel('catalog/product_attribute_collection');
		foreach ($attributeCollection as $attribute) {
            $this->_attributes[$attribute->getAttributeCode()] = $attribute->getAttributeCode();
        }			
		return $this->_attributes;
	}
}
