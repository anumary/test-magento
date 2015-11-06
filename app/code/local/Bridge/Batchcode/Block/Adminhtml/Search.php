<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Search extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'batchcode';
        $this->_controller = 'adminhtml_batchcode';
        $this->_mode = 'search';

        $this->_addButton('search_customers', array(
            'label' => Mage::helper('batchcode')->__('Search'),
            'class' => 'save',
            'onclick' => 'submitform()',
                ), -100);

        $this->_removeButton('save', 'label', Mage::helper('batchcode')->__('Save Batchcode'));
        $this->_formScripts[] = "
                   function submitform()
                   {
                    edit_form.action = '" . $this->getUrl('adminhtml/report_type/index', array('id' => $id)) . "';
                    edit_form.action= edit_form.action.replace(\"report_type\",$('report_type').value);
                    editForm.submit();
            }
        ";
    }

    /**
     * Set and Get form header text
     *
     * @return string
     */
    public function getHeaderText() {
        return Mage::helper('batchcode')->__('Search by Batchcode');
    }

}
