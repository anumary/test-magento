<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Model_Product extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('batchcode/batchcode_order_item');
    }
}