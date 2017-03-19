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

class EH_CustomerApprove_Model_Customer extends Mage_Customer_Model_Customer
{

	/*
	 * Config paths
	 */
	const XML_PATH_APPROVAL_EMAIL_ENABLED 			= 'customerapprove/email/enabled';
	const XML_PATH_APPROVAL_EMAIL_TEMPLATE 			= 'customerapprove/email/template';
	const XML_PATH_APPROVAL_EMAIL_IDENTITY 			= 'customerapprove/email/identity';

	const XML_PATH_ADMIN_NOTIFICATION_ENABLED		= 'customerapprove/admin_notification/enabled';
	const XML_PATH_ADMIN_NOTIFICATION_TEMPLATE		= 'customerapprove/admin_notification/template';
	const XML_PATH_ADMIN_NOTIFICATION_IDENTITY		= 'customerapprove/admin_notification/identity';
	const XML_PATH_ADMIN_NOTIFICATION_RECIPIENTS	= 'customerapprove/admin_notification/recipients';

	const XML_PATH_GENERAL_WELCOME_EMAIL			= 'customerapprove/general/welcome_email';

	/**
	 * Modifies the original function to include a custom made event
	 * dispatcher. This is to ensure that e-mails are sent out upon
	 * account confirmation / creation.
	 *
	 * @param string $type
	 * @param string $backUrl
	 * @param string $storeId
	 * @return Mage_Customer_Model_Customer
	 */
    public function sendNewAccountEmail($type = 'registered', $backUrl = '', $storeId = '0', $customer)
    {
		// whether or not extension is enabled
		$enabled = Mage::getStoreConfig(EH_CustomerApprove_Helper_Data::EH_CC_ENABLED, $storeId);
		
		$customerRestrictions = Mage::helper('customerapprove')->getCustomerGroups($customer->getGroupId());
		
		// first check if we should send out the default welcome e-mail
		$defaultWelcomeEmailEnabled = (intval(Mage::getStoreConfig(self::XML_PATH_GENERAL_WELCOME_EMAIL, $storeId)) == 1) ? true : false;
		
		if (!$enabled || ($enabled && $defaultWelcomeEmailEnabled && $customerRestrictions) || !$customerRestrictions) {
			// run parent function
			$types = array(
            'registered'   => 'customer/create_account/email_template', // welcome email, when confirmation is disabled
            'confirmed'    => 'customer/create_account/email_confirmed_template', // welcome email, when confirmation is enabled
            'confirmation' => 'customer/create_account/email_confirmation_template', // email with confirmation link
			);
			if (!isset($types[$type])) {
				Mage::throwException(Mage::helper('customer')->__('Wrong transactional account email type'));
			}
	
			if (!$storeId) {
				$storeId = $this->_getWebsiteStoreId($this->getSendemailStoreId());
			}

			$this->_sendEmailTemplate($types[$type], 'customer/create_account/email_identity',
			array('customer' => $customer, 'back_url' => $backUrl), $storeId);
			// dispatch custom event
			Mage::dispatchEvent('customer_new_account_email_sent',
				array('customer' => $customer)
			);
		}

		// send out admin notification e-mail
		//$this->sendNewAccountNotificationEmail($storeId);


		return $this;
	}

	protected function _sendEmailTemplate($template, $sender, $templateParams = array(), $storeId = null)
    {
        /** @var $mailer Mage_Core_Model_Email_Template_Mailer */
        $mailer = Mage::getModel('core/email_template_mailer');
        $emailInfo = Mage::getModel('core/email_info');
        $emailInfo->addTo($templateParams['customer']->getEmail(), $templateParams['customer']->getName());
        $mailer->addEmailInfo($emailInfo);

        // Set all required params and send emails
        $mailer->setSender(Mage::getStoreConfig($sender, $storeId));
        $mailer->setStoreId($storeId);
        $mailer->setTemplateId(Mage::getStoreConfig($template, $storeId));
        $mailer->setTemplateParams($templateParams);
        $mailer->send();
        return $this;
    }
    
