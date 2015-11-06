<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Model_Batchcode extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('batchcode/batchcode');
    }

    /**
     * Reset  data
     * @return Bridge_Batchcode_Model_Batchcode
     */
    public function reset()
    {
        $this->setData(array());
        $this->setOrigData();
        $this->_attributes = null;

        return $this;
    }

    /**
     * Prepare form before rendering HTML
     * @return Bridge_Batchcode_Model_Batchcode
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('form_form',
                        array(
                            'legend' => Mage::helper('batchcode')->__('Item information')
                        )
        );

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('batchcode')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));

        return parent::_prepareForm();
    }

}
