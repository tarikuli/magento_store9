<?php
class VES_PdfProItem_Model_Observer
{
    public function ves_pdfpro_data_prepare_after($observer){
    	$type = $observer->getType();
    	if($type=='item'){
    		$itemData = $observer->getSource();			/*Varien_Object	*/
    		$item = $observer->getModel();
			if ($item instanceof Mage_Sales_Model_Order_Item) {
	            /*Mage_Sales_Model_Order_Item*/
				
	        }else if($item instanceof Mage_Sales_Model_Order_Invoice_Item){

				/*Mage_Sales_Model_Order_Invoice_Item*/
				
				/*** ADD PRODUCT ATTRIBUTES HERE ***/
				$product = Mage::getModel('catalog/product')->load($item->getData('product_id'));
				$name = $product->getName().'<br/>'. $product->getSku();$itemData->setData('name', $name);
				/*** ADD PRODUCT ATTRIBUTES HERE ***/
				
			}else if($item instanceof Mage_Sales_Model_Order_Shipment_Item){
				/*Mage_Sales_Model_Order_Shipment_Item*/
				
			}else if($item instanceof Mage_Sales_Model_Order_Creditmemo_Item){
				/*Mage_Sales_Model_Order_Creditmemo_Item*/
				
			}
			
			/*
				$customVariable = new Varien_Object(array(
					'property1'	=> 'value1',
					'property2'	=> 'value2',
					'property3'	=> 'value3',
				));
				$itemData->setData('custom_variable',$customVariable);
				
				$customVariable1 = 'This is value of custom variable 1';
				$itemData->setData('custom_variable_1',$customVariable1);
				
				In your PDF template you can use
				
				{{var item.custom_variable.property1}}
				{{var item.custom_variable.property2}}
				{{var item.custom_variable.property3}}
				{{var item.custom_variable_1}}
			*/
    	}elseif($type == 'order'){
    		
    		$orderData 	= $observer->getSource();		/*Varien_Object	*/
    		$order 		= $observer->getModel();		/*Mage_Sales_Model_Order*/
    		
			/*
				$customVariable = new Varien_Object(array(
					'property1'	=> 'value1',
					'property2'	=> 'value2',
					'property3'	=> 'value3',
				));
				$orderData->setData('custom_variable',$customVariable);
				
				$customVariable1 = 'This is value of custom variable 1';
				$orderData->setData('custom_variable_1',$customVariable1);
				
				In your Order PDF template you can use
				
				{{var order.custom_variable.property1}}
				{{var order.custom_variable.property2}}
				{{var order.custom_variable.property3}}
				{{var order.custom_variable_1}}
			*/

    	}elseif($type == 'invoice'){
    		$invoiceData 	= $observer->getSource();	/* Varien_Object*/
    		$invoice 		= $observer->getModel();	/*Mage_Sales_Model_Order_Invoice*/
    		/*
				$customVariable = new Varien_Object(array(
					'property1'	=> 'value1',
					'property2'	=> 'value2',
					'property3'	=> 'value3',
				));
				$invoiceData->setData('custom_variable',$customVariable);
				
				$customVariable1 = 'This is value of custom variable 1';
				$invoiceData->setData('custom_variable_1',$customVariable1);
				
				In your Invoice PDF template you can use
				
				{{var invoice.custom_variable.property1}}
				{{var invoice.custom_variable.property2}}
				{{var invoice.custom_variable.property3}}
				{{var invoice.custom_variable_1}}
			*/
    	}elseif($type == 'shipment'){
    		$shipmentData 	= $observer->getSource();	/* Varien_Object*/
    		$shipment 		= $observer->getModel();	/*Mage_Sales_Model_Order_Shipment*/
    		
			/*
				$customVariable = new Varien_Object(array(
					'property1'	=> 'value1',
					'property2'	=> 'value2',
					'property3'	=> 'value3',
				));
				$shipmentData->setData('custom_variable',$customVariable);
				
				$customVariable1 = 'This is value of custom variable 1';
				$shipmentData->setData('custom_variable_1',$customVariable1);
				
				In your Shipment PDF template you can use
				
				{{var shipment.custom_variable.property1}}
				{{var shipment.custom_variable.property2}}
				{{var shipment.custom_variable.property3}}
				{{var shipment.custom_variable_1}}
			*/

    	}elseif($type == 'creditmemo'){
    		$creditmemoData = $observer->getSource();	/* Varien_Object*/
    		$creditmemo 	= $observer->getModel();	/*Mage_Sales_Model_Order_Creditmemo*/
    		/*
				$customVariable = new Varien_Object(array(
					'property1'	=> 'value1',
					'property2'	=> 'value2',
					'property3'	=> 'value3',
				));
				$creditmemoData->setData('custom_variable',$customVariable);
				
				$customVariable1 = 'This is value of custom variable 1';
				$creditmemoData->setData('custom_variable_1',$customVariable1);
				
				In your Creditmemo PDF template you can use
				
				{{var creditmemo.custom_variable.property1}}
				{{var creditmemo.custom_variable.property2}}
				{{var creditmemo.custom_variable.property3}}
				{{var creditmemo.custom_variable_1}}
			*/

    	}elseif($type == 'customer'){
    		$customerData 	= $observer->getSource();	/* Varien_Object*/
    		$customer 		= $observer->getModel();	/*Mage_Customer_Model_Customer*/
    	}
    }
}
