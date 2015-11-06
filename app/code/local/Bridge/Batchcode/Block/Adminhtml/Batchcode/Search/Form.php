<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Batchcode_Search_Form extends Mage_Adminhtml_Block_Widget_Form {

    /**
     * Prepare form before rendering HTML
     *
     * @return Bridge_Batchcode_Block_Adminhtml_Batchcode_Search_Form
     */
    protected function _prepareForm() {
        $collection = $this->_prepareCollection();
        $data[0] = Mage::helper('batchcode')->__('Select...');
        foreach ($collection as $batch) {
            $data[$batch->getEntityId()] = $batch->getBatchNumber();
        }

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/adminhtml_report_type/index', array('id' => $id)),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
        ));
        $form->setUseContainer(true);

        $this->setForm($form);

        $fieldset = $form->addFieldset('search_form_fieldset', array(
            'legend' => Mage::helper('batchcode')->__('Batchcode Information')
        ));
        $fieldset->addField('report_type', 'select', array(
            'label' => Mage::helper('batchcode')->__('Report Type'),
            'title' => Mage::helper('batchcode')->__('Report Type'),
            'name' => 'report_type',
            'required' => false,
            'options' => array(
                'customer' => 'Customers',
                'guest' => 'Guests',
                'order' => 'Orders'),
        ));
        $fieldset->addField('entity_id', 'select', array(
            'label' => Mage::helper('batchcode')->__('Batch Code'),
            'title' => Mage::helper('batchcode')->__('Batch Code'),
            'name' => 'entity_id',
            'required' => false,
            'options' => $data,
        ));

        $fieldset->addField('note', 'note', array(
            'text' => Mage::helper('batchcode')->__('OR'),
        ));

        $fieldset->addField('batch_number', 'text', array(
            'required' => false,
            'name' => 'batch_number'
        ));

        return parent::_prepareForm();
    }

    /**
     * Prepare form collection object
     *
     * @return this
     */
    protected function _prepareCollection() {
        $model = Mage::getModel('batchcode/batchcode');
        $collection = $model->getCollection();

        return $collection;
    }

}
