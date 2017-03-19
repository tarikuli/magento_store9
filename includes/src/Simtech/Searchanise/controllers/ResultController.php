<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/
class Simtech_Searchanise_ResultController extends Mage_Core_Controller_Front_Action {
	/**
	 * Retrieve catalog session
	 *
	 * @return Mage_Catalog_Model_Session
	 */
	protected function _getSession() {
		return Mage::getSingleton('catalog/session');
	}

	public function indexAction() {
		/*$this->loadLayout();
        $this->renderLayout();*/

		$query = Mage::helper('catalogsearch')->getQuery();
		/* @var $query Mage_CatalogSearch_Model_Query */

		$query->setStoreId(Mage::app()->getStore()->getId());

		if ($query->getQueryText() != '') {
			if (Mage::helper('catalogsearch')->isMinQueryLength()) {
				$query->setId(0)
					->setIsActive(1)
					->setIsProcessed(1);
			} else {
				if ($query->getId()) {
					$query->setPopularity($query->getPopularity() + 1);
				} else {
					$query->setPopularity(1);
				}

				if ($query->getRedirect()) {
					$query->save();
					$this->getResponse()->setRedirect($query->getRedirect());
					return;
				} else {
					$query->prepare();
				}
			}

			Mage::helper('catalogsearch')->checkNotes();

			$this->loadLayout();
			$this->_initLayoutMessages('catalog/session');
			$this->_initLayoutMessages('checkout/session');
			$this->renderLayout();

			if (!Mage::helper('catalogsearch')->isMinQueryLength()) {
				$query->save();
			}
		} else {
			$this->_redirectReferer();
		}
	}
}
