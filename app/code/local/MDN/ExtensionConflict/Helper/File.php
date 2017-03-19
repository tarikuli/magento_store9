<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright  Copyright (c) 2015 BoostMyShop (http://www.boostmyshop.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @package MDN_ExtensionConflict
 */
class MDN_ExtensionConflict_Helper_File extends Mage_Core_Helper_Abstract
{

    public function getFilesListAsArray($dir,$allowedExtensions = null)
    {
        $files = array();
        $handle = opendir($dir);
        if ($handle) {
            $maxLoop = 500;//avoid security infinite loop - limit max 500 file per folder

            while (false !== ($file = readdir($handle))) {

                if (--$maxLoop == 0)
                    break;

                if ($file != '.' && $file != '..') {
                    $path = $dir . $file;
                    if (is_file($path)) {
                        $data = array();
                        $data['label'] = $file;
                        $data['value'] = $path;
                        $data['size'] = filesize($path);
                        $data['time'] = date ("Y-m-d H:i:s",filemtime ($path));

                        $pointPos = strpos($file,'.');
                        $data['type'] = ($pointPos>0) ? substr($file,$pointPos+1):'report';

                        $allowedFile = true;
                        if($allowedExtensions != null && strlen($data['type'])>0){
                            $allowedFile = false;
                            foreach($allowedExtensions as $extension){
                                if($data['type'] == $extension){
                                    $allowedFile = true;
                                }
                            }
                        }

                        if($allowedFile)
                            $files[] = $data;
                    }
                }

            }
        }
        closedir($handle);
        return $files;
    }

    public function getFile($path){
        $maxSize = 512*1024;//512kb
        $fileSize = filesize($path);
        if($fileSize<$maxSize)
            return file_get_contents($path);
        else {
            //return the last 512kb - the end of the file
            $begin = $fileSize-$maxSize;
            return file_get_contents($path, null, null, $begin, $maxSize);
        }

    }

    public function encodeFilePathForAjax($value){
       return str_replace('/','|',$value);
    }

    public function decodeFilePathForAjax($value){
        return str_replace('|','/',urldecode($value));
    }

    function formatBytes($bytes, $precision = 2) {
        $unitsArray = array('b' => 1, 'Kb' => 2 , 'Mb' => 3 , 'Gb' => 4);

        $bytes = max($bytes, 0);
        $finalBytes = 0;
        $finalText = '';

        foreach($unitsArray as $unitText => $exponent){
            $pow = pow(1024,$exponent);
            if($bytes < $pow ){
                $finalText = $unitText;
                $finalBytes = round($bytes/($pow/1024), $precision);
                break;
            }
        }


        return $finalBytes . ' ' . $finalText;
    }

}