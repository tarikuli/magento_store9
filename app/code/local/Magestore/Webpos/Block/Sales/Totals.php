<?php

/*
 * Web POS by Magestore.com
 * Version 2.3
 * Updated by Daniel - 12/2015
 */

class Magestore_Webpos_Block_Sales_Totals extends Mage_Core_Block_Template {

    protected $additionFieldsToDisplay = array();

    public function _construct() {
        $this->setTemplate('webpos/webpos/orderlist/ordertotals.phtml');
    }

    /*
     * Get Subtotal
     */

    public function getSubtotal() {
        $subtotal = Mage::helper('tax')->displaySalesSubtotalExclTax() ? $this->getOrder()->getSubtotal() : $this->getOrder()->getSubtotalInclTax();
        $baseSubtotal = Mage::helper('tax')->displaySalesSubtotalExclTax() ? $this->getOrder()->getBaseSubtotal() : $this->getOrder()->getBaseSubtotalInclTax();
        return $this->convertAndFormatTotal($subtotal,$baseSubtotal);
    }

    /*
     * Get Discount amount
     */

    public function getDiscountDescription() {
        return ($this->getOrder()->getData('discount_description')) ? ($this->getOrder()->getData('discount_description')) : false;
    }

    public function getDiscountAmount() {
        return $this->convertAndFormatTotal(str_replace('-', '', $this->getOrder()->getData('discount_amount')),str_replace('-', '', $this->getOrder()->getData('base_discount_amount')));
    }

    public function getGiftcardDiscount() {
        if (!$this->getOrder()->getGiftVoucherDiscount() || $this->getOrder()->getGiftVoucherDiscount() <= 0) {
            return false;
        }
        return $this->convertAndFormatTotal($this->getOrder()->getGiftVoucherDiscount(),$this->getOrder()->getData('base_gift_voucher_discount'));
    }

    public function getShippingAmount() {
        switch (Mage::getStoreConfig('tax/sales_display/shipping')) {
            case '1': #Exclude
                return $this->getOrder()->formatPrice($this->getOrder()->getShippingAmount());
            case '2': #Include
                return $this->getOrder()->formatPrice($this->getOrder()->getShippingInclTax());
            case '3': #Exclude & Include
                #return $this->getOrder()->formatPrice($this->getOrder()->getShippingAmount()) . " (Incl. Tax {$this->getOrder()->formatPrice($this->getOrder()->getShippingInclTax())})";
                return $this->getOrder()->formatPrice($this->getOrder()->getShippingInclTax());
        }
    }

    public function getTaxesAmount() {
        return $this->convertAndFormatTotal($this->getOrder()->getTaxAmount(),$this->getOrder()->getData('base_tax_amount'));
    }

    public function getStoreCredit() {
        if ($this->getOrder()->getCustomercreditDiscount() != 0) {
            return $this->convertAndFormatTotal($this->getOrder()->getCustomercreditDiscount(),$this->getOrder()->getData('base_customercredit_discount'));
        }
        return false;
    }

    public function getGrandTotal() {
        return $this->convertAndFormatTotal($this->getOrder()->getGrandTotal(),$this->getOrder()->getData('base_grand_total'));
    }

    public function getTotalPaid() {
        if ($this->getOrder()->getStatus() == 'pending')
            return $this->convertAndFormatTotal($this->getOrder()->getTotalPaid(),$this->getOrder()->getData('base_total_paid'));
        return false;
    }

    public function getBalance() {
        if ($this->getOrder()->getStatus() == 'pending') {
			if($this->getOrder()->getPayment()->getMethodInstance()->getCode() == 'multipaymentforpos'){
				$amountPaid = 0;
				if ($this->getOrder()->getData('webpos_base_ccforpos')) {
					$amountPaid += $this->getOrder()->getData('webpos_base_ccforpos');
				}
				if ($this->getOrder()->getData('webpos_base_cp1forpos')) {
					$amountPaid += $this->getOrder()->getData('webpos_base_cp1forpos');
				}
				if ($this->getOrder()->getData('webpos_base_cp2forpos')) {
					$amountPaid += $this->getOrder()->getData('webpos_base_cp2forpos');
				}
				if ($this->getOrder()->getData('webpos_base_cash')) {
					$amountPaid += $this->getOrder()->getData('webpos_base_cash');
				}
				if ($this->getOrder()->getData('webpos_base_codforpos')) {
					$amountPaid += $this->getOrder()->getData('webpos_base_codforpos');
				}
				$balance = $this->getOrder()->getGrandTotal() - $amountPaid;
				return $this->getOrder()->formatPrice($balance);
			}
            return $this->convertAndFormatTotal($this->getOrder()->getTotalDue(),$this->getOrder()->getData('base_total_due'));
        }
        return false;
    }

