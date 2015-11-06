<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Batchcode extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_batchcode';
        $this->_blockGroup = 'batchcode';
        $this->_headerText = Mage::helper('batchcode')->__('Batch List');
        $this->_addButtonLabel = Mage::helper('batchcode')->__('Add New Batch');
        parent::__construct();
    }

}
