<?php

/*
 * Web POS by Magestore.com
 * Version 2.3
 * Updated by Daniel - 12/2015
 */

class Magestore_Webpos_Block_Payment_Method_Multipayment_Info_Multipayment extends Mage_Payment_Block_Info {
    /*
      This block will show the payment method information
     */

    protected function _prepareSpecificInformation($transport = null) {
        if (null !== $this->_paymentSpecificInformation) {
            return $this->_paymentSpecificInformation;
        }
        $data = array();
        $orderCurrencyCode = $this->getOrderData('order_currency_code');
        $currentCurrencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
        if ($this->getInfo()->getData('cashforpos_ref_no')) {
            $amount = Mage::helper('directory')->currencyConvert($this->getInfo()->getData('cashforpos_ref_no'), $orderCurrencyCode, $currentCurrencyCode);
            $data[Mage::helper('webpos/payment')->getCashMethodTitle()] = Mage::helper('core')->formatPrice($amount, false);
        }
        if ($this->getInfo()->getData('ccforpos_ref_no')) {
            $amount = Mage::helper('directory')->currencyConvert($this->getInfo()->getData('ccforpos_ref_no'), $orderCurrencyCode, $currentCurrencyCode);
            $data[Mage::helper('webpos/payment')->getCcMethodTitle()] = Mage::helper('core')->formatPrice($amount, false);
        }
        if ($this->getInfo()->getData('cp1forpos_ref_no')) {
            $amount = Mage::helper('directory')->currencyConvert($this->getInfo()->getData('cp1forpos_ref_no'), $orderCurrencyCode, $currentCurrencyCode);
            $data[Mage::helper('webpos/payment')->getCp1MethodTitle()] = Mage::helper('core')->formatPrice($amount, false);
        }
        if ($this->getInfo()->getData('cp2forpos_ref_no')) {
            $amount = Mage::helper('directory')->currencyConvert($this->getInfo()->getData('cp2forpos_ref_no'), $orderCurrencyCode, $currentCurrencyCode);
            $data[Mage::helper('webpos/payment')->getCp2MethodTitle()] = Mage::helper('core')->formatPrice($amount, false);
        }
        if ($this->getInfo()->getData('codforpos_ref_no')) {
            $amount = Mage::helper('directory')->currencyConvert($this->getInfo()->getData('codforpos_ref_no'), $orderCurrencyCode, $currentCurrencyCode);
            $data[Mage::helper('webpos/payment')->getCodMethodTitle()] = Mage::helper('core')->formatPrice($amount, false);
        }

        $transport = parent::_prepareSpecificInformation($transport);
        return $transport->setData(array_merge($data, $transport->getData()));
    }

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('webpos/webpos/payment/method/info/multipaymentforpos.phtml');
    }

    protected function getOrderData($key) {
        $data = false;
        $order = $this->getInfo()->getOrder();
        if($order){
            $data = $order->getData($key);
        }
        return $data;
    }

    public function getMethodTitle() {
        return Mage::helper('webpos/payment')->getMultipaymentMethodTitle();
    }

}
