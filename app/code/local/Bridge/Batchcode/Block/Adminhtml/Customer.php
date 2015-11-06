<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Customer extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $batch_code = $this->getBatchCode();

        $this->_controller = 'adminhtml_customer';
        $this->_blockGroup = 'batchcode';
        $this->_headerText = Mage::helper('batchcode')->__('Customers of batch code : ') . $batch_code;
        parent::__construct();
        $this->_removeButton('add');
    }

    /**
     * Get current batch number
     *
     * @return string
     */
    public function getBatchCode()
    {
        $params = $this->getRequest()->getPost();
        $batch_number = $params['batch_number'];
        if (!$batch_number) {
            $id = (int) $params['entity_id'];
            if (!$id) {
                $id = $this->getRequest()->getParam('id', null); //Get batch id
            }
            if ($id) {
                $batch_number = Mage::getModel('batchcode/batchcode')
                                ->load($id)
                                ->getBatchNumber();
            }
        }

        if (!$batch_number) {
            $batch_number = Mage::helper('batchcode')->__('Invalid');
        }

        return $batch_number;
    }

}
