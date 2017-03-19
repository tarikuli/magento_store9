<?php

class Magestore_Webpos_Block_Sales_Order_Totals extends Mage_Adminhtml_Block_Sales_Order_Totals {

    public function _initTotals() {
        parent::_initTotals();
        if ($this->cashAmount() > 0)
            $this->_totals['webpos_cash'] = new Varien_Object(array(
                'code' => 'webpos_cash',
                'strong' => true,
                'value' => $this->cashAmount(),
                'base_value' => $this->cashAmount('base'),
                'label' => $this->helper('webpos/payment')->getCashMethodTitle(),
            ));
        if ($this->changeAmount() > 0)
            $this->_totals['webpos_change'] = new Varien_Object(array(
                'code' => 'webpos_change',
                'strong' => true,
                'value' => $this->changeAmount(),
                'base_value' => $this->changeAmount('base'),
                'label' => $this->helper('sales')->__('POS Change'),
            ));

        if($this->paymentAmount('ccforpos') > 0){
            $this->_totals['webpos_ccforpos'] = new Varien_Object(array(
                'code' => 'webpos_ccforpos',
                'strong' => true,
                'value' => $this->paymentAmount('ccforpos'),
                'base_value' => $this->paymentAmount('ccforpos', true),
                'label' => $this->helper('webpos/payment')->getCcMethodTitle(),
            ));
        }

        if($this->paymentAmount('cp1forpos') > 0){
            $this->_totals['webpos_cp1forpos'] = new Varien_Object(array(
                'code' => 'webpos_cp1forpos',
                'strong' => true,
                'value' => $this->paymentAmount('cp1forpos'),
                'base_value' => $this->paymentAmount('cp1forpos', true),
                'label' => $this->helper('webpos/payment')->getCp1MethodTitle(),
            ));
        }


        if($this->paymentAmount('cp2forpos') > 0){
            $this->_totals['webpos_cp2forpos'] = new Varien_Object(array(
                'code' => 'webpos_cp2forpos',
                'strong' => true,
                'value' => $this->paymentAmount('cp2forpos'),
                'base_value' => $this->paymentAmount('cp2forpos', true),
                'label' => $this->helper('webpos/payment')->getCp2MethodTitle(),
            ));
        }

        if($this->paymentAmount('codforpos') > 0){
            $this->_totals['webpos_codforpos'] = new Varien_Object(array(
                'code' => 'webpos_codforpos',
                'strong' => true,
                'value' => $this->paymentAmount('codforpos'),
                'base_value' => $this->paymentAmount('codforpos', true),
                'label' => $this->helper('webpos/payment')->getCodMethodTitle(),
            ));
        }

    }

    public function getObjectOrder(){
        return $this->getParentBlock()->getOrder();
    }

    public function cashAmount($type = null) {
        $order = $this->getObjectOrder();

        if ($type == 'base')
            $webposCash = $order->getWebposBaseCash();
        else
            $webposCash = $order->getWebposCash();
        return $webposCash;
    }

    public function changeAmount($type = null) {
        $order = $this->getObjectOrder();
        if ($type == 'base')
            $webposChange = $order->getWebposBaseChange();
        else
            $webposChange = $order->getWebposChange();
        return $webposChange;
    }

    public function paymentAmount($type, $is_base = false){
        $order = $this->getObjectOrder();

        if ($is_base)
            $typeAmount = !is_null($order->getData('webpos_base_'.$type)) ? $order->getData('webpos_base_'.$type) : 0;
        else
            $typeAmount = !is_null($order->getData('webpos_'.$type)) ? $order->getData('webpos_'.$type) : 0;
        return $typeAmount;
    }




}
