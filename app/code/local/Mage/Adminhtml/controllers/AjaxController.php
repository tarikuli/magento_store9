<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Backend ajax controller
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_AjaxController extends Mage_Adminhtml_Controller_Action {
	/**
	 * Ajax action for inline translation
	 *
	 */
	public function translateAction() {
		$translation = $this->getRequest()->getPost('translate');
		$area = $this->getRequest()->getPost('area');

		//filtering
		/** @var $filter Mage_Core_Model_Input_Filter_MaliciousCode */
		$filter = Mage::getModel('core/input_filter_maliciousCode');
		foreach ($translation as &$item) {
			$item['custom'] = $filter->filter($item['custom']);
		}

		echo Mage::helper('core/translate')->apply($translation, $area);
		exit();
	}

	public function createsubcategoriesAction() {
		$response = array();
		$params = $this->getRequest()->getParams();

		$from = intval($params['from']);
		$to = intval($params['to']);
		$parentId = params['parentId'];
		$parentCategory = Mage::getModel('catalog/category')->load($parentId);
		if ($parentCategory) {
			$count = 0;
			while ($from <= $to) {
				try {
					$name = $from . "";
					$category = Mage::getModel('catalog/category');
					$category->setName($name);
					$category->setUrlKey(strtolower($name));
					$category->setIsActive(1);
					$category->setDisplayMode('PRODUCTS');
					$category->setIsAnchor(1);
					$storeId = Mage::app()->getStore()->getId();
					$category->setStoreId($storeId);
					$category->setPath($parentCategory->getPath());
					$category->save();
					$count++;
				} catch (Exception $e) {
				}
				$from = $from + 1;
			}
		}

		$response['status'] = 'SUCCESS';
		$response['message'] = $count . ' Sub categories created - ' . $parentCategory->getPath();

		echo json_encode($response);
		exit();
	}

	/**
	 * Check is allowed access to action
	 *
	 * @return bool
	 */
	protected function _isAllowed() {
		return true;
	}
}
