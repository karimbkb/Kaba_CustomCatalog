<?php

class Kaba_CustomCatalog_Adminhtml_Page_IndexController extends Mage_Adminhtml_Controller_Action
{	
	protected $_mode = null;
	
	protected function _getSession() 
	{
		return Mage::getSingleton('adminhtml/session');
	}
	
	protected function _getNextPageId()
	{
		if($pageId = $this->getRequest()->getParam('page_id')) {
			return $pageId;
		} else {			
			$page = Mage::getModel('customcatalog/page')->getCollection();
			$lastId = $page->getLastItem()->getPageId();
		
			return (int)$lastId + 1;
		}
	}
	
	protected function _getLastPageId()
	{
		$page = Mage::getModel('customcatalog/page')->getCollection();
		$lastId = $page->getLastItem()->getPageId();
		
		return (int)$lastId;
	}
	
	protected function _uploadFile($file, $str)
	{
		if($file) {
			$uploader = new Varien_File_Uploader($str);
			$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));			 
			$uploader->setAllowRenameFiles(false);
			$uploader->setFilesDispersion(false);
							   
			$path = $this->getSaveImagesPath();													   
			$result = $uploader->save($path, $file);
		 
			return $result['file'];	
		}	
		return null;			
	}
	
	protected function _initPageModel()
	{
		$id = $this->getRequest()->getParam('id');
		if($id) {
			$page = Mage::getModel('customcatalog/page')->load($id);
			if(!$page->getPageId()) {
				$this->_getSession()->addError(Mage::helper('customcatalog')->__('This item does not exist.'));					
			} else {
				Mage::register('page_model', $page);
			}
		}
	}
			
	public function getFileName($pageId)
	{
		return Mage::getModel('customcatalog/page')->load((int)$pageId)->getBannerImageTop();
	}
	
	public function getSaveImagesPath()
	{
		return Mage::getBaseDir('media') . '/customcatalog/pages/' . $this->_getNextPageId();
	}
	
	public function indexAction()
	{
		$this->loadLayout();
		$this->_title($this->__('Custom Catalog Pages'));
		$this->_setActiveMenu('customcatalog/index');
		$this->_addContent($this->getLayout()->createBlock('customcatalog/adminhtml_page_index'));
		$this->renderLayout();
	}
	
	public function	newAction()
	{
		$this->_forward('edit');
	}
	
	public function	editAction()
	{
		$this->_initPageModel();		
		$this->loadLayout();
		$this->_setActiveMenu('customcatalog/index');
		$this->renderLayout();
	}
	
	public function	saveAction()
	{		
		$params = $this->getRequest()->getParams();	
		if (!$this->_validateFormKey($params['form_key'])) {
			$this->_getSession()->addError(Mage::helper('adminhtml')->__('Invalid Form Key. Please refresh the page.'));
			$this->_redirectReferer();			
		}

		$redirectBack = $this->getRequest()->getParam('back', false);		
		$pageUrl = Mage::getModel('customcatalog/url');
		
		$pageId = (int)$params['page_id'];
		$storeIds = implode(',', $params['stores']);			
		$curDate = date('Y-m-d H:i:s');
		$productIds = (isset($params['page'])) ? $params['page']['products'] : '';
		$typeUsed = isset($params['product_collection_type']) ? $params['product_collection_type'] : '';
		
		if(in_array($params['is_active'], array(0,1))) {
			$isActive = $params['is_active']; 
		} else {
			$this->_getSession()->addError(Mage::helper('adminhtml')->__('Invalid input.'));
			$this->_redirectReferer();	
		}
					
		if($pageId > 0) {
			$filename = $this->getFileName($pageId);
			$filename = $this->_uploadFile($_FILES['banner_image_top']['name'], 'banner_image_top');						
			
			$page = Mage::getModel('customcatalog/page')->load($pageId);
			$pageUrl->setCurrentPageUrl($pageId);						
		} else {	
			$filename = $this->_uploadFile($_FILES['banner_image_top']['name'], 'banner_image_top');				
			$page = Mage::getModel('customcatalog/page');			
			$page->setCreatedAt($curDate);
			
			$pageUrl->setRequestPath($params['page_url']);	
			$pageUrl->setStoreIds($params['stores']);				
			
		}
				
		$page->setIsActive($isActive);
		$page->setPageName($params['page_name']);
		$page->setHeadline($params['headline']);
		$page->setPageUrl($params['page_url']);
		$page->setBannerImageTop($filename);
		$page->setText($params['text']);
		$page->setStores($storeIds);
		$page->setFromDate($params['from_date']);
		$page->setToDate($params['to_date']);
		$page->setMetaTitle($params['meta_title']);
		$page->setMetaKeywords($params['meta_keywords']);
		$page->setMetaDescription($params['meta_description']);
		$page->setUpdatedAt($curDate);	
		
		$pageUrl->setRequestPath($params['page_url']);				
		$pageUrl->setCurrentPageUrl($pageId);
		$pageUrl->setStoreIds($params['stores']);
		
		if(isset($params['product_collection_type'])) {
			$page->setProductCollectionType($params['product_collection_type']);
		}
		
		if($typeUsed == Kaba_CustomCatalog_Helper_Data::TYPE_FILTER) {
			$filter = $this->_getFilter($params);
			$page->setSerializedFilters($filter);	
		}
		
		if($typeUsed == Kaba_CustomCatalog_Helper_Data::TYPE_SKU) {
			$page->setSerializedFilters(null);	
		}	
			
		try {
			$page->save();
			$pageId = $page->getId();
			if($typeUsed == Kaba_CustomCatalog_Helper_Data::TYPE_SKU) {
				Mage::getModel('customcatalog/page_products')->savePageProductsRelation($page, $productIds);					
			}
			if($typeUsed == Kaba_CustomCatalog_Helper_Data::TYPE_FILTER) {
				Mage::getResourceModel('customcatalog/page_products')->removeProductIds($pageId);
			}
			$pageUrl->addUrl();
		} catch(Exception $e) {
			$this->_getSession()->addError(Mage::helper('adminhtml')->__('Error occured :' . $e->getMessage()));
			$this->_redirect('*/*/edit', array('id' => $pageId, '_current' => true));	
			return;
		}
		
		$this->_getSession()->addSuccess(Mage::helper('adminhtml')->__('Success.'));
		
		if ($redirectBack) {
			$this->_redirect('*/*/edit', array('id' => $pageId, '_current' => true));	
		} else {
			$this->_redirect('*/*/');
		}	
		
	}
	
	protected function _getFilter($params)
	{
		$filter = array();
		for($i = 1; $i < 10; $i++) {	
			if(strlen($params['attributes-select-' . $i])) {
				$filter[$i]['attribute'] = $params['attributes-select-' . $i]; 
				$filter[$i]['operator'] = $params['operators-select-' . $i]; 
				$filter[$i]['value'] = $params['value-text-' . $i]; 
			}
		}
		return serialize($filter);
	}
	
	/**
     * Get products grid and serializer block
     */
    public function productsAction()
    {
		$this->_initPageModel();	
		
		if(!$this->getRequest()->getParam('id')) {
			echo $this->getLayout()->createBlock('core/messages')->addWarning($this->__('Please save this campaign in order to create a product collection.'))->toHtml();
			return;	
		}
			
        $this->loadLayout();	
        $this->renderLayout();
    }
	
	 /**
     * Get products grid
     */
    public function productsGridAction()
    {
		$this->_initPageModel();		
        $this->loadLayout();
        $this->renderLayout();
    }
	
	 /**
     * Get grid
     */
    public function gridAction()
    {		
		$this->loadLayout();
		$this->_addContent($this->getLayout()->createBlock('customcatalog/adminhtml_page_index'));
		$this->renderLayout();
    }
		
	public function massDeleteAction()
	{
		$ids = $this->getRequest()->getParam('page_checkboxes');
		if(!is_array($ids))  {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select one or more items.'));
		} else  {			
			try	{								
				foreach ($ids as $id) {
					Mage::getModel('customcatalog/page')->load($id)->delete();
				}				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were deleted.', count($ids)));
				
			} catch (Exception $e)  {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}
	
	/**
     * Generate product selection action
     */
    public function autoSelectAction()
    {
        if (!$this->getRequest()->isAjax()) {
            $this->_forward('noRoute');
            return;
        }
		
        $result = array();
		$data = $this->getRequest()->getParams();	
		
		$page = Mage::getModel('customcatalog/page')->load((int)$data['page_id']);
				
		if(!strlen($data['skus'])) {
			$this->_getSession()->addSuccess(Mage::helper('customcatalog')->__('You did not enter any SKUs.'));
			$this->_initLayoutMessages('adminhtml/session');
			$result['messages']  = $this->getLayout()->getMessagesBlock()->getGroupedHtml();
		} else {
				try {		
				$productIds = Mage::getModel('customcatalog/page_products')->savePageProductsRelationFromSkus($page, $data['skus']);				
				
				$this->_getSession()->addSuccess(Mage::helper('customcatalog')->__('Product collection has been generated.'));
				$this->_initLayoutMessages('adminhtml/session');
				$result['messages']  = $this->getLayout()->getMessagesBlock()->getGroupedHtml();
				$result['ids']  = $productIds;
					
			} catch (Exception $e) {
				$result['error'] = Mage::helper('customcatalog')->__('An error occurred while generating the product collection. Please review the log and try again.');
				Mage::logException($e);
			}
		}
        
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}