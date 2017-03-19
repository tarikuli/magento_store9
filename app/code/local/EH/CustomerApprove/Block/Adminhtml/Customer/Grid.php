<?php
/*////////////////////////////////////////////////////////////////////////////////
 \\\\\\\\\\\\\\\\\\\\\\\\\  Customer Approve/Disapprove  \\\\\\\\\\\\\\\\\\\\\\\\\
 /////////////////////////////////////////////////////////////////////////////////
 \\\\\\\\\\\\\\\\\\\\\\\\\ NOTICE OF LICENSE\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 ///////                                                                   ///////
 \\\\\\\ This source file is subject to the Open Software License (OSL 3.0)\\\\\\\
 ///////   that is bundled with this package in the file LICENSE.txt.      ///////
 \\\\\\\   It is also available through the world-wide-web at this URL:    \\\\\\\
 ///////          http://opensource.org/licenses/osl-3.0.php               ///////
 \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 ///////                      * @category   EH                            ///////
 \\\\\\\                      * @package    EH_CustomerApprove            \\\\\\\
 ///////    * @author     ExtensionHut <info@extensionhut.com>             ///////
 \\\\\\\                                                                   \\\\\\\
 /////////////////////////////////////////////////////////////////////////////////
 \\\\\\* @copyright  Copyright 2015 © www.extensionhut.com All right reserved\\\\\
 /////////////////////////////////////////////////////////////////////////////////
 */

class EH_CustomerApprove_Block_Adminhtml_Customer_Grid extends Mage_Adminhtml_Block_Customer_Grid
{
	
    protected function _prepareColumns()
    {
		if(Mage::helper('customerapprove')->getIsEnabled()) {
			// add "Approved" column to customer grid
			$this->addColumnAfter('eh_cc_is_approved', array(
				'header'    => Mage::helper('customerapprove')->__('Approved'),
				'index'     => 'eh_cc_is_approved',
				'type'      => 'options',
				'options'   => Mage::helper('customerapprove')->getApprovalStates()
			), 'website_id');
		}

		// add default grid columns
		return parent::_prepareColumns();
    }

	protected function _prepareMassaction()
    {
		// add default mass actions
		parent::_prepareMassaction();
		if(Mage::helper('customerapprove')->getIsEnabled()) {
			// add mass action "approve"
			$this->getMassactionBlock()->addItem('approve', array(
				 'label'    => Mage::helper('customerapprove')->__('Approve'),
				 'url'      => $this->getUrl('adminhtml/customerapprove_customer/massApprove'),
				 'confirm'  => Mage::helper('customer')->__('Are you sure?')
			));

			// add mass action "disapprove"
			$this->getMassactionBlock()->addItem('disapprove', array(
				 'label'    => Mage::helper('customerapprove')->__('Disapprove'),
				 'url'      => $this->getUrl('adminhtml/customerapprove_customer/massDisapprove'),
				 'confirm'  => Mage::helper('customer')->__('Are you sure?')
			));
		}
        return $this;
    }
	
}