	/**
     * Send email notification to customer regarding account approval
     *
     * @throws Mage_Core_Exception
     * @return Mage_Customer_Model_Customer
     */
    public function sendAccountApprovalEmail($storeId = '0')
    {
    	// don't send an approval e-mail if the extension is disabled
		if (!Mage::getStoreConfig(EH_CustomerApprove_Helper_Data::EH_CC_ENABLED, $storeId)) {
			return $this;
		}
		
		// make sure approval e-mails are enabled before sending any
		$enabled = (intval(Mage::getStoreConfig(self::XML_PATH_APPROVAL_EMAIL_ENABLED, $storeId)) == 1) ? true : false;

		if ($enabled) {
			$translate = Mage::getSingleton('core/translate');

			/* @var $translate Mage_Core_Model_Translate */
			$translate->setTranslateInline(false);

			if (!$storeId) {
				$storeId = $this->_getWebsiteStoreId($this->getSendemailStoreId());
			}

			Mage::getModel('core/email_template')
				->setDesignConfig(array('area' => 'frontend', 'store' => $storeId))
				->sendTransactional(
					Mage::getStoreConfig(self::XML_PATH_APPROVAL_EMAIL_TEMPLATE, $storeId),
					Mage::getStoreConfig(self::XML_PATH_APPROVAL_EMAIL_IDENTITY, $storeId),
					$this->getEmail(),
					$this->getName(),
					array('customer' => $this));

			$translate->setTranslateInline(true);
		}
		
        return $this;
    }

	/**
	 * Send email notification to customer regarding account approval
	 *
	 * @throws Mage_Core_Exception
	 * @return Mage_Customer_Model_Customer
	 */
	public function sendNewAccountNotificationEmail($storeId = '0', $customer)
	{
		// don't send a notification e-mail if the extension is disabled
		if (!Mage::getStoreConfig(EH_CustomerApprove_Helper_Data::EH_CC_ENABLED, $storeId)) {
			return $this;
		}
		
		if(!Mage::helper('customerapprove')->getCustomerGroups($customer->getGroupId())) {
			return $this;
		}
		
		// make sure notification e-mails are enabled before sending any
		$enabled = (intval(Mage::getStoreConfig(self::XML_PATH_ADMIN_NOTIFICATION_ENABLED, $storeId)) == 1) ? true : false;

		if ($enabled) {
			$translate = Mage::getSingleton('core/translate');

			/* @var $translate Mage_Core_Model_Translate */
			$translate->setTranslateInline(false);

			if (!$storeId) {
				$storeId = $this->_getWebsiteStoreId($this->getSendemailStoreId());
			}

			// set up list of recipients
			$recipients = array();

			// get recipient list from config
			$recipientsConfig = Mage::getStoreConfig(self::XML_PATH_ADMIN_NOTIFICATION_RECIPIENTS, $storeId);

			if (!empty($recipientsConfig)) {
				if (strrpos($recipientsConfig,',') > 0) {
					$recipientArr = explode(',',$recipientsConfig);

					if (count($recipientArr)) {
						$recipients = $recipientArr;
					}
				}
				else if (strrpos($recipientsConfig,'@') > 0) {
					$recipients = array($recipientsConfig);
				}
			}
			// send notification e-mail to each recipient
			if (count($recipients)) {
				foreach ($recipients as $address) {
					Mage::getModel('core/email_template')
						->setDesignConfig(array('area' => 'frontend', 'store' => $storeId))
						->sendTransactional(
						Mage::getStoreConfig(self::XML_PATH_ADMIN_NOTIFICATION_TEMPLATE, $storeId),
						Mage::getStoreConfig(self::XML_PATH_ADMIN_NOTIFICATION_IDENTITY, $storeId),
						$address,
						$customer->getName(),
						array('customer' => $customer));
				}
			}

			$translate->setTranslateInline(true);
		}

		return $this;
	}

}
