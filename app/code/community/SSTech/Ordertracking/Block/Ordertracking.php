<?php
class SSTech_Ordertracking_Block_Ordertracking extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getOrdertracking()     
     { 
        if (!$this->hasData('ordertracking')) {
            $this->setData('ordertracking', Mage::registry('current_order'));
        }
        return $this->getData('ordertracking');
        
    }
    public function getTrackInfo($order)
    {
        $shipTrack = array();
        if ($order) {
            $shipments = $order->getShipmentsCollection();
            foreach ($shipments as $shipment){
                $increment_id = $shipment->getIncrementId();
                $tracks = $shipment->getTracksCollection();

                $trackingInfos=array();
                foreach ($tracks as $track){
                    $trackingInfos[] = $track->getNumberDetail();
                }
                $shipTrack[$increment_id] = $trackingInfos;
            }
        }
        return $shipTrack;
    }
}