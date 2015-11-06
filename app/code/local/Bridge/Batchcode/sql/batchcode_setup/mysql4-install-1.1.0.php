<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
echo 'Installing Product Batch Code 1.1.0 : ' . get_class($this) . "\n <br /> \n";

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
  `expiration_date` date NOT NULL,
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

/* Product Supplier */

$attributeName = 'Product Supplier'; // Name of the attribute
$attributeCode = 'leverancier'; // Code of the attribute
$attributeGroup = 'General';          // Group to add the attribute to
$attributeSetIds = array(4);          // Array with attribute set ID's to add this attribute to. (ID:4 is the Default Attribute Set)
$data = array(
    'type' => 'varchar', // Attribute type
    'input' => 'text', // Input type
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL, // Attribute scope
    'required' => true,
    'user_defined' => false,
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => false,
    'unique' => false,
    'used_in_product_listing' => true,
    'label' => $attributeName
);

$installer = Mage::getResourceModel('catalog/setup', 'catalog_setup');
$installer->startSetup();
$installer->addAttribute('catalog_product', $attributeCode, $data);

foreach ($attributeSetIds as $attributeSetId) {
    $installer->addAttributeToGroup('catalog_product', $attributeSetId, $attributeGroup, $attributeCode);
}
$installer->endSetup();

?>