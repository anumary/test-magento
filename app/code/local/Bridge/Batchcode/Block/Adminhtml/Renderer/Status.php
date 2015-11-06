<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    /**
     * Renders grid column
     *
     * @param  Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row) {
        $data = $row->getData($this->getColumn()->getIndex());
        $status = ($data ? $this->helper('batchcode')->__('YES') : $this->helper('batchcode')->__('NO'));

        return $status;
    }

}
