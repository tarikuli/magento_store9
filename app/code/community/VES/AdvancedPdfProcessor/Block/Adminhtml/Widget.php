<?php

/**
 * WYSIWYG widget plugin main block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class VES_AdvancedPdfProcessor_Block_Adminhtml_Widget extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_blockGroup = 'advancedpdfprocessor';
        $this->_controller = 'adminhtml';
        $this->_mode = 'widget';
        $this->_headerText = $this->helper('widget')->__('Widget Insertion');

        $this->removeButton('reset');
        $this->removeButton('back');
        $this->_updateButton('save', 'label', $this->helper('widget')->__('Insert Widget'));
        $this->_updateButton('save', 'class', 'add-widget');
        $this->_updateButton('save', 'id', 'insert_button');
        $this->_updateButton('save', 'onclick', 'wWidget.insertWidget()');
		
        //initial object
        $this->_formScripts[] = 'wWidget = new WysiwygWidget.Widget('
            . '"widget_options_form", "select_widget_type", "widget_options", "'
            . $this->getUrl('*/*/loadOptions') .'", "' . $this->getRequest()->getParam('widget_target_id') . '");';
        
	}
}
