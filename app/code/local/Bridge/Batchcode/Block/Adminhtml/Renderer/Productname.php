<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Renderer_Productname extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    /**
     * Renders grid column
     *
     * @param  Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row) {
        $product_id = $row->getData($this->getColumn()->getIndex());
        $model = Mage::getModel('catalog/product') ;
        $_product = $model->load($product_id);              
        return $_product->getName();;
    }

}
