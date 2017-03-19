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
 * @package     Mage_Catalog
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Catalog product random items block
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */

class Mage_Catalog_Block_Product_List_Newarrivals extends Mage_Catalog_Block_Product_List {
	/**
	 * Default value for products count that will be shown
	 */
	const DEFAULT_PRODUCTS_COUNT = 10;

	/**
	 * Products count
	 *
	 * @var null
	 */
	protected $_productsCount;

	protected function _getProductCollection() {
		if (is_null($this->_productCollection)) {
			$todayStartOfDayDate = Mage::app()->getLocale()->date()
				->setTime('00:00:00')
				->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

			$todayEndOfDayDate = Mage::app()->getLocale()->date()
				->setTime('23:59:59')
				->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

			$collection = Mage::getResourceModel('catalog/product_collection');
			$collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());

			$collection = $this->_addProductAttributesAndPrices($collection)
				->addStoreFilter()
				->addAttributeToFilter('news_from_date', array('or' => array(
					0 => array('date' => true, 'to' => $todayEndOfDayDate),
					1 => array('is' => new Zend_Db_Expr('null'))),
				), 'left')
				->addAttributeToFilter('news_to_date', array('or' => array(
					0 => array('date' => true, 'from' => $todayStartOfDayDate),
					1 => array('is' => new Zend_Db_Expr('null'))),
				), 'left')
				->addAttributeToFilter(
					array(
						array('attribute' => 'news_from_date', 'is' => new Zend_Db_Expr('not null')),
						array('attribute' => 'news_to_date', 'is' => new Zend_Db_Expr('not null')),
					)
				)
				->addAttributeToFilter('newarrival', array('eq' => 1))
				->addAttributeToSort('news_from_date', 'desc')
				->setPageSize($this->getProductsCount())
				->setCurPage(1);

			$this->_productCollection = $collection;
		}
		return $this->_productCollection;
	}

	/**
	 * Set how much product should be displayed at once.
	 *
	 * @param int $count
	 * @return $this
	 */
	public function setProductsCount($count) {
		$this->_productsCount = $count;
		return $this;
	}

	/**
	 * Get how much products should be displayed at once.
	 *
	 * @return int
	 */
	public function getProductsCount() {
		if (null === $this->_productsCount) {
			$this->_productsCount = self::DEFAULT_PRODUCTS_COUNT;
		}
		return $this->_productsCount;
	}
}
