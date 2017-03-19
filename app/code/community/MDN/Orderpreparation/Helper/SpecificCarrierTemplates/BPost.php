<?php


class MDN_Orderpreparation_Helper_SpecificCarrierTemplates_BPost extends MDN_Orderpreparation_Helper_SpecificCarrierTemplates_Abstract {

    /**
     * Generate XML output
     *
     * @param unknown_type $orderPreparationCollection
     */
    public function createExportFile($orderPreparationCollection) {

        foreach ($orderPreparationCollection as $orderToPrepare) {

            $order = mage::getModel('sales/order')->load($orderToPrepare->getorder_id());
            $shipmentId = $orderToPrepare->getshipment_id();
            $shipment = Mage::getModel('sales/order_shipment')->loadByIncrementId($shipmentId);

            if ($shipment->getBpostLabelPath() != "") {

                $pdfMerged = new Zend_Pdf();

                $ioFile = new Varien_Io_File();
                $bpostMediaFilePath = Mage::getBaseDir('media') . Bpost_ShM_Model_Adminhtml_Bpostgrid::MEDIA_LABEL_PATH;
                $pdfNames = explode(":", $shipment->getBpostLabelPath());

                foreach($pdfNames as $pdfName){
                    $pdfPath = $bpostMediaFilePath . $pdfName;

                    if($ioFile->fileExists($pdfPath)){
                        $tmpPdf = Zend_Pdf::load($pdfPath);

                        foreach ($tmpPdf->pages as $page) {
                            $clonedPage = clone $page;
                            $pdfMerged->pages[] = $clonedPage;
                        }
                    }
                }

                return $pdfMerged->render();
            }


        }

    }


    /**
     * Method to import trackings
     * @param <type> $t_lines
     */
    public function importTrackingFile($t_lines) {

        throw new Exception('Not implemented');
    }


}