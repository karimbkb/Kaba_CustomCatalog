<?php

class Kaba_CustomCatalog_Block_Adminhtml_Page_Index_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Grid
{		
    public function __construct()
    {
        parent::__construct();
        $this->setId('productsGrid');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        if ($this->_getPageId()) {
            $this->setDefaultFilter(array('in_products'=>1));
        }
    }
	
	protected function _getPageId()
	{
		return $this->getRequest()->getParam('id');
	}
	
	protected function _getPageModel()
	{
		return Mage::registry('page_model');
	}
	
	protected function _getProductIds($products)
	{
		$productIds = array();
		foreach($products as $product) {
			$productIds[] = $product->getProductId();
		}
		return $productIds;
	}
	
	public function getProductIds()
	{
		$products = Mage::getModel('customcatalog/page_products')->getCollection()
		->addFieldToFilter('page_id', $this->_getPageId())
		->addFieldToSelect('product_id');		
		
		return $this->_getProductIds($products);
	}
	
	/**
     * Add filter
     *
     * @param object $column
     * @return Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Crosssell
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_products') {
            $productIds = $this->getProductIds();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
            } else {
                if($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$productIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * Prepare collection for grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
	{   
		$collection = Mage::getModel('catalog/product')->getCollection()		
		->addAttributeToSelect('*');	
				
		if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $collection->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left');
        }
	
		$this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Define grid columns
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
		$this->addColumn('in_products', array(
			'header_css_class' => 'a-center',
			'type'      => 'checkbox',
			'name'      => 'in_products',
			'values'    => $this->getProductIds(),
			'align'     => 'center',
			'index'     => 'entity_id'
		));
		
		$this->addColumn('entity_id', array(
            'header' => Mage::helper('catalog')->__('Id'),
            'index'  => 'entity_id',
			'width'	 => '30',
        ));
		
        $this->addColumn('name', array(
            'header' => Mage::helper('catalog')->__('Name'),
            'index'  => 'name',
			'width'	 => '400',
			'value'  => 'test'
        ));
		
		$this->addColumn('sku', array(
            'header' => Mage::helper('catalog')->__('SKU'),
            'index'  => 'sku',
			'width'	 => '50',
        ));
		
		$this->addColumn('type_id', array(
            'header' => Mage::helper('catalog')->__('Type Id'),
            'index'  => 'type_id',
			'width'	 => '50',
			'type'  => 'options',
			'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
        ));
		
		$sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->load()
            ->toOptionHash();

        $this->addColumn('set_name',
            array(
                'header'=> Mage::helper('catalog')->__('Attrib. Set Name'),
                'width' => '100px',
                'index' => 'attribute_set_id',
                'type'  => 'options',
                'options' => $sets,
        ));
		
		$this->addColumn('entity_type_id', array(
            'header' => Mage::helper('catalog')->__('Entity Type Id'),
            'index'  => 'entity_type_id',
			'width'	 => '50',
        ));
		
		$store = $this->_getStore();
        $this->addColumn('price', array(
			'header'=> Mage::helper('catalog')->__('Price'),
			'type'  => 'price',
			'currency_code' => $store->getBaseCurrency()->getCode(),
			'index' => 'price',
        ));
		
		$this->addColumn('special_price', array(
            'header' => Mage::helper('catalog')->__('Special Price'),
            'index'  => 'special_price',
			'currency_code' => $store->getBaseCurrency()->getCode(),
			'width'	 => '50',
        ));		

		/*$this->addColumn('categories', array(
			'header'=> Mage::helper('catalog')->__('Categories'),
			'width' => '250px',
			'index' => 'entity_id',
			'frame_callback' => array($this, 'getCategories'),
			'filter_condition_callback' => array($this, 'searchForCategories'),
		));*/      
		
		$this->addColumn('weight', array(
            'header' => Mage::helper('catalog')->__('Weight'),
            'index'  => 'weight',
			'width'	 => '50',
        ));
		
		$this->addColumn('status', array(
            'header' => Mage::helper('catalog')->__('Status'),
            'index'  => 'status',
			'width'	 => '50',
        ));
		
		$this->addColumn('visibility', array(
            'header' => Mage::helper('catalog')->__('Visibility'),
            'index'  => 'visibility',
			'width'	 => '50',
			'type'  => 'options',
            'options' => Mage::getModel('catalog/product_visibility')->getOptionArray(),
        ));		
		
        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $this->addColumn('qty',
                array(
                    'header'=> Mage::helper('catalog')->__('Qty'),
                    'width' => '100px',
                    'type'  => 'number',
                    'index' => 'qty',
            ));
        }

        $this->addColumn('created_at', array(
            'header' => Mage::helper('catalog')->__('Created On'),
            'index'  => 'created_at',
            'type'   => 'datetime',
            'align'  => 'center', 
            'width'  => '160'
        ));
		
        return parent::_prepareColumns();
    }

    /**
     * Get grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/productsGrid', array('_current'=> true));
    }	    
	
	protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
	
	public function getCategories($value)
	{
		$readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$categoryIds = Mage::getModel('catalog/product')->load($value)->getCategoryIds();
		$catIds = array();
		
		foreach($categoryIds as $categoryId) {
			$catIds[] = $categoryId;
		}
		
		$sql = 'SELECT value FROM catalog_category_entity_varchar WHERE store_id = 0 AND attribute_id = 41 AND entity_id IN (' . implode(',', $catIds) . ')';
		$data = $readConnection->fetchAll($sql);
		
		$catStr = null;
		foreach($data as $catName) {
			$catStr .= $catName['value'] . " > ";
		}
				
		return $catStr;
	}
	
	public function searchForCategories($collection, $column)
	{	
		if (!$value = $column->getFilter()->getValue()) {
            return;
        }
		
		$readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$sql = 'SELECT entity_id, value FROM catalog_category_entity_varchar WHERE store_id = 0 AND attribute_id = 41';
		$data = $readConnection->fetchAll($sql);
		$productId = null;
		$productArray = array();
		$productCollection = clone $collection;
		
		foreach($productCollection as $product) {	
			$productId = $product->getId();		
			$categoryIds = $product->getCategoryIds();
			$catIds = array();
			
			foreach($categoryIds as $categoryId) {
				$catIds[] = $categoryId;
			}	
			
			$catStr = null;
			foreach($data as $categoryData) {
				if(in_array($categoryData['entity_id'], $catIds)) {
					$catStr .= $categoryData['value'] . " > ";					
				}
			}
			
			if(stristr($catStr, $value)) {
				$productArray[] = $productId;
			}
		}
				
		$this->getCollection()->addAttributeToFilter('entity_id', array('in' => $productArray));
	}
}
