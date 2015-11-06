<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Batchcode_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    /**
     * @var Batch code entity id
     */
    protected $_entityid;

    /**
     * Class constructor
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Set value to @var Batch code entity id
     */
    public function setEntityId($entity) {
        $this->_entityid = $entity;
    }

    /**
     * Get value of @var Batch code entity id
     * @return integer
     */
    public function getEntityId() {
        return $this->_entityid;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return Bridge_Batchcode_Block_Adminhtml_Batchcode_Edit_Form
     */
    protected function _prepareForm() {
        $id = $this->getRequest()->getParam('id');

        $this->setEntityId($id);
        $data = $this->_prepareCollection();

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $id)),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
        ));

        $form->setUseContainer(true);

        $this->setForm($form);

        $fieldset = $form->addFieldset('edit_form_fieldset', array(
            'legend' => Mage::helper('batchcode')->__('Batchcode Information')
        ));

        $fieldset->addField('entity_id', 'hidden', array(
            'label' => Mage::helper('batchcode')->__('Batch No'),
            'class' => 'textbox',
            'required' => false,
            'name' => 'batch_entity_id',
        ));

        $fieldset->addField('batch_number', 'text', array(
            'label' => Mage::helper('batchcode')->__('Batch Code'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'batch_number',
        ));

        /* date field */
        $displaydateFormat = 'dd-MM-yyyy';
        $inputdateFormat = 'yyyy-MM-dd';
        $fieldset->addField('expiration_date', 'date', array(
            'name' => 'expiration_date',
            'required' => true,
            'label' => Mage::helper('batchcode')->__('Expiration Date'),
            'title' => Mage::helper('batchcode')->__('Expiration Date'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => $inputdateFormat,
            'format' => $displaydateFormat,
            'time' => false
        ));


        /* date field */
        $action = 'getProductChooser(\'' . Mage::getUrl(
                        'adminhtml/product/chooser/attribute/skubatchcode/form/rule_conditions_fieldset/id/' . $this->getRequest()->getParam('id'), array('_secure' => Mage::app()->getStore()->isAdminUrlSecure())
                ) . '?isAjax=true\');';

        $fieldset->addField('product_id', 'text', array(
            'label' => Mage::helper('batchcode')->__('Product Id'),
            'class' => '',
            'required' => false,
            'readonly' => true,
            'name' => 'product_id',
            'after_element_html' => '
               <img alt="Open Chooser" class="rule-chooser-trigger" src="' . $this->getSkinUrl('images/rule_chooser_trigger.gif') . '" id="openchooser" onclick="showproducts();" >

<img onclick="hideproducts();"  alt="Apply" class="v-middle" src="' . $this->getSkinUrl('images/rule_component_apply.gif') . '">
</div>
<div id="chosser-container"></div><script>$(\'chosser-container\').hide();
function hideproducts()
{
$(\'chosser-container\').hide();
}
function showproducts()
{
$(\'chosser-container\').show();
}
' . $action . '
</script>
'
        ));

        $fieldset->addField('qty', 'text', array(
            'label' => Mage::helper('batchcode')->__('Qty'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'qty',
        ));
        $fieldset->addField('sales_priority', 'text', array(
            'label' => Mage::helper('batchcode')->__('Priority'),
            'class' => '',
            'required' => false,
            'name' => 'salespriority',
            'note' => Mage::helper('batchcode')->__('The Priority of the batchcode for sale.'),
        ));
        $fieldset->addField('onsales', 'select', array(
            'name' => 'onsales',
            'label' => Mage::helper('batchcode')->__('Status'),
            'title' => Mage::helper('batchcode')->__('Status'),
            'required' => true,
            'values' => array(0 => 'Not On Sale', 1 => 'On Sale'),
        ));
        $form->setValues($data);

        return parent::_prepareForm();
    }

    /**
     * Prepare form collection object
     *
     * @return this
     */
    protected function _prepareCollection() {
        $model = Mage::getModel('batchcode/batchcode');
        $collection = $model->getCollection()
                ->getBatchDetails($this->getEntityId())
        ;

        $data = $collection->getData();

        return $data[0];
    }

}