    public function getWebposCash() {
        if ($this->getOrder()->getWebposCash() > 0) {
            return $this->convertAndFormatTotal($this->getOrder()->getWebposCash(),$this->getOrder()->getData('webpos_base_cash'));
        }
        return false;
    }

    public function getWebposChange() {
        if ($this->getOrder()->getWebposChange() > 0) {
            return $this->convertAndFormatTotal($this->getOrder()->getWebposChange(),$this->getOrder()->getData('webpos_base_change'));
        }
        return false;
    }

    public function getTotalPaidFromTransaction() {
        $orderId = $this->getOrder()->getIncrementId();
        $transaction = $this->loadByField($this->getTransaction(), 'order_id', $orderId);
        $cashIn = 0;
        foreach ($transaction as $tran) {
            $cashIn+= $tran->getData('cash_in');
        }
        return $this->getOrder()->formatPrice($cashIn);
    }

    private function getTransaction() {
        return Mage::getSingleton('webpos/transaction')->getCollection();
    }

    public function loadByField($collection, $field, $value) {
        $collection = $collection
                ->addFieldToFilter($field, array('eq' => $value));

        return $collection;
    }

    public function getRefundedAmount() {
        if (!$this->getOrder()->getTotalRefunded() || $this->getOrder()->getTotalRefunded() <= 0) {
            return false;
        }
        return $this->convertAndFormatTotal($this->getOrder()->getTotalRefunded(),$this->getOrder()->getData('base_total_refunded'));
    }

    public function getWebposCc() {
        if ($this->getOrder()->getData('webpos_ccforpos') > 0) {
            return $this->convertAndFormatTotal($this->getOrder()->getData('webpos_ccforpos'),$this->getOrder()->getData('webpos_base_ccforpos'));
        }
        return false;
    }

    public function getWebposCp1() {
        if ($this->getOrder()->getData('webpos_cp1forpos') > 0) {
            return $this->convertAndFormatTotal($this->getOrder()->getData('webpos_cp1forpos'),$this->getOrder()->getData('webpos_base_cp1forpos'));
        }
        return false;
    }

    public function getWebposCp2() {
        if ($this->getOrder()->getData('webpos_cp2forpos') > 0) {
            return $this->convertAndFormatTotal($this->getOrder()->getData('webpos_cp2forpos'),$this->getOrder()->getData('webpos_base_cp2forpos'));
        }
        return false;
    }

    public function getWebposPaypal() {
        $payment = $this->getOrder()->getPayment()->getMethod();
        if ($payment == 'paypal_direct') {
            return $this->convertAndFormatTotal($this->getOrder()->getData('grand_total'),$this->getOrder()->getData('base_grand_total'));
        }
        return false;
    }

    public function getWebposCod() {
        if ($this->getOrder()->getData('webpos_codforpos') > 0) {
            return $this->convertAndFormatTotal($this->getOrder()->getData('webpos_codforpos'),$this->getOrder()->getData('webpos_base_codforpos'));
        }
        return false;
    }

    /* Add Earning point and Spending point to invoice */
    public function getEarningPoint(){
        if ($this->getOrder()->getData('rewardpoints_earn') > 0) {
            return $this->getOrder()->getData('rewardpoints_earn');
        }
    }
    public function getSpendingPoint(){
        if ($this->getOrder()->getData('rewardpoints_spent') > 0) {
            return $this->getOrder()->getData('rewardpoints_spent');
        }
    }

    public function convertAndFormatTotal($amount, $baseAmount){
        $baseCurrencyCode = $this->getOrder()->getData('base_currency_code');
        $orderCurrencyCode = $this->getOrder()->getData('order_currency_code');
        $currentCurrencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
        $amount = Mage::helper('directory')->currencyConvert($amount, $orderCurrencyCode, $currentCurrencyCode);
        if($orderCurrencyCode == $currentCurrencyCode){
            return $amount;
        }
        if($baseCurrencyCode == $currentCurrencyCode && $baseAmount){
            return $baseAmount;
        }
        return Mage::helper('core')->formatPrice($amount, false);
    }

}
