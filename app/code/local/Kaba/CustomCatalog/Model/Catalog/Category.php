<?php

class Kaba_CustomCatalog_Model_Catalog_Category extends Mage_Catalog_Model_Category
{
	 /**
     * Get category products collection
     *
     * @return Varien_Data_Collection_Db
     */
    public function getProductCollection()
    {
		if($this->getLevel() == 1) {
			$collection = Mage::getResourceModel('catalog/product_collection')
            ->setStoreId($this->getStoreId());  
		} else {
			$collection = Mage::getResourceModel('catalog/product_collection')
				->setStoreId($this->getStoreId())
				->addCategoryFilter($this);
		}
        return $collection;
    }
}