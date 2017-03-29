<?php

class VES_Core_Helper_Data extends VES_Core_Helper_Core
{
	/**
	 * Get All License Info
	 */
	public function getLicenseInfo(){
		if(Mage::registry('ves_license_information') === null){
			$installedLicenseCollection = Mage::getModel('ves_core/key')->getCollection();
			$info = array();
			foreach($installedLicenseCollection as $license){
				$licenseInfo = unserialize($license->decode($license->getLicenseInfo(),VES_Core_Model_Key::ENCODED_KEY));
				$licenseInfo['license_key']	= $license->getLicenseKey();
				$info[$licenseInfo['extension_name']]	= $licenseInfo;
			}
			Mage::register('ves_license_information', $info);
		}
		return Mage::registry('ves_license_information');
	}
	
	/**
	 * Check extension is activated or not.
	 */
	public function checkExtensions(){
		$installedModules = Mage::app()->getConfig()->getModuleConfig()->asArray();
		$licenseInfos = $this->getLicenseInfo();
		$notificationMessage = array();
		foreach($this->getExtensionList() as $extensionName=>$extensionTitle){
			if(isset($installedModules[$extensionName]) && is_array($installedModules[$extensionName]) && ($installedModules[$extensionName]['active']=='true')){
				$available = false;
				$status	   = 4;
				if(isset($licenseInfos[$extensionName]) && ($licenseInfos[$extensionName]['status']==2)){
					$available = true;
				}else{
					$available = false;
					$status = isset($licenseInfos[$extensionName])?$licenseInfos[$extensionName]['status']:4;
				}
				if(!$available){
					switch($status){
						case 1: $status = 'pending';break;
						case 0: $status = 'expired';break;
						case 3: $status = 'suspended';break;
						default: $status = 'not activated';
					}
					$notificationMessage[$extensionName] = $this->__('<strong class="label">Vnecoms:</strong> Extension <strong>%s</strong> is %s. <a href="%s">Manage your license key</a> now.',$extensionTitle,$status,Mage::helper('adminhtml')->getUrl('adminhtml/vnecoms_extension/'));
				}
			}
		}
		Mage::getSingleton('adminhtml/session')->setData('ves_notification_message',$notificationMessage);
	}
	
	/**
	 * Get all paid extensions.
	 */
	public function getExtensionList(){
		if (Mage::registry('ves_license_extension_list') === null){
			$dir = Mage::getBaseDir('media') . DS . 'ves_tmp';
			if (!(@is_dir($dir) || @mkdir($dir, 0777, true))){
				throw new Exception("Unable to create directory '{$dir}'.");
			}
	
			$filename = Mage::getBaseDir('media') . DS . 'ves_tmp' . DS . 'vnecoms.data';

			if (!is_file($filename)/* || ($this->getFrequency() + $this->getLastUpdate()) < time()*/){
				$fp = fopen($filename, 'w');
				$content = '2Cuqff9VVaqUFqowWul2BYJrM9EqIGLjuy28w6fiun9kzA2RLojrUE9geB2m1bqDvfLd2/9oQLufJkGT2N9nlQ5pBs6ikM9nY29R3cWUz+U0cRrODpg2GMnc7CmkBv+ilxWcIUR8+U9eHE3rXiUHvoI8UmET2ynyVlocTx9e3x8rb2AUdptGrmrMv1LgBFOp+5n0XIyhzJpENd3fQJMe2qqpfmH9S2XtX/Gcwt/Wy90t1l69aGrwt2aH1fxstQ+z2lzZMScF3sSpllwau+Cqv9VFpOiWUWSLHnFeWTWzznBI+hjEDDoKiBXAVjTK1HAk5JgQoW++SQX40NYCQV6Yj5awwhtUWt+cdK2lf5ovSBD+wMAvGGPPMEgByjrGMQp3ELqBkX4upF53Xhi6FCGi/EcfvZBZpTf/Tck9LpQcsDozbl1YWAPJGIT+D9YtCAB4vzqBFN3HdroZYQDwmhYBmEg/lZqVmDa4j4hghJeOIEeChm6fzBOwpOP0Nxh+LLu2tdcuF0hD14thuggVHcwJjihmkb+B2DP9JfeH1uHAHVgpPma9szSA1FnhjOBHdWmnqPI6srWVtK8CLumDJqOOzRNNlmFhRw0sKROVH34m9w7Gr2NcqfoTpRKMDcI0JJCn1oibMhmIlDybLfsEABOk3IBdKe8+76oRQAK+CxQR56mgaThzg6MbwKky+53qnRwqihnYi5l6F8w+PdEYesISzJj1DO7Yrux0lQemClRmkzbTh7UwYq+Kce2iFGa4Eutb3XR9kLLJMXdOYYcpKhhXoIQe7EIlK1MY9HZm6NFfmtE=';
				fwrite($fp, $content);
				fclose($fp);
				$this->setLastUpdate();
			}
	
			$extensions = file_get_contents($filename);
			$extensions = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5('90986602085d91835e5f0bb7cc43b025') , base64_decode($extensions) , MCRYPT_MODE_CBC, md5(md5('90986602085d91835e5f0bb7cc43b025'))) , "\0");
			$extensions = unserialize($extensions);
			if (!is_array($extensions) || sizeof($extensions) < 9){
				throw new Exception("License file is invalid.");
			}
	
			Mage::register('ves_license_extension_list', $extensions);
		}
		return Mage::registry('ves_license_extension_list');
	}
	/**
     * Retrieve Update Frequency
     *
     * @return int
     */
    public function getFrequency()
    {
        return 2592000; /*30 days*/
    }

    /**
     * Retrieve Last update time
     *
     * @return int
     */
    public function getLastUpdate()
    {
    	$lastUpdate = Mage::app()->loadCache('ves_license_extension_list_lastcheck');
    	if(!$lastUpdate) return $this->setLastUpdate();
        return $lastUpdate;
    }

    /**
     * Set last update time (now)
     *
     * @return Mage_AdminNotification_Model_Feed
     */
    public function setLastUpdate()
    {
    	$time = time();
        Mage::app()->saveCache($time, 'ves_license_extension_list_lastcheck');
        return $time;
    }
}