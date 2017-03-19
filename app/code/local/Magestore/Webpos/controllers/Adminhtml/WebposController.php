<?php

/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_Webpos
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Webpos Adminhtml Controller
 * 
 * @category    Magestore
 * @package     Magestore_Webpos
 * @author      Magestore Developer
 */
class Magestore_Webpos_Adminhtml_WebposController extends Mage_Adminhtml_Controller_Action {

    /**
     * index action
     */
    public function indexAction() {
        if (!Mage::helper('magenotification')->checkLicenseKeyAdminController($this)) {
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function gotowebposAction() {
        if (!Mage::helper('magenotification')->checkLicenseKeyAdminController($this)) {
            return;
        }
        $storeId = $this->getRequest()->getParam('webpos_storeid');
        $urlRedirect = Mage::getModel('core/store')->load($storeId)->getUrl('webpos', array('_secure' => true));
        $websiteId = $this->getRequest()->getParam('webpos_websiteid');
        $useStoreCode = Mage::getStoreConfig('web/url/use_store', $storeId);
        foreach ($this->getWebsiteCollection() as $_website):
            foreach ($this->getGroupCollection($_website) as $_group):
                foreach ($this->getStoreCollection($_group) as $_store):
                    if ($storeId == $_store->getId()) {
                        if ($useStoreCode == true)
                            $urlRedirect = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . $_store->getCode() . '/webpos/index/index';
                        else
                            $urlRedirect = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/webpos/index/index';
                        break;
                    }
                endforeach;
            endforeach;
        endforeach;

        header('Location:' . $urlRedirect);
        exit();
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('sales/webpos/gotopos');
    }

    public function getWebsiteCollection() {
        $collection = Mage::getModel('core/website')->getResourceCollection();
        $coreBlock = new Mage_Core_Block_Template();
        $websiteIds = $coreBlock->getWebsiteIds();
        if (!is_null($websiteIds)) {
            $collection->addIdFilter($coreBlock->getWebsiteIds());
        }

        return $collection->load();
    }

    public function getGroupCollection($website) {
        if (!$website instanceof Mage_Core_Model_Website) {
            $website = Mage::getModel('core/website')->load($website);
        }
        return $website->getGroupCollection();
    }

    public function getStoreCollection($group) {
        if (!$group instanceof Mage_Core_Model_Store_Group) {
            $group = Mage::getModel('core/store_group')->load($group);
        }
        $coreBlock = new Mage_Core_Block_Template();
        $stores = $group->getStoreCollection();
        $_storeIds = $coreBlock->getStoreIds();
        if (!empty($_storeIds)) {
            $stores->addIdFilter($_storeIds);
        }
        return $stores;
    }

}
