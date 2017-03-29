<?php

$installer = $this;

$installer->startSetup();
$installer->run("
	--
-- Table structure for table `ves_ves_pdfpros_variable`
--

CREATE TABLE IF NOT EXISTS `{$this->getTable('advancedpdfprocessor/variable')}` (
  `entity_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `code` text,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`entity_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=168 ;

--
-- Dumping data for table `ves_ves_pdfpros_variable`
--

INSERT INTO `{$this->getTable('advancedpdfprocessor/variable')}` (`entity_id`, `category_id`, `title`, `code`, `sort_order`) VALUES
(1, 1, 'Email', '{{var customer.email}}', 2),
(2, 1, 'First Name', '{{var customer.firstname}}', 4),
(3, 1, 'Last Name', '{{var customer.lastname}}', 5),
(4, 1, 'Middle Name', '{{var customer.middlename}}', 6),
(5, 1, 'Prefix', '{{var customer.prefix}}', 7),
(6, 1, 'Suffix', '{{var customer.suffix}}', 8),
(7, 1, 'Tax/VAT Number', '{{var customer.taxvat}}', 9),
(8, 1, 'Birthdate (Full)', '{{var customer.customer_dob.full}}', 1),
(9, 1, 'Is customer guest', '{{var customer.customer_is_guest}}', 3),
(10, 1, 'Gender', '{{var customer.gender}}', 10),
(11, 2, 'First Name', '{{var billing.firstname}}', 1),
(12, 2, 'Middle Name', '{{var billing.middlename}}', 2),
(13, 2, 'Last Name', '{{var billing.lastname}}', 3),
(14, 2, 'Prefix', '{{var billing.prefix}}', 4),
(15, 2, 'Suffix', '{{var billing.suffix}}', 5),
(16, 2, 'E-mail', '{{var billing.email}}', 6),
(17, 2, 'Company', '{{var billing.company}}', 7),
(18, 2, 'Country Name', '{{var billing.country_name}}', 8),
(19, 2, 'Country Code', '{{var billing.country_id}}', 9),
(20, 2, 'State/Region', '{{var billing.region}}', 10),
(21, 2, 'City', '{{var billing.city}}', 11),
(22, 2, 'Zip Code', '{{var billing.postcode}}', 12),
(23, 2, 'Street', '{{var billing.street}}', 13),
(24, 2, 'Telephone', '{{var billing.telephone}}', 14),
(25, 2, 'Fax', '{{var billing.fax}}', 15),
(26, 2, 'Billing Info', '{{var billing.formated}}', 16),
(27, 3, 'First Name', '{{var shipping.firstname}}', 1),
(28, 3, 'Middle Name', '{{var shipping.middlename}}', 2),
(29, 3, 'Last Name', '{{var shipping.lastname}}', 3),
(30, 3, 'Prefix', '{{var shipping.prefix}}', 4),
(31, 3, 'Suffix', '{{var shipping.suffix}}', 5),
(32, 3, 'E-mail', '{{var shipping.email}}', 6),
(33, 3, 'Company', '{{var shipping.company}}', 7),
(34, 3, 'Country Name', '{{var shipping.country_name}}', 8),
(35, 3, 'Country Code', '{{var shipping.country_id}}', 9),
(36, 3, 'State/Region', '{{var shipping.region}}', 10),
(37, 3, 'City', '{{var shipping.city}}', 11),
(38, 3, 'Zip Code', '{{var shipping.postcode}}', 12),
(39, 3, 'Street', '{{var shipping.street}}', 13),
(40, 3, 'Telephone', '{{var shipping.telephone}}', 14),
(41, 3, 'Fax', '{{var shipping.fax}}', 15),
(42, 3, 'Shipping Info', '{{var shipping.formated}}', 16),
(43, 4, 'Method Code', '{{var payment.code}}', 1),
(44, 4, 'Method Name', '{{var payment.name}}', 2),
(45, 4, 'Info Block', '{{var payment.info}}', 3),
(46, 5, 'Negative Adjustment Amount', '{{var order.adjustment_negative}}', 1),
(47, 5, 'Positive Adjustment Amount', '{{var order.adjustment_positive}}', 2),
(48, 5, 'Applied Coupon Code', '{{var order.coupon_code}}', 3),
(49, 5, 'Creation Date (Full)', '{{var order.created_at_formated.full}}', 4),
(50, 5, 'Creation Date (Long)', '{{var order.created_at_formated.long}}', 5),
(51, 5, 'Creation Date (Medium)', '{{var order.created_at_formated.medium}}', 6),
(52, 5, 'Creation Date (Short)', '{{var order.created_at_formated.short}}', 7),
(53, 5, 'Customer ID', '{{var order.customer_id}}', 8),
(54, 5, 'Total Discount Amount', '{{var order.discount_amount}}', 9),
(55, 5, 'Canceled Discount Amount', '{{var order.discount_canceled}}', 10),
(56, 5, 'Invoiced Discount Amount', '{{var order.discount_invoiced}}', 11),
(57, 5, 'Refunded Discount Amount', '{{var order.discount_refunded}}', 12),
(58, 5, 'Discount Description', '{{var order.discount_description}}', 13),
(59, 5, 'Global Currency Code', '{{var order.global_currency_code}}', 14),
(60, 5, 'Grand Total', '{{var order.grand_total}}', 15),
(61, 5, 'ID', '{{var order.increment_id}}', 16),
(62, 5, 'Does order have only virtual products (gift cards, virtual products, etc)', '{{var order.is_virtual}}', 17),
(63, 5, 'Order Currency Code', '{{var order.order_currency_code}}', 18),
(64, 5, 'Shipping Amount Without Tax', '{{var order.shipping_amount}}', 19),
(65, 5, 'Canceled Shipping Amount', '{{var order.shipping_canceled}}', 20),
(66, 5, 'Invoice Shipping Amount', '{{var order.shipping_invoiced}}', 21),
(67, 5, 'Refunded Shipping Amount', '{{var order.shipping_refunded}}', 22),
(68, 5, 'Status', '{{var order.status}}', 23),
(69, 5, 'Shipping Method Description', '{{var order.shipping_description}}', 24),
(70, 5, 'Shipping Discount Amount', '{{var order.shipping_discount_amount}}', 25),
(71, 5, 'Shipping Amount Including Tax', '{{var order.shipping_incl_tax}}', 26),
(72, 5, 'Shipping Tax Amount', '{{var order.shipping_tax_amount}}', 27),
(73, 5, 'Store Currency Code', '{{var order.store_currency_code}}', 28),
(74, 5, 'Store Name', '{{var order.store_name}}', 29),
(75, 5, 'Subtotal Amount Without Tax', '{{var order.subtotal}}', 30),
(76, 5, 'Invoiced Subtotal Amount Without Tax', '{{var order.subtotal_invoiced}}', 31),
(77, 5, 'Refunded Subtotal Amount Without Tax', '{{var order.subtotal_refunded}}', 32),
(78, 5, 'Canceled Subtotal Amount Without Tax', '{{var order.subtotal_canceled}}', 33),
(79, 5, 'Subtotal Amount Including Tax', '{{var order.subtotal_incl_tax}}', 34),
(80, 5, 'Tax Amount', '{{var order.tax_amount}}', 35),
(81, 5, 'Canceled Tax Amount', '{{var order.tax_canceled}}', 36),
(82, 5, 'Invoiced Tax Amount', '{{var order.tax_invoiced}}', 37),
(83, 5, 'Refunded Tax Amount', '{{var order.tax_refunded}}', 38),
(84, 5, 'Total Item Count', '{{var order.total_item_count}}', 39),
(85, 5, 'Total Paid', '{{var order.total_paid}}', 40),
(86, 5, 'Total Canceled Amount', '{{var order.total_canceled}}', 41),
(87, 5, 'Total Due Amount', '{{var order.total_due}}', 42),
(88, 5, 'Total Invoiced Amount', '{{var order.total_invoiced}}', 43),
(89, 5, 'Total Amount Refunded Offline', '{{var order.total_offline_refunded}}', 44),
(90, 5, 'Total Amount Refunded Online', '{{var order.total_online_refunded}}', 45),
(91, 5, 'Total Refunded Amount', '{{var order.total_refunded}}', 46),
(92, 5, 'Total Items Quantity', '{{var order.total_qty_ordered}}', 47),
(93, 5, 'Weight Of All Items', '{{var order.weight}}', 48),
(94, 5, 'Time of Last Update (Full)', '{{var order.updated_at_formated.full}}', 49),
(95, 5, 'Time of Last Update (long)', '{{var order.updated_at_formated.long}}', 50),
(96, 5, 'Time of Last Update (Medium)', '{{var order.updated_at_formated.medium}}', 51),
(97, 5, 'Time of Last Update (Short)', '{{var order.updated_at_formated.short}}', 52),
(98, 6, 'Total Discount Amount', '{{var invoice.discount_amount}}', 1),
(99, 6, 'Global Currency Code', '{{var invoice.global_currency_code}}', 2),
(100, 6, 'Grand Total', '{{var invoice.grand_total}}', 3),
(101, 6, 'ID', '{{var invoice.increment_id}}', 4),
(102, 6, 'Order Currency Code', '{{var invoice.order_currency_code}}', 5),
(103, 6, 'Shipping Amount Including Tax', '{{var invoice.shipping_incl_tax}}', 6),
(104, 6, 'Shipping Tax Amount', '{{var invoice.shipping_tax_amount}}', 7),
(105, 6, 'Shipping Total Amount', '{{var invoice.shipping_amount}}', 8),
(106, 6, 'Store Currency Code', '{{var invoice.store_currency_code}}', 9),
(107, 6, 'Subtotal Amount Including Tax', '{{var invoice.subtotal_incl_tax}}', 10),
(108, 6, 'Tax Amount', '{{var invoice.tax_amount}}', 11),
(109, 6, 'Total Items Quantity', '{{var invoice.total_qty}}', 12),
(110, 7, 'ID', '{{var shipment.increment_id}}', 1),
(111, 7, 'Total Items Quantity', '{{var shipment.total_qty}}', 2),
(112, 7, 'Tracking Numbers', '{{if1 shipment.tracking}}\r\n{{foreach shipment.tracking as track}}\r\n	{{var track.title}}:{{var track.number}}<br />\r\n{{/foreach}}\r\n{{/if1}}', 3),
(113, 6, 'Creation Date (Long)', '{{var invoice.created_at_formated.long}}', 14),
(114, 6, 'Creation Date (Full)', '{{var invoice.created_at_formated.full}}', 13),
(115, 6, 'Creation Date (Medium)', '{{var invoice.created_at_formated.medium}}', 15),
(116, 6, 'Creation Date (Short)', '{{var invoice.created_at_formated.full}}', 16),
(117, 6, 'Time of Last Update (Full)', '{{var invoice.updated_at_formated.full}}', 17),
(118, 6, 'Time of Last Update (long)', '{{var invoice.updated_at_formated.long}}', 18),
(119, 6, 'Time of Last Update (Medium)', '{{var invoice.updated_at_formated.medium}}', 19),
(120, 6, 'Time of Last Update (Short)', '{{var invoice.updated_at_formated.short}}', 20),
(121, 7, 'Creation Date (Full)', '{{var shipment.created_at_formated.full}}', 4),
(122, 7, 'Creation Date (Long)', '{{var shipment.created_at_formated.long}}', 5),
(123, 7, 'Creation Date (Medium)', '{{var shipment.created_at_formated.medium}}', 6),
(124, 7, 'Creation Date (Short)', '{{var shipment.created_at_formated.short}}', 8),
(125, 7, 'Time of Last Update (Full)', '{{var shipment.updated_at_formated.full}}', 9),
(126, 7, 'Time of Last Update (long)', '{{var shipment.updated_at_formated.long}}', 10),
(127, 7, 'Time of Last Update (Medium)', '{{var shipment.updated_at_formated.medium}}', 11),
(128, 7, 'Time of Last Update (Short)', '{{var shipment.updated_at_formated.short}}', 12),
(129, 8, 'Total Adjustment', '{{var creditmemo.adjustment}}', 1),
(130, 8, 'Negative Adjustment', '{{var creditmemo.adjustment_negative}}', 2),
(131, 8, 'Positive Adjustment', '{{var creditmemo.adjustment_positive}}', 3),
(132, 8, 'Creation Date (Full)', '{{var creditmemo.created_at_formated.full}}', 4),
(133, 8, 'Creation Date (Long)', '{{var creditmemo.created_at_formated.long}}', 5),
(134, 8, 'Creation Date (Medium)', '{{var creditmemo.created_at_formated.medium}}', 6),
(135, 8, 'Creation Date (Short)', '{{var creditmemo.created_at_formated.short}}', 7),
(136, 8, 'Total Discount Amount', '{{var creditmemo.discount_amount}}', 8),
(137, 8, 'Grand Total', '{{var creditmemo.grand_total}}', 10),
(138, 8, 'Order Currency Code', '{{var creditmemo.order_currency_code}}', 11),
(139, 8, 'ID', '{{var creditmemo.increment_id}}', 12),
(140, 8, 'Shipping Amount Including Tax', '{{var creditmemo.shipping_incl_tax}}', 13),
(141, 8, 'Shipping Tax Amount', '{{var creditmemo.shipping_tax_amount}}', 14),
(142, 8, 'Shipping Discount Amount', '{{var creditmemo.shipping_discount_amount}}', 15),
(143, 8, 'Shipping Total Amount', '{{var creditmemo.shipping_amount}}', 16),
(144, 8, 'Global Currency Code', '{{var creditmemo.global_currency_code}}', 17),
(145, 8, 'Store Currency Code', '{{var creditmemo.store_currency_code}}', 18),
(146, 8, 'Subtotal Amount Without Tax', '{{var creditmemo.subtotal}}', 19),
(147, 8, 'Subtotal Amount Including Tax', '{{var creditmemo.subtotal_incl_tax}}', 20),
(148, 8, 'Tax Amount', '{{var creditmemo.tax_amount}}', 21),
(149, 8, 'Time of Last Update (Full)', '{{var creditmemo.updated_at_formated.full}}', 22),
(150, 8, 'Time of Last Update (Long)', '{{var creditmemo.updated_at_formated.long}}', 23),
(151, 8, 'Time of Last Update (Medium)', '{{var creditmemo.updated_at_formated.medium}}', 24),
(152, 8, 'Time of Last Update (Short)', '{{var creditmemo.updated_at_formated.short}}', 25),
(153, 1, 'Birthdate (Long) ', '{{var customer.customer_dob.long}}', 1),
(154, 1, 'Birthdate (Medium)', '{{var customer.customer_dob.medium}}', 1),
(155, 1, 'Birthdate (Short)', '{{var customer.customer_dob.short}}', 1),
(156, 5, 'ID (Barcode)', '{{barcode order.increment_id}}', 16),
(157, 6, 'ID (Barcode)', '{{barcode invoice.increment_id}} ', 4),
(158, 7, 'ID (Barcode)', '{{barcode shipment.increment_id}} ', 1),
(159, 8, 'ID (Barcode)', '{{barcode creditmemo.increment_id}} ', 12),
(160, 5, 'Order Comments', '{{foreach order.comments as comment}}\r\n	<strong>{{var comment.created_date}}</strong> {{var comment.created_time}} | {{var comment.status}}<br />\r\n	Customer <strong>{{var comment.customer_notified}}</strong><br />\r\n	{{if comment.comment}}\r\n		{{var comment.comment}}<br />\r\n	{{/if}}\r\n{{/foreach}}', 53),
(161, 6, 'Comments', '{{foreach invoice.comments as comment}}\r\n	<strong>{{var comment.created_date}}</strong> {{var comment.created_time}}<br />\r\n	Customer <strong>{{var comment.customer_notified}}</strong><br />\r\n	{{if comment.comment}}\r\n		{{var comment.comment}}<br />\r\n	{{/if}}\r\n{{/foreach}}', 21),
(162, 7, 'Comments', '{{foreach shipment.comments as comment}}\r\n	<strong>{{var comment.created_date}}</strong> {{var comment.created_time}}<br />\r\n	Customer <strong>{{var comment.customer_notified}}</strong><br />\r\n	{{if comment.comment}}\r\n		{{var comment.comment}}<br />\r\n	{{/if}}\r\n{{/foreach}}', 13),
(163, 8, 'Comments', '{{foreach creditmemo.comments as comment}}\r\n	<strong>{{var comment.created_date}}</strong> {{var comment.created_time}}<br />\r\n	Customer <strong>{{var comment.customer_notified}}</strong><br />\r\n	{{if comment.comment}}\r\n		{{var comment.comment}}<br />\r\n	{{/if}}\r\n{{/foreach}}', 26),
(164, 5, 'ID (QR Code)', '{{qrcode order.increment_id}}', 16),
(165, 6, 'ID (QR Code)', '{{qrcode invoice.increment_id}}', 4),
(166, 7, 'ID (QR Code)', '{{qrcode shipment.increment_id}}', 1),
(167, 8, 'ID (QR Code)', '{{qrcode creditmemo.increment_id}}', 12);	
");

$installer->run("

--
-- Table structure for table `ves_ves_pdfpros_variable_category`
--

CREATE TABLE IF NOT EXISTS `{$this->getTable('advancedpdfprocessor/category')}` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `code` varchar(255) not null default '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `type_variable` int(11) not null default '1',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `ves_ves_pdfpros_variable_category`
--

INSERT INTO `{$this->getTable('advancedpdfprocessor/category')}` (`category_id`, `title`, `code`, `sort_order`,`type_variable`) VALUES
(1, 'Customer' , 'customer' , 1, 1),
(2, 'Billing Address' , 'billing_address', 2,1),
(3, 'Shipping Address', 'shipping_address', 3, 1),
(4, 'Payment Information', 'payment_information', 4, 1),
(5, 'Order' , 'order', 5, 1),
(6, 'Invoice','invoice', 6, 1),
(7, 'Shipment', 'shipment', 7, 1),
(8, 'Credit Memo', 'creditmemo', 9, 1),
(9,'Order Item', 'order_item', 10, 0),
(10,'Invoice Item', 'invoice_item', 11, 0),
(11,'Shipment Item', 'shipment_item',12, 0),
(12,'Credit Memo Item', 'creditmemo_item', 13, 0);
");

$installer->endSetup();