<?php

require_once 'abstract.php';

class MDN_Shell_ErpWarehouseExport extends Mage_Shell_Abstract
{


    /**
     * Run script
     *
     */
    public function run()
    {
        $stockId = $this->getArg('stock_id');
        $date = $this->getArg('date');

        if ($stockId && $date) {
            $fileName = 'erp_warehouse_export_stock_' . $stockId . '_' . (str_replace('-', '_', $date)) . '.csv';
            $filePath = Mage::getBaseDir('var').DS.'export'.DS.$fileName;

            echo "\nExport stocks for warehouse #" . $stockId . " at <" . $date . ">";
            $csvContent = Mage::helper('AdvancedStock/Warehouse')->getStockAtDateContent($stockId, $date);
            file_put_contents($filePath, $csvContent);
            echo "\nExport finished : ".$filePath."\n";
        }

        $this->usageHelp();
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f erpIndexer.php -- [options]

  help                          This help
  stock_id     Warehouse id
  date         Date (YYYY-MM-DD)

USAGE;
    }
}

$shell = new MDN_Shell_ErpWarehouseExport();
$shell->run();
