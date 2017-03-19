<?php
/*////////////////////////////////////////////////////////////////////////////////
 \\\\\\\\\\\\\\\\\\\\\\\\\\\\\  ExtensionHut Core  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 /////////////////////////////////////////////////////////////////////////////////
 \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ NOTICE OF LICENSE\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 ///////                                                                   ///////
 \\\\\\\ This source file is subject to the Open Software License (OSL 3.0)\\\\\\\
 ///////   that is bundled with this package in the file LICENSE.txt.      ///////
 \\\\\\\   It is also available through the world-wide-web at this URL:    \\\\\\\
 ///////          http://opensource.org/licenses/osl-3.0.php               ///////
 \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 ///////                      * @category   EH                            ///////
 \\\\\\\                      * @package    EH_Core                \\\\\\\
 ///////    * @author     ExtensionHut <info@extensionhut.com>            ///////
 \\\\\\\                                                                   \\\\\\\
 /////////////////////////////////////////////////////////////////////////////////
 \\\\\\* @copyright  Copyright 2015 © www.extensionhut.com All right reserved\\\\\
 /////////////////////////////////////////////////////////////////////////////////
 */
 
class EH_Core_Model_Feed_General extends EH_Core_Model_Feed_Abstract {

    const XML_FEED_URL = 'extensionhut.com/magento_notifications/';
    const XML_FEED_FILENAME = 'feed_general.rss';
    const XML_LASTCHECK = 'eh_general_notifications_lastcheck';

    /**
     * Feed url
     *
     * @var string
     */
    protected $_feedUrl;

    public function getFeedUrl() {
        if (is_null($this->_feedUrl)) {
            $this->_feedUrl = 'https://';
            $this->_feedUrl .= self::XML_FEED_URL . self::XML_FEED_FILENAME;
        }
        return $this->_feedUrl;
    }

    public function feedFetch() {
        $this->checkUpdate();
    }

    public function setLastUpdate() {
        Mage::app()->saveCache(time(), self::XML_LASTCHECK);
        return $this;
    }

    public function getLastUpdate() {
        return Mage::app()->loadCache(self::XML_LASTCHECK);
    }

}
