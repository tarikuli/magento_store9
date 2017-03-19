<?php

    class SSTech_Ordertracking_Helper_Data extends Mage_Core_Helper_Abstract
    {
        public function getOrdertrackingUrl()
        {
            return $this->_getUrl('ordertracking/index');
        }
}