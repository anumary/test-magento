<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Model_Resource_Batchcode_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('batchcode/batchcode');
    }

    /**
     * Get all batch code
     * @return Bridge_Batchcode_Model_Resource_Batchcode_Collection
     */
    public function getAllBatchDetails() {
        $query = $this->join(
                'batchcode_product', 'main_table.entity_id = batchcode_product.batch_id', array(
            'product_id' => 'product_id'
                )
        );

        return $query;
    }

    /**
     * Get  batch code details by entity_id
     * @return Bridge_Batchcode_Model_Resource_Batchcode_Collection
     */
    public function getBatchDetails($id = 0) {
        $query = $this->join(
                'batchcode_product', 'main_table.entity_id = batchcode_product.batch_id', array(
            'product_id' => 'product_id'
                )
        );

        $query = $this->addFieldToFilter(
                'batchcode_product.batch_id', array(
            'eq' => $id
                )
        );

        return $query;
    }

    /**
     * Get  batch code details by product id
     * @return Bridge_Batchcode_Model_Resource_Batchcode_Collection
     */
    public function getBatchByProduct($product_id = 0) {
        $query = $this->join(
                'batchcode_product', 'main_table.entity_id = batchcode_product.batch_id', array(
            'product_id' => 'product_id'
                )
        );

        $query = $this
                ->addFieldToFilter('batchcode_product.product_id', array('eq' => $product_id))
                ->addFieldToFilter('main_table.enabled', array('eq' => 1))
                ->addFieldToFilter('main_table.onsales', array('eq' => 1))
                ->setOrder('main_table.sales_priority', 'asc')
        ;

        return $query;
    }

}
