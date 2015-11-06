<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Renderer_Qty extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    /**
     * Renders grid column
     *
     * @param  Varien_Object $row
     * @return intvalue
     */
    public function render(Varien_Object $row) {
        return $this->_getValue($row);
    }

    public function _getValue(Varien_Object $row) {
        $val = $row->getData($this->getColumn()->getIndex());
        return intval($val);
    }

}
