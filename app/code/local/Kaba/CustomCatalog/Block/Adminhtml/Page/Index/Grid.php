<?php
class Kaba_CustomCatalog_Block_Adminhtml_Page_Index_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('page_grid');
		$this->setDefaultSort('page_id');
		$this->setDefaultDir('DESC');
		$this->setUseAjax(true);
		$this->setSaveParametersInSession(true);
	}
	
	protected function _prepareCollection()
	{
		$collection = Mage::getModel('customcatalog/page')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns()
	{
		$this->addColumn('page_id', array (
		'index' => 'page_id',
		'header' => Mage::helper('customcatalog')->__('Page id'),
		'type' => 'number',
		'sortable' => true,
		'width' => '100px',
		));
		
		$this->addColumn('page_name', array (
		'index' => 'page_name',
		'header' => Mage::helper('customcatalog')->__('Name'),
		'sortable' => true,
		));
		
		$this->addColumn('headline', array (
		'index' => 'headline',
		'header' => Mage::helper('customcatalog')->__('Headline'),
		'sortable' => true,
		));
		
		$this->addColumn('page_url', array (
		'index' => 'page_url',
		'header' => Mage::helper('customcatalog')->__('Page Url'),
		'sortable' => true,
		));
		
		$this->addColumn('banner_image_top', array (
		'index' => 'banner_image_top',
		'header' => Mage::helper('customcatalog')->__('Top Banner'),
		'sortable' => false,
		'frame_callback' => array($this, 'displayImage')
		));
		
		$this->addColumn('created_at', array (
		'index' => 'created_at',
		'header' => Mage::helper('customcatalog')->__('Created At'),
		'sortable' => true,
		));
		
		$this->addColumn('stores', array (
		'index' => 'stores',
		'header' => Mage::helper('customcatalog')->__('Stores'),
		'sortable' => false,
		'frame_callback' => array($this, 'displayStores')
		));
		
		$this->addColumn('is_active', array (
		'index' => 'is_active',
		'header' => Mage::helper('customcatalog')->__('Status'),
		'sortable' => true,
		'frame_callback' => array($this, 'displayStatus')
		));
		
		$this->addColumn('action',
            array(
                'header'    =>  Mage::helper('customer')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('customer')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));

        //$this->addExportType('*/*/exportCsv', Mage::helper('customer')->__('CSV'));
        //$this->addExportType('*/*/exportXml', Mage::helper('customer')->__('Excel XML'));
				
		return parent::_prepareColumns();
	}
	
	public function getGridUrl()
	{
		return $this->getUrl('*/*/grid', array('_current' => true));
	}
	
	public function getRowUrl($row) 
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getPageId()));
	}
	
	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('page_id');
		$this->getMassactionBlock()->setFormFieldName('page_checkboxes');
		
		$this->getMassactionBlock()->addItem('delete', array(
			'label' => Mage::helper('customcatalog')->__('Delete'),
			'url' => $this->getUrl('*/*/massDelete'),
			'confirm' => Mage::helper('customcatalog')->__('Are you sure?')
		));
		
		return $this;
	}
	
	public function displayImage($value, $row, $column, $isExport)
	{
		if(empty($value)) {
			return Mage::helper('catalog')->__('No Image');
		}
		
		return '<img src="' . Mage::helper('customcatalog')->getImage($value, $row->getPageId()) . '" width="250">';
	}
	
	public function displayStatus($value, $row, $column, $isExport)
	{
		switch($value) {
			default:
			case 0:
				return '<div class="" style="background-color:#C0C0C0; margin: 0 0 0 10px; color: #FFF; border-radius: 17px; width: 120px; font-size: 9px; text-align: center; text-transform: uppercase; font-weight: bold;">' . Mage::helper('adminhtml')->__('Inactive') . '</div>';
			break;
			
			case 1:
				return '<div class="" style="background-color:#00cc00; margin: 0 0 0 10px; color: #FFF; border-radius: 17px; width: 120px; font-size: 9px; text-align: center; text-transform: uppercase; font-weight: bold;">' . Mage::helper('adminhtml')->__('Active') . '</div>';
			break;
		}
	}
	
	public function displayStores($value, $row, $column, $isExport)
	{
		$stores = explode(',', $value);
		$storesTmp = array();
		
		foreach($stores as $storeId) {
			$storesTmp[] = Mage::app()->getStore($storeId)->getWebsite()->getName() . ': ' . Mage::app()->getStore($storeId)->getName();
		}
		return implode('<br>', $storesTmp);
	}
}
