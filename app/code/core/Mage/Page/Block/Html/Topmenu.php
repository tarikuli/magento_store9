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
 * @package     Mage_Page
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Top menu block
 *
 * @category    Mage
 * @package     Mage_Page
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Page_Block_Html_Topmenu extends Mage_Core_Block_Template {
	/**
	 * Top menu data tree
	 *
	 * @var Varien_Data_Tree_Node
	 */
	protected $_menu;

	/**
	 * Current entity key
	 *
	 * @var string|int
	 */
	protected $_currentEntityKey;

	/**
	 * Init top menu tree structure and cache
	 */
	public function _construct() {
		$this->_menu = new Varien_Data_Tree_Node(array(), 'root', new Varien_Data_Tree());
		/*
			        * setting cache to save the topmenu block
		*/
		$this->setCacheTags(array(Mage_Catalog_Model_Category::CACHE_TAG));
		$this->setCacheLifetime(false);
	}

	/**
	 * Get top menu html
	 *
	 * @param string $outermostClass
	 * @param string $childrenWrapClass
	 * @return string
	 */
	public function getHtml($outermostClass = '', $childrenWrapClass = '') {
		Mage::dispatchEvent('page_block_html_topmenu_gethtml_before', array(
			'menu' => $this->_menu,
			'block' => $this,
		));

		$this->_menu->setOutermostClass($outermostClass);
		$this->_menu->setChildrenWrapClass($childrenWrapClass);

		if ($renderer = $this->getChild('catalog.topnav.renderer')) {
			$renderer->setMenuTree($this->_menu)->setChildrenWrapClass($childrenWrapClass);
			$html = $renderer->toHtml();
		} else {
			$html = $this->_getHtml($this->_menu, $childrenWrapClass);
		}

		Mage::dispatchEvent('page_block_html_topmenu_gethtml_after', array(
			'menu' => $this->_menu,
			'html' => $html,
		));

		return $html;
	}

	protected function getOptionText($options = array(), $option_id) {
		foreach ($options as $key => $option) {
			if ($option['value'] == $option_id) {
				return $option;
			}
		}
		return null;
	}

	public function getMenuHtml($attr = "", $is_option = "true", $selector = "", $sideimage = null, $title = null) {
		$items = $this->getAttributeValues($attr, $is_option);
		$total = count($items);
		$divider = 10;
		$col = 6;
		$imgcol = 6;
		$width = 400;
		if ($total <= 10) {
			$divider = 10;
			$col = 6;
			$imgcol = 6;
			$width = 400;
		} else if ($total <= 20) {
			$divider = $total / 2;
			$col = 4;
			$imgcol = 4;
			$width = 400;
		} else if ($total <= 30) {
			$divider = ($total / 3);
			$col = 3;
			$imgcol = 3;
			$width = 450;
		} else if ($total <= 60) {
			$divider = ($total / 4);
			$col = 3;
			$imgcol = 3;
			$width = 600;
		} else {
			$divider = ($total / 6);
			$col = 2;
			$imgcol = 3;
			$width = 800;
		}

		$count = 1;
		$columns = 0;
		$html = '<ul style="min-width:' . $width . 'px;" class="dropdown-menu ' . $selector . '"><div class="row">';
		foreach ($items as $key => $item) {
			if ($count == 1) {
				$columns++;
				$html .= '<div class="matchHeight col-menu-items col-md-' . $col . '"><div class="dropdown-content"><ul>';
				if ($key == 0 && $title) {
					$html .= '<li class="menu-title"><h4>' . $title . '</h4></li>';
				}
			}
			$url = Mage::getBaseUrl() . 'catalogsearch/advanced/result?' . $attr . '=' . $item['value'];

			$html .= '<li> <a href="' . $url . '">' . $item['label'] . '</a></li>';

			if ($count == $divider || ($key + 1) == $total) {
				$html .= "</ul></div></div>";
				$count = 1;
			}
			$count++;
		}
		$html .= '</div></ul>';
		return array('html' => $html, 'items' => $items);
	}

	public function getSubMenuHtml($category, $items) {
		$total = count($items);
		$divider = 10;
		$col = 6;
		$imgcol = 6;
		$width = 400;
		if ($total <= 10) {
			$divider = 10;
			$col = 6;
			$imgcol = 6;
			$width = 400;
		} else if ($total <= 20) {
			$divider = round($total / 2);
			$col = 6;
			$imgcol = 6;
			$width = 400;
		} else if ($total <= 40) {
			$divider = round($total / 3);
			$col = 4;
			$imgcol = 4;
			$width = 450;
		} else if ($total <= 60) {
			$divider = round($total / 4);
			$col = 3;
			$imgcol = 3;
			$width = 650;
		} else if ($total <= 80) {
			$divider = round($total / 4);
			$col = 3;
			$imgcol = 3;
			$width = 650;
		} else {
			$divider = round($total / 6);
			$col = 2;
			$imgcol = 3;
			$width = 700;
		}

		$count = 1;
		$columns = 0;
		$html = '<ul style="min-width:' . $width . 'px;" class="dropdown-menu dropdown-submenu ' . $category['urlkey'] . '"><div class="row">';
		foreach ($items as $key => $item) {
			if ($count == 1) {
				$columns++;
				$html .= '<div class="col-submenu-items col-md-' . $col . '"><div class="dropdown-content"><ul>';
			}

			$html .= '<li class="search-link submenu-link"> <a title="" href="' . $item['url'] . '">' . $item['name'] . '</a>';

			$html .= '</li>';
			if ($count == $divider || ($key + 1) == $total) {
				$html .= "</ul></div></div>";
				$count = 0;
			}

			$count++;
		}

		$html .= '</div></ul>';
		return $html;
	}

	public function getCategoryMenuHtml($category, $items) {
		$total = count($items);
		$divider = 10;
		$col = 6;
		$imgcol = 6;
		$width = 400;
		if ($total <= 10) {
			$divider = 10;
			$col = 6;
			$imgcol = 6;
			$width = 400;
		} else if ($total <= 20) {
			$divider = round($total / 2);
			$col = 4;
			$imgcol = 4;
			$width = 500;
		} else if ($total <= 40) {
			$remainder = ($total % 4);
			$divider = round($total / 3);
			$col = 4;
			$imgcol = 4;
			$width = 550;
		} else if ($total <= 60) {
			$divider = round($total / 4);
			$col = 3;
			$imgcol = 3;
			$width = 600;
		} else if ($total <= 80) {
			$divider = round($total / 4);
			$col = 3;
			$imgcol = 3;
			$width = 600;
		} else {
			$divider = round($total / 6);
			$col = 2;
			$imgcol = 3;
			$width = 700;
		}

		$count = 1;
		$columns = 0;
		$html = '<ul style="min-width:' . $width . 'px;" class="dropdown-menu ' . $category['urlkey'] . '"><div class="row" >';
		foreach ($items as $key => $item) {
			if ($count == 1) {
				$columns++;
				$html .= '<div class=" col-menu-items col-md-' . $col . '"><div class="dropdown-content"><ul>';
				if ($key == 0 && $category['name']) {
					//$html .= '<li class="menu-title"><h4>' . $category['name'] . '</h4></li>';
				}
			}

			$html .= '<li class="search-link menu-item"> <a title="" href="' . $item['url'] . '">' . $item['name'] . '</a>';

			//check for subcategory
			$children = $this->getMenuSubCategories($item['id']);
			if ($children && count($children) > 0) {
				$html .= $this->getSubMenuHtml($item, $children);
			}
			$html .= '</li>';

			if ($count == $divider || ($key + 1) == $total) {
				$html .= "</ul></div></div>";
				$count = 0;
			}

			$count++;
		}

		$sideimage = $category['image'];
		if ($columns < 3 && $sideimage && !$is_sub) {
			$html .= '<div class=" col-menu-items col-image col-md-' . $imgcol . '"><a title="' . $category['title'] . '" href="' . $category['url'] . '"><img src="' . $sideimage . '" class="img-responsive" alt="usmap"></a></div>';
		}

		$html .= '</div></ul>';
		return $html;
	}

	public function getMenuImage($attr) {
		try {
			$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
			$prefix = Mage::getConfig()->getTablePrefix();
			$tblname = $prefix . 'mksresponsivebannerslider';
			$sql = $connection->query("select * from $tblname where status='0' and title ='" . $attr . "' and groupname='menu_banner' ORDER BY imageorder ASC ");
			$row1 = $sql->fetch();
			$title = $row1["title"];
			$description = $row1["description"];
			$urlx = $row1["image"];
			$media_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
			$imgurl = $media_url . $urlx;
			$url = empty($row1["url"]) ? "#" : $row1["url"];
			return array('image' => $imgurl, 'url' => $url);
		} catch (Exception $e) {
		}
		return null;
	}

	/**
	 * Recursively generates top menu html from data that is specified in $menuTree
	 *
	 * @param Varien_Data_Tree_Node $menuTree
	 * @param string $childrenWrapClass
	 * @return string
	 * @deprecated since 1.8.2.0 use child block catalog.topnav.renderer instead
	 */
	protected function _getHtml(Varien_Data_Tree_Node $menuTree, $childrenWrapClass) {
		$html = '';

		$children = $menuTree->getChildren();
		$parentLevel = $menuTree->getLevel();
		$childLevel = is_null($parentLevel) ? 0 : $parentLevel + 1;

		$counter = 1;
		$childrenCount = $children->count();

		$parentPositionClass = $menuTree->getPositionClass();
		$itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

		foreach ($children as $child) {

			$child->setLevel($childLevel);
			$child->setIsFirst($counter == 1);
			$child->setIsLast($counter == $childrenCount);
			$child->setPositionClass($itemPositionClassPrefix . $counter);

			$outermostClassCode = '';
			$outermostClass = $menuTree->getOutermostClass();

			if ($childLevel == 0 && $outermostClass) {
				$outermostClassCode = ' class="' . $outermostClass . '" ';
				$child->setClass($outermostClass);
			}

			$html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
			$html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>'
			. $this->escapeHtml($child->getName()) . '</span></a>';

			if ($child->hasChildren()) {
				if (!empty($childrenWrapClass)) {
					$html .= '<div class="' . $childrenWrapClass . '">';
				}
				$html .= '<ul class="level' . $childLevel . '">';
				$html .= $this->_getHtml($child, $childrenWrapClass);
				$html .= '</ul>';

				if (!empty($childrenWrapClass)) {
					$html .= '</div>';
				}
			}
			$html .= '</li>';

			$counter++;
		}

		return $html;
	}

	/**
	 * Generates string with all attributes that should be present in menu item element
	 *
	 * @param Varien_Data_Tree_Node $item
	 * @return string
	 */
	protected function _getRenderedMenuItemAttributes(Varien_Data_Tree_Node $item) {
		$html = '';
		$attributes = $this->_getMenuItemAttributes($item);

		foreach ($attributes as $attributeName => $attributeValue) {
			$html .= ' ' . $attributeName . '="' . str_replace('"', '\"', $attributeValue) . '"';
		}

		return $html;
	}

	/**
	 * Returns array of menu item's attributes
	 *
	 * @param Varien_Data_Tree_Node $item
	 * @return array
	 */
	protected function _getMenuItemAttributes(Varien_Data_Tree_Node $item) {
		$menuItemClasses = $this->_getMenuItemClasses($item);
		$attributes = array(
			'class' => implode(' ', $menuItemClasses),
		);

		return $attributes;
	}

	/**
	 * Returns array of menu item's classes
	 *
	 * @param Varien_Data_Tree_Node $item
	 * @return array
	 */
	protected function _getMenuItemClasses(Varien_Data_Tree_Node $item) {
		$classes = array();

		$classes[] = 'level' . $item->getLevel();
		$classes[] = $item->getPositionClass();

		if ($item->getIsFirst()) {
			$classes[] = 'first';
		}

		if ($item->getIsActive()) {
			$classes[] = 'active';
		}

		if ($item->getIsLast()) {
			$classes[] = 'last';
		}

		if ($item->getClass()) {
			$classes[] = $item->getClass();
		}

		if ($item->hasChildren()) {
			$classes[] = 'parent';
		}

		return $classes;
	}

	/**
	 * Retrieve cache key data
	 *
	 * @return array
	 */
	public function getCacheKeyInfo() {
		$shortCacheId = array(
			'TOPMENU',
			Mage::app()->getStore()->getId(),
			Mage::getDesign()->getPackageName(),
			Mage::getDesign()->getTheme('template'),
			Mage::getSingleton('customer/session')->getCustomerGroupId(),
			'template' => $this->getTemplate(),
			'name' => $this->getNameInLayout(),
			$this->getCurrentEntityKey(),
		);
		$cacheId = $shortCacheId;

		$shortCacheId = array_values($shortCacheId);
		$shortCacheId = implode('|', $shortCacheId);
		$shortCacheId = md5($shortCacheId);

		$cacheId['entity_key'] = $this->getCurrentEntityKey();
		$cacheId['short_cache_id'] = $shortCacheId;

		return $cacheId;
	}

	/**
	 * Retrieve current entity key
	 *
	 * @return int|string
	 */
	public function getCurrentEntityKey() {
		if (null === $this->_currentEntityKey) {
			$this->_currentEntityKey = Mage::registry('current_entity_key')
			? Mage::registry('current_entity_key') : Mage::app()->getStore()->getRootCategoryId();
		}
		return $this->_currentEntityKey;
	}
}
