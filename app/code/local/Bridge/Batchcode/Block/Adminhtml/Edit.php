<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'batchcode';
        $this->_controller = 'adminhtml_batchcode';
        $this->_mode = 'edit';

        $this->_addButton('save_and_continue', array(
            'label' => Mage::helper('batchcode')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);

        $this->_updateButton('save', 'label', Mage::helper('batchcode')->__('Save Batchcode'));
        $this->_formScripts[] = "
            function toggleEditor()
            {
                if (tinyMCE.getInstanceById('form_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'edit_form');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'edit_form');
                }
            }

            function saveAndContinueEdit()
            {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * Make the header text
     *
     * @return string
     */
    public function getHeaderText() {
        if (Mage::registry('batchcode_data') && Mage::registry('batchcode_data')->getId()) {
            return Mage::helper('batchcode')->__('Edit Batchcode ') . Mage::helper('batchcode')->__("%s", $this->htmlEscape(Mage::registry('batchcode_data')->getBatchNumber()));
        } else {
            return Mage::helper('batchcode')->__('New Batchcode');
        }
    }

}
