<?php

require_once 'abstract.php';

class MDN_Shell_ErpIndexer extends Mage_Shell_Abstract
{


    /**
     * Run script
     *
     */
    public function run()
    {
        if ($this->getArg('help')) {
            $this->usageHelp();
            return;
        }
        if ($this->getArg('sales_history')) {
            echo "\nRefreshing sales history";
            $result = Mage::helper('AdvancedStock/Sales_History')->updateForAllProductsWithoutTask();
            echo "\n".$result." products updated\n";
            return;
        }
        if ($this->getArg('ideal_stock_level')) {
            echo "\nRefreshing Ideal stock levels";
            $result = Mage::helper('AdvancedStock/Product_PreferedStockLevel')->updateForAllProducts();
            echo "\n".$result." products updated\n";
            return;
        }
        if ($this->getArg('backgroundtask'))
        {
            echo "\Running background tasks";
            $result = Mage::helper('BackgroundTask')->ExecuteTasks(true);
            echo "\n".$result." tasks executed\n";
            return;
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
  sales_history             Refresh sales history
  ideal_stock_level         Refresh ideal / warning stock levels
  backgroundtask            Run pending background tasks

USAGE;
    }
}

$shell = new MDN_Shell_ErpIndexer();
$shell->run();
