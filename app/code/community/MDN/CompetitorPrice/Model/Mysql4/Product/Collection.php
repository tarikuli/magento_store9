<?php

class MDN_CompetitorPrice_Model_Mysql4_Product_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('CompetitorPrice/Product');
    }

}
