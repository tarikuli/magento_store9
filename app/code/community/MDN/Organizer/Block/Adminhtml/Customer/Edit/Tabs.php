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
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Order view tabs
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class MDN_Organizer_Block_Adminhtml_Customer_Edit_Tabs extends Mage_Adminhtml_Block_Customer_Edit_Tabs
{
   
    protected function _beforeToHtml()
    {   	
    	parent::_beforeToHtml();

    	
		//Rajoute l'onglet pour les tasks
        if (Mage::registry('current_customer')->getId()) {  
        	
        	$TaskCount = 0;
	    	$gridBlock = $this->getLayout()
	    				->createBlock('Organizer/Task_Grid')
	    				->setEntityType('customer')
	    				->setEntityId($this->getCustomer()->getId())
	    				->setShowTarget(false)
	    				->setShowEntity(false)
	    				->setTemplate('Organizer/Task/List.phtml');
	    				
    		$content = $gridBlock->toHtml();
    		
    		$TaskCount = $gridBlock->getCollection()->getSize();
            $this->addTab('customer_organizer', array(
                'label'     => Mage::helper('Organizer')->__('Organizer').' ('.$TaskCount.')',
                'title'     => Mage::helper('Organizer')->__('Organizer').' ('.$TaskCount.')',
                'content'   => $content,
            ));
        }

        return parent::_beforeToHtml();
    }
}