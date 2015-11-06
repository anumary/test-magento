<?php
/**
* Bridge Batchcode
*
* @category      Bridge
* @package       Bridge_Batchcode
* @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
* @license       http://bridge-india.in/disclaimer/magento
*/
echo 'Running This Installation script : '.get_class($this)."\n <br /> \n";

$installer = $this;
$installer->startSetup();
$installer->run("
    CREATE TABLE IF NOT EXISTS `{$installer->getTable('batchcode/batchcode')}` (
  `entity_id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_number` varchar(250) NOT NULL,
  `qty` decimal(12,4) NOT NULL,
  `onsales` smallint(6) NOT NULL,
  `onsalesstartdate` datetime NOT NULL,
  `onsalesenddate` datetime NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `enabled` smallint(4) DEFAULT '1',
  `sales_priority` int(11) NOT NULL,
  PRIMARY KEY (`entity_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
");

   $installer->run("
    CREATE TABLE IF NOT EXISTS `{$installer->getTable('batchcode/batchcode_product')}` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_id` int(11) NOT NULL,
  `product_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

");

   $installer->run("
   CREATE TABLE IF NOT EXISTS `{$installer->getTable('batchcode/batchcode_order_item')}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `product_id` int(10) NOT NULL,
  `qty` decimal(12,4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

");

$installer->endSetup();
