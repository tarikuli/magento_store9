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
 * @package     Mage_Review
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Detailed Product Reviews
 *
 * @category   Mage
 * @package    Mage_Review
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Catalog_Block_Product_List_Review extends Mage_Catalog_Block_Product_List {

	public function getReviews() {
		$_reviews = Mage::getModel('review/review')
			->getResourceCollection()
			->addStoreFilter(Mage::app()->getStore()->getId())
			->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
			->setDateOrder()
			->addRateVotes()
			->setPageSize(10)
			->setCurPage(1);
		return $_reviews;
	}

	public function getAverageRating(Mage_Review_Model_Review $review) {
		$avg = 0;
		if (count($review->getRatingVotes())) {
			$ratings = array();
			foreach ($review->getRatingVotes() as $rating) {
				$ratings[] = $rating->getPercent();
			}
			$avg = array_sum($ratings) / count($ratings);
		}
		return $avg;
	}

	public function getBackgroundImage($image = "") {
		$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
		$prefix = Mage::getConfig()->getTablePrefix();
		$tblname = $prefix . 'mksresponsivebannerslider';
		$sql = $connection->query("select * from $tblname where status='0' and groupname='testimonial_banner' ORDER BY imageorder ASC Limit 1");
		$row1 = $sql->fetch();
		$bg = array('image_url' => $image);
		if ($row1) {
			$urlx = $row1["image"];
			$media_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
			$imgurl = $media_url . $urlx;
			$url = empty($row1["url"]) ? "#" : $row1["url"];

			$bg = array(
				'title' => $row1["title"],
				'description' => $row1["description"],
				'image_url' => $imgurl,
				'url' => $url,
			);
		}
		return $bg;
	}
}
