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
 * @package     Mage_Core
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Base html block
 *
 * @category   Mage
 * @package    Mage_Core
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Core_Block_Template extends Mage_Core_Block_Abstract {
	const XML_PATH_DEBUG_TEMPLATE_HINTS = 'dev/debug/template_hints';
	const XML_PATH_DEBUG_TEMPLATE_HINTS_BLOCKS = 'dev/debug/template_hints_blocks';
	const XML_PATH_TEMPLATE_ALLOW_SYMLINK = 'dev/template/allow_symlink';

	/**
	 * View scripts directory
	 *
	 * @var string
	 */
	protected $_viewDir = '';

	/**
	 * Assigned variables for view
	 *
	 * @var array
	 */
	protected $_viewVars = array();

	protected $_baseUrl;

	protected $_jsUrl;

	/**
	 * Is allowed symlinks flag
	 *
	 * @var bool
	 */
	protected $_allowSymlinks = null;

	protected static $_showTemplateHints;
	protected static $_showTemplateHintsBlocks;

	/**
	 * Path to template file in theme.
	 *
	 * @var string
	 */
	protected $_template;

	/**
	 * Internal constructor, that is called from real constructor
	 *
	 */
	protected function _construct() {
		parent::_construct();

		/*
			         * In case template was passed through constructor
			         * we assign it to block's property _template
			         * Mainly for those cases when block created
			         * not via Mage_Core_Model_Layout::addBlock()
		*/
		if ($this->hasData('template')) {
			$this->setTemplate($this->getData('template'));
		}
	}

	/**
	 * Get relevant path to template
	 *
	 * @return string
	 */
	public function getTemplate() {
		return $this->_template;
	}

	/**
	 * Set path to template used for generating block's output.
	 *
	 * @param string $template
	 * @return Mage_Core_Block_Template
	 */
	public function setTemplate($template) {
		$this->_template = $template;
		return $this;
	}

	/**
	 * Get absolute path to template
	 *
	 * @return string
	 */
	public function getTemplateFile() {
		$params = array('_relative' => true);
		$area = $this->getArea();
		if ($area) {
			$params['_area'] = $area;
		}
		$templateName = Mage::getDesign()->getTemplateFilename($this->getTemplate(), $params);
		return $templateName;
	}

	/**
	 * Get design area
	 * @return string
	 */
	public function getArea() {
		return $this->_getData('area');
	}

	/**
	 * Assign variable
	 *
	 * @param   string|array $key
	 * @param   mixed $value
	 * @return  Mage_Core_Block_Template
	 */
	public function assign($key, $value = null) {
		if (is_array($key)) {
			foreach ($key as $k => $v) {
				$this->assign($k, $v);
			}
		} else {
			$this->_viewVars[$key] = $value;
		}
		return $this;
	}

	/**
	 * Set template location directory
	 *
	 * @param string $dir
	 * @return Mage_Core_Block_Template
	 */
	public function setScriptPath($dir) {
		$scriptPath = realpath($dir);
		if (strpos($scriptPath, realpath(Mage::getBaseDir('design'))) === 0 || $this->_getAllowSymlinks()) {
			$this->_viewDir = $dir;
		} else {
			Mage::log('Not valid script path:' . $dir, Zend_Log::CRIT, null, null, true);
		}
		return $this;
	}

	/**
	 * Check if direct output is allowed for block
	 *
	 * @return bool
	 */
	public function getDirectOutput() {
		if ($this->getLayout()) {
			return $this->getLayout()->getDirectOutput();
		}
		return false;
	}

	public function getShowTemplateHints() {
		if (is_null(self::$_showTemplateHints)) {
			self::$_showTemplateHints = Mage::getStoreConfig(self::XML_PATH_DEBUG_TEMPLATE_HINTS)
			&& Mage::helper('core')->isDevAllowed();
			self::$_showTemplateHintsBlocks = Mage::getStoreConfig(self::XML_PATH_DEBUG_TEMPLATE_HINTS_BLOCKS)
			&& Mage::helper('core')->isDevAllowed();
		}
		return self::$_showTemplateHints;
	}

	/**
	 * Retrieve block view from file (template)
	 *
	 * @param   string $fileName
	 * @return  string
	 */
	public function fetchView($fileName) {
		Varien_Profiler::start($fileName);

		// EXTR_SKIP protects from overriding
		// already defined variables
		extract($this->_viewVars, EXTR_SKIP);
		$do = $this->getDirectOutput();

		if (!$do) {
			ob_start();
		}
		if ($this->getShowTemplateHints()) {
			echo <<<HTML
<div style="position:relative; border:1px dotted red; margin:6px 2px; padding:18px 2px 2px 2px; zoom:1;">
<div style="position:absolute; left:0; top:0; padding:2px 5px; background:red; color:white; font:normal 11px Arial;
text-align:left !important; z-index:998;" onmouseover="this.style.zIndex='999'"
onmouseout="this.style.zIndex='998'" title="{$fileName}">{$fileName}</div>
HTML;
			if (self::$_showTemplateHintsBlocks) {
				$thisClass = get_class($this);
				echo <<<HTML
<div style="position:absolute; right:0; top:0; padding:2px 5px; background:red; color:blue; font:normal 11px Arial;
text-align:left !important; z-index:998;" onmouseover="this.style.zIndex='999'" onmouseout="this.style.zIndex='998'"
title="{$thisClass}">{$thisClass}</div>
HTML;
			}
		}

		try {
			$includeFilePath = realpath($this->_viewDir . DS . $fileName);
			if (strpos($includeFilePath, realpath($this->_viewDir)) === 0 || $this->_getAllowSymlinks()) {
				include $includeFilePath;
			} else {
				Mage::log('Not valid template file:' . $fileName, Zend_Log::CRIT, null, null, true);
			}

		} catch (Exception $e) {
			ob_get_clean();
			throw $e;
		}

		if ($this->getShowTemplateHints()) {
			echo '</div>';
		}

		if (!$do) {
			$html = ob_get_clean();
		} else {
			$html = '';
		}
		Varien_Profiler::stop($fileName);
		return $html;
	}

	/**
	 * Render block
	 *
	 * @return string
	 */
	public function renderView() {
		$this->setScriptPath(Mage::getBaseDir('design'));
		$html = $this->fetchView($this->getTemplateFile());
		return $html;
	}

	/**
	 * Render block HTML
	 *
	 * @return string
	 */
	protected function _toHtml() {
		if (!$this->getTemplate()) {
			return '';
		}
		$html = $this->renderView();
		return $html;
	}

	/**
	 * Get base url of the application
	 *
	 * @return string
	 */
	public function getBaseUrl() {
		if (!$this->_baseUrl) {
			$this->_baseUrl = Mage::getBaseUrl();
		}
		return $this->_baseUrl;
	}

	/**
	 * Get url of base javascript file
	 *
	 * To get url of skin javascript file use getSkinUrl()
	 *
	 * @param string $fileName
	 * @return string
	 */
	public function getJsUrl($fileName = '') {
		if (!$this->_jsUrl) {
			$this->_jsUrl = Mage::getBaseUrl('js');
		}
		return $this->_jsUrl . $fileName;
	}

	/**
	 * Get data from specified object
	 *
	 * @param Varien_Object $object
	 * @param string $key
	 * @return mixed
	 */
	public function getObjectData(Varien_Object $object, $key) {
		return $object->getDataUsingMethod((string) $key);
	}

	/**
	 * Get cache key informative items
	 *
	 * @return array
	 */
	public function getCacheKeyInfo() {
		return array(
			'BLOCK_TPL',
			Mage::app()->getStore()->getCode(),
			$this->getTemplateFile(),
			'template' => $this->getTemplate(),
		);
	}

	/**
	 * Get is allowed symliks flag
	 *
	 * @return bool
	 */
	protected function _getAllowSymlinks() {
		if (is_null($this->_allowSymlinks)) {
			$this->_allowSymlinks = Mage::getStoreConfigFlag(self::XML_PATH_TEMPLATE_ALLOW_SYMLINK);
		}
		return $this->_allowSymlinks;
	}

	public function getAttributeValues($attr = "", $is_option = "true") {
		$allValues = array();
		$collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect($attr)->groupByAttribute($attr);

		if ($is_option == "true") {
			$attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $attr);
			if ($attribute->usesSource()) {
				$options = $attribute->getSource()->getAllOptions(true);
			}
		}

		foreach ($collection as $_product) {
			$data = $_product->getData();
			if ($is_option == "true") {
				$allValues[] = $this->getOptionText($options, $data[$attr]);
			} else {
				$allValues[] = array('label' => $data[$attr], 'value' => $data[$attr]);
			}
		}
		return $allValues;
	}

	public function getMenuSubCategories($parentId) {
		$data = array();
		$cacheId = 'main_submenu_category_' . $parentId;
		if (false !== ($cach_category = Mage::app()->getCache()->load($cacheId))) {
			$data = unserialize($cach_category);
		} else {
			$children = Mage::getModel('catalog/category')->getCategories($parentId);
			foreach ($children as $cat) {
				$cat = Mage::getModel('catalog/category')->load($cat->getId());
				$category = array(
					'id' => $cat->getId(),
					'url' => $cat->getUrl(),
					'name' => $cat->getName(),
					'image' => $cat->getImageUrl(),
					'isActive' => $cat->getIsActive(),
					'level' => $cat->getLevel(),
					'parentId' => $cat->getParentId(),
					'urlkey' => $cat->getUrlKey(),
					'title' => ($cat->getInternalTitle() ? $cat->getInternalTitle() : $cat->getName()),
					'includeInMenu' => $cat->getIncludeInMenu(),
				);
				array_push($data, $category);
			}
			Mage::app()->getCache()->save(serialize($data), $cacheId);
		}
		return $data;
	}

	public function getMenuSubCategoriesByKey($urlkey) {
		$data = array();
		foreach ($this->getAllCategories() as $key => $cat) {
			if ($cat['category']['urlkey'] == $urlkey) {
				$data = $cat['items'];
				break;
			}
		}
		return $data;
	}

	public function __getAllCategories() {
		$categories = Mage::getModel('catalog/category');
		$treeModel = $categories->getTreeModel();
		$treeModel->load();

		$ids = $treeModel->getCollection()->getAllIds();
		$data = array();
		$keys = array();
		if (!empty($ids)) {
			$count = 0;
			foreach ($ids as $id) {
				if ($count == 0) {
					$count++;
					continue;
				}

				$cat = Mage::getModel('catalog/category')->load($id);
				$category = array(
					'id' => $cat->getId(),
					'url' => $cat->getUrl(),
					'name' => $cat->getName(),
					'image' => $cat->getImageUrl(),
					'isActive' => $cat->getIsActive(),
					'level' => $cat->getLevel(),
					'parentId' => $cat->getParentId(),
					'urlkey' => $cat->getUrlKey(),
					'title' => ($cat->getInternalTitle() ? $cat->getInternalTitle() : $cat->getName()),
					'includeInMenu' => $cat->getIncludeInMenu(),
				);
				if ($category['level'] == 2) {
					$data[$category['id']]['category'] = $category;
					$data[$category['id']]['items'] = array();
				}

				if ($category['level'] == 3) {
					array_push($data[$category['parentId']]['items'], $category);
				}
				$count++;
			}
		}

		return $data;
	}

	public function getAllCategories() {
		$data = array();
		$cacheId = 'main_menu_category';
		if (false !== ($cach_category = Mage::app()->getCache()->load($cacheId))) {
			$data = unserialize($cach_category);
		} else {
			$categories = $this->getMenuSubCategories(Mage::app()->getStore()->getRootCategoryId());
			foreach ($categories as $key => $category) {
				$data[$category['id']]['category'] = $category;
				$data[$category['id']]['items'] = $this->getMenuSubCategories($category['id']);
			}
			Mage::app()->getCache()->save(serialize($data), $cacheId);
		}
		return $data;
	}

	public function getSearchCategory($make_var) {
		 $categories = $this->getAllCategories();
		 foreach( $categories as $key => $cat) {
		 	if($make_var === $cat['category']['urlkey']){
		 		return $cat;
		 	}
		 }
		 return null;
	}

	public function getDataGroupAtrributes($groupName = "") {
		$attributeSetCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')->load();
		$attributeSet = null;
		foreach ($attributeSetCollection as $id => $attributeGroup) {
			if (strtolower($attributeGroup->getAttributeGroupName()) == strtolower($groupName)) {
				$attributeSet = $attributeGroup;
				break;
			}
		}
		$attrs = array('sku');
		if ($attributeSet) {
			$attributes = Mage::getResourceModel('catalog/product_attribute_collection')
				->setAttributeGroupFilter($attributeSet->getId())
				->addVisibleFilter()
				->checkConfigurableProducts()
				->load();

			foreach ($attributes->getItems() as $key => $attribute) {
				$attrCode = $attribute->getAttributeCode();
				if (!in_array($attrCode, array('description_works', 'replaces_key_types','display_fcc_model'))) {
					array_push($attrs, $attrCode);
				}
			}
		}
		$middle = ceil(count($attrs) / 2);
		$left = array_slice($attrs, 0, $middle);
		$right = array_slice($attrs, $middle);

		return array('left' => $left, 'right' => $right);
	} 
	public function getDescriptionDataAtrributes() {
		return array('description_works', 'replaces_key_types');
	}

	public function getAdditionalAttributes($_product){
        $specs = $this->getDataGroupAtrributes("Specification"); 
        $infos = array_diff(array_merge($specs['right'], $specs['left']),array('sku')); 
        $attrs = array(); 
        foreach ($infos as $info) { 
        	if($value = $_product->getData($info)){
	            array_push( $attrs,
	                 [$_product->getResource()->getAttribute($info)->getFrontendLabel(),$value]
	            );
	        }
        } 
        return $attrs;
    }

    public function getOutOfStockText(){
        return Mage::getModel('core/variable')->loadByCode('outofstock')->getValue('plain');
    }

}
