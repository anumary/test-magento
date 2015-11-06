<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Batchcode_Orderview extends Mage_Adminhtml_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('Bridge/batchcode/orderview.phtml');
    }

    /**
     * Get all batchcode of the shipped product
     *
     * @return Batchcode & Expiry date
     */


    public function getBatchcode() {
        //$productbatch = array();
        $item = $this->getData('item_id');
        $collection = Mage::getModel('batchcode/order_item')->getCollection()
                ->addFieldToFilter('item_id', $item)
                ->addFieldToSelect('batch_id')
                ->addFieldToSelect('id');
        foreach ($collection as $item) {
            $batch[] = Mage::getModel('batchcode/batchcode')->load($item->getBatchId());
        }
        return $batch;
    }

}
