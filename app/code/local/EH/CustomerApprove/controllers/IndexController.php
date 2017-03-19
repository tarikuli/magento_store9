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

class EH_CustomerApprove_IndexController extends Mage_Adminhtml_Controller_Action
{
	
	/**
	 * Noroute action
	 */
	public function norouteAction($coreRoute = null)
    {
        $this->_forward('index','adminhtml_page','customerapprove');
    }
        
    
    /**
     * Fixes changeLocale bug
     *
     */
  	public function changeLocaleAction()
  	{
  		$this->_forward('changeLocale', 'index','admin');
  	}
    
  	
  	/**
  	 * Check if a user is allowed here
  	 *
  	 * @return boolean
  	 */
    protected function _isAllowed()
    {
	    return Mage::getSingleton('admin/session')->isAllowed('customer/approve');
    }
	
}
