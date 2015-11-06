<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Batchcode_Productbatches extends Mage_Adminhtml_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('Bridge/batchcode/productbatches.phtml');
    }

    /**
     * Get all batches of a product
     *
     * @return collection
     */
    public function getBatchByProduct() {
        $product_id = $this->getData('product_id');
        $qty_ordered = $this->getData('qty_ordered');
        $order_item_id = $this->getData('order_item_id');
        $collection = Mage::getModel('batchcode/batchcode')
                ->getCollection()
                ->getBatchByProduct($product_id)
        ;
        $this->setCollection($collection);
        $this->setData('qty_ordered', $qty_ordered);

        return $collection;
    }

}
