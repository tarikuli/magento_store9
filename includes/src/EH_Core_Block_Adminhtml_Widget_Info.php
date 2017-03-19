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
 
class EH_Core_Block_Adminhtml_Widget_Info extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface {

	public function render(Varien_Data_Form_Element_Abstract $element) {
		$html = '<div style="background:url(\'https://extensionhut.com/images/logo.png\') no-repeat scroll 40px center #EAF0EE;border:1px solid #CCCCCC;margin-bottom:10px;padding:10px 10px 10px 200px;">
					<h4>About ExtensionHut.com</h4>
					<p>ExtensionHut is a hut of reliable extensions with global presence and expertise. We pour our knowledge and experience to excel business growth with innovative products. We think beyond the conventional and have passion to perform, design and build solutions to meet your expectation.<br>
					<br />
					<table width="525" border="0">
						<tr>
							<td width="64%">View more extensions from us:</td>
							<td width="36%"><a href="http://www.magentocommerce.com/magento-connect/developer/ExtensionHut" target="_blank">Magento Connect</a></td>
						</tr>
						<tr>
							<td width="64%">Contact:</td>
							<td width="36%"><a href="mailto:info@extensionhut.com">info@extensionhut.com</a></td>
						</tr>
						<tr>
							<td width="64%">Visit our website:</td>
							<td width="36%"><a href="http://www.extensionhut.com" target="_blank">www.extensionhut.com</a></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td height="30"><strong>Need help?</strong></td>
							<td><strong><a href="http://www.extensionhut.com/support" target="_blank">Contact Our Support Team</a></strong></td>
						</tr>
					</table>
				</div>';
		return $html;
	}

}
