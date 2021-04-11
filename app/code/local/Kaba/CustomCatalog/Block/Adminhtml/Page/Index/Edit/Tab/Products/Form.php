<?php

class Kaba_CustomCatalog_Block_Adminhtml_Page_Index_Edit_Tab_Products_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $model = Mage::registry('page_model');
        $pageId = (is_object($model)) ? $model->getId() : '';

        $form->setHtmlIdPrefix('products_');     
		
		$gridBlock = $this->getLayout()->getBlock('page_index_edit_tab_products');
        $gridBlockJsObject = '';
        if ($gridBlock) {
            $gridBlockJsObject = $gridBlock->getJsObjectName();
        }   

        $fieldset = $form->addFieldset('products_fieldset', array('legend' => Mage::helper('customcatalog')->__('Product Selection')));
        $fieldset->addClass('ignore-validate');
		
		$fieldset->addField('page_id', 'hidden', array(
            'name'     => 'page_id',
            'required' => false, 
			'value'    => $pageId
        ));
				
		$fieldset->addField('product_collection_type', 'select', array(
			'name'  => 'product_collection_type',
			'label' => Mage::helper('customcatalog')->__('Type of product collection'),
			'values' => array(array('value' => 'sku', 'label' => 'SKU based'), array('value' => 'filter', 'label' => 'Filter based')),
			'value' => $model->getProductCollectionType(),
			'onchange' => 'evalSelectBox();'
		));
			
        $fieldset->addField('skus', 'textarea', array(
            'name'     => 'skus',
            'label'    => Mage::helper('customcatalog')->__('SKUs'),
            'title'    => Mage::helper('customcatalog')->__('SKUs'),
            'required' => false, 
			'note'     => Mage::helper('customcatalog')->__('Comma seperated SKUs for product selection.'),
        ));		
       
        $idPrefix = $form->getHtmlIdPrefix();
        $autoSelectUrl = $this->getAutoSelectUrl();

        $fieldset->addField('save_button', 'note', array(
            'text' => $this->getButtonHtml(
                Mage::helper('customcatalog')->__('Auto Select'),
                "autoSelectProducts('{$idPrefix}' ,'{$autoSelectUrl}', '{$gridBlockJsObject}')",
                'autoSelect'
            )
        ));
		
		$fieldset->addType('filter', 'Kaba_CustomCatalog_Block_Adminhtml_Form_Element_Filter');

		$fieldset->addField('filters', 'filter', array(
			'name'     => 'filters',
			'label'    => Mage::helper('customcatalog')->__('Filter'),
			'title'    => Mage::helper('customcatalog')->__('Filter'),
			'required' => false, 
		));		
		
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Retrieve URL to Generate Action
     *
     * @return string
     */
    public function getAutoSelectUrl()
    {
        return $this->getUrl('*/*/autoSelect');
    }
}
