<?php

class Kaba_CustomCatalog_Model_Resource_Page_Products extends Mage_Core_Model_Resource_Db_Abstract
{
	/**
     * Define main table name and attributes table
     */
    protected function _construct()
    {
        $this->_init('customcatalog/page_products', 'link_id');
    }
	
	/** 
	* Get Ids by given skus 
	*/
	public function getIdsBySkus($sku = array())
    {
        $adapter = $this->_getReadAdapter();
		$pIds = array();

        $select = $adapter->select()
            ->from((string)Mage::getConfig()->getTablePrefix() . 'mage_catalog_product_entity', 'entity_id')
            ->where('sku IN (?)', $sku);

       $entityIds = $adapter->fetchAll($select);	   
	   
	   foreach($entityIds as $entityId) {
		   $pIds[] = $entityId['entity_id'];
	   }
	   
	   $pIds = implode('&', $pIds);
	   	   
	   return $pIds;
    }

	
	/**
     * Convert products string into array
     *
     * @param Mage_Catalog_Model_Product $product
     * @param array $data
     * @param int $typeId
     * @return Mage_Catalog_Model_Resource_Product_Link
     */
    public function getProductsArray($productIds)
    {
		$productIds = explode('&', $productIds);
		foreach($productIds as $productId) {
			$productsIds[$productId] = '';
		}
		return $productsIds;
	}
	
	/**
     * Save Product Links process
     *
     * @param Mage_Catalog_Model_Product $product
     * @param array $data
     * @param int $typeId
     * @return Mage_Catalog_Model_Resource_Product_Link
     */
    public function saveProductIds($page, $data)
    {
		$data = $this->getProductsArray($data);
		
        if (!is_array($data)) {
            $data = array();
        }

        $adapter = $this->_getWriteAdapter();

        $bind   = array(
            ':page_id'    => (int)$page->getPageId(),
        );
        $select = $adapter->select()
            ->from($this->getMainTable(), array('product_id', 'link_id'))            
            ->where('page_id = :page_id');

        $links   = $adapter->fetchPairs($select, $bind);
				
        $deleteIds = array();
        foreach($links as $linkedProductId => $linkId) {
            if (!isset($data[$linkedProductId])) {
                $deleteIds[] = (int)$linkId;
            }
        }
        if (!empty($deleteIds)) {
            $adapter->delete($this->getMainTable(), array(
                'link_id IN (?)' => $deleteIds,
            ));
        }

        foreach ($data as $linkedProductId => $linkInfo) {
            $linkId = null;
            if (isset($links[$linkedProductId])) {
                $linkId = $links[$linkedProductId];
                unset($links[$linkedProductId]);
            } else {
                $bind = array(
                    'page_id'        => $page->getId(),
                    'product_id' => $linkedProductId
                );
                $adapter->insert($this->getMainTable(), $bind);
                $linkId = $adapter->lastInsertId($this->getMainTable());
            }           
        }
    }
	
	/**
     * Remove all product ids by page id
     *
     * @param int $pageId
     */
    public function removeProductIds($pageId)
    {		
        $adapter = $this->_getWriteAdapter();		
		$adapter->delete($this->getMainTable(), array('page_id = ?' => $pageId));
	}
}