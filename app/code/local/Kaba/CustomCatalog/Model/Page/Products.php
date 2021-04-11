<?php

class Kaba_CustomCatalog_Model_Page_Products extends Mage_Core_Model_Abstract
{
	/**
     * Initialize resource
     */
    protected function _construct()
    {
        $this->_init('customcatalog/page_products');
    } 
	
	/**
     * Save data for product/page relations
     *
     * @param   Kaba_CustomCatalog_Model_Page $page
	 * @param   array()
     * @return  Mage_Catalog_Model_Page_Products
     */
    public function savePageProductsRelation($page, $productIds)
    {        
        if (strlen($productIds)) {
            $this->_getResource()->saveProductIds($page, $productIds);
        }       
    }
	
	/**
     * Save data for product/page relations from given skus
     *
     * @param   Kaba_CustomCatalog_Model_Page $page
	 * @param   array()
     * @return  Mage_Catalog_Model_Page_Products
     */
    public function savePageProductsRelationFromSkus($page, $productSkus)
    {
		$skuArray = explode(',', $productSkus);		
		$productIds = $this->_getResource()->getIdsBySkus($skuArray);
				        
        $this->savePageProductsRelation($page, $productIds); 
		  return $productIds; 
    }
}