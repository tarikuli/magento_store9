<?php

class MDN_ExtensionConflict_Block_Logs extends Mage_Adminhtml_Block_Widget_Form
{

	public function getLogFileCombo($value = ''){
		return $this->getFileListAsCombo($this->getLogsList(),'logsList',$value);
	}

	public function getReportFileCombo($value = ''){
		return $this->getFileListAsCombo($this->getReportList(),'reportsList',$value);
	}

	public function getFileListAsCombo($valuesArray,$name,$value){
		$html = '<select id="' . $name . '" name="' . $name . '" onChange="loadFileIntoTextArea(this.options[this.selectedIndex])">';
		$helper = mage::helper('ExtensionConflict/File');
		$html .= '<option value="">No selection</option>';
		foreach ($valuesArray as $item) {
			$selected = ($item['value'] == $value)?' selected ':'';
			$valueForAjax = $helper->encodeFilePathForAjax($item['value']);
			$html .= '<option value="' . $valueForAjax . '" ' . $selected . '>' . $item['label'] .' ('.$helper->formatBytes($item['size']) .')' . '</option>';
		}
		$html .= '</select>';
		return $html;
	}



	public function getLogsList()
	{
		$allowedExtensions = array('log','txt');
		return mage::helper('ExtensionConflict/File')->getFilesListAsArray($this->getLogsDirectory(),$allowedExtensions);
	}

	public function getReportList()
	{
		return mage::helper('ExtensionConflict/File')->getFilesListAsArray($this->getReportDirectory());
	}
	
	public function getBackUrl()
	{
		return $this->getUrl('adminhtml/ExtensionConflict_Admin/List');
	}


	//------------------------ FILE SYSTEM

	private function getVarDirectoryBasedOnVar($folderName)
	{
		return Mage::getBaseDir('var') . DS . $folderName . DS;
	}

	private function getLogsDirectory()
	{
		return $this->getVarDirectoryBasedOnVar('log');
	}

	private function getReportDirectory()
	{
		return $this->getVarDirectoryBasedOnVar('report');
	}

	
}