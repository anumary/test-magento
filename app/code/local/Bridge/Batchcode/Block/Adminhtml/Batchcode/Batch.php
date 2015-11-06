<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Batchcode_Batch extends Mage_Adminhtml_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('Bridge/batchcode/batch.phtml');
    }

    /**
     * Get all batchcode of the shipped product
     *
     * @return Batchcode & Expiry date
     */
//    public function getBatchcode() {
//        $item = $this->getData('order_item_id');    
//        $batchitem = Mage::getModel('batchcode/order_item')->getCollection()
//                ->addFieldToFilter('item_id', $item)               
//                ->addFieldToSelect('id');
//        //$batchid = $batchitem->getId(); 
//        $ids = array();
//       foreach ($collection as $item) {
//            // $batchnames[] = Mage::getModel('batchcode/batchcode')->load($item->getBatchId())->getBatchNumber();       
//           $ids[] = $item->getId();
//       }
//        $batchproduct = Mage::getModel('batchcode/batchcode')->load($batchid, 'entity_id');
//        $product = $batchproduct->getBatchNumber();
//        $productexp = $batchproduct->getExpirationDate();
//        $Date = $productexp;
//        $expDate = date("d-m-Y", strtotime($Date));
//        return $product . ' (' . $expDate . ')';
//    }

    public function getBatchcode() {
        $item = $this->getData('order_item_id');
        $collection = Mage::getModel('batchcode/order_item')->getCollection()
                ->addFieldToFilter('item_id', $item)
                ->addFieldToSelect('batch_id')
                ->addFieldToSelect('id');
        foreach ($collection as $item) {
            $batch[] = Mage::getModel('batchcode/batchcode')->load($item->getBatchId());
        }
        return $batch;



//        $batchitem = Mage::getModel('batchcode/order_item')->load($item, 'item_id');
//        $id = $batchitem->getId();
//        //print_r($batchid1);
//        $batchitem = Mage::getModel('batchcode/order_item')->load($id);
//        $batchid = $batchitem->getBatchId();
//        $batchproduct = Mage::getModel('batchcode/batchcode')->load($batchid, 'entity_id');
//        $product = $batchproduct->getBatchNumber();
//        $productexp = $batchproduct->getExpirationDate();
//        if ($product) {
//            $Date = $productexp;
//            $expDate = date("d-m-Y", strtotime($Date));
//            return $product . ' (' . $expDate . ')';
//        } else {
//            return 'No batchcode';
//        }
    }

}
