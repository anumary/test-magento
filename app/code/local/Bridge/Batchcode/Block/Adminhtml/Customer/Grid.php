<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Customer_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    protected $_batch_code = '';
    protected $_entity_id = 0;

    public function __construct() {
        parent::__construct();
        $this->setId('customerGrid');
        $this->setUseAjax(true);
        $this->setDefaultSort('entity_id');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Get the selected batch code
     *
     * @return string
     */
    public function getBatchCode() {
        return $this->_batch_code;
    }

    /**
     * Get the selected batch id
     *
     * @return integer
     */
    public function getBatchId() {
        return $this->_entity_id;
    }

    /**
     * Get the selected batch ids as array
     *
     * @return array
     */
    public function getAllBatchId() {
        $i = 0;
        $id = array();
        $id[$i] = $this->getRequest()->getParam('id', null); //Get batch id
        if (!$id[$i]) {
            $params = $this->getRequest()->getPost();
            $id[$i] = (int) $params['entity_id'];
            $batch_number = ($params['batch_number'] ? $params['batch_number'] : $this->getRequest()->getParam('batch_number', null));
            if (!$id[$i]) {
                $this->_batch_code = $batch_number;
                $collection = Mage::getModel('batchcode/batchcode')
                        ->getCollection()
                        ->addFieldToFilter('batch_number', $batch_number)
                        ->getData();
                foreach ($collection as $data) {
                    $id[$i] = $data['entity_id'];
                    $i++;
                }
            } else {
                $this->_entity_id = $id[$i];
            }
        } else {
            $this->_entity_id = $id[$i];
        }

        return $id;
    }

    /**
     * Prepare grid collection object
     *
     * @return this
     */
    protected function _prepareCollection() {
        $id = array();
        $id = $this->getAllBatchId();
        $batchorder_item = Mage::getModel('batchcode/order_item');

        $alldata = $batchorder_item->getCollection()
                ->addFieldToFilter('batch_id', array('in' => $id))
                ->getData()
        ;

        $orderIds = array();

        foreach ($alldata as $data) {
            $orderIds[] = $data[order_id];
        }

        /*
         * Now get all unique customers from the orders of these items.
         */
        $orderCollection = Mage::getResourceModel('sales/order_collection')
                ->addFieldToFilter('entity_id', array('in' => $orderIds))
                ->addFieldToFilter('customer_id', array('neq' => 'NULL'))
        ;
        $customers = array();
        foreach ($orderCollection as $order) {
            $customerIds[] = $order->getCustomerId();
        }

//Only populates registered customers
        $collection = Mage::getResourceModel('customer/customer_collection')
                ->addNameToSelect()
                ->addAttributeToSelect('email')
                ->addAttributeToSelect('created_at')
                ->addAttributeToSelect('group_id')
                ->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
                ->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
                ->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
                ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
                ->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left')
                ->addFieldToFilter('entity_id', array('in' => $customerIds));

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('entity_id', array(
            'header' => Mage::helper('customer')->__('ID'),
            'width' => '50px',
            'index' => 'entity_id',
            'type' => 'number',
        ));
        $this->addColumn('name', array(
            'header' => Mage::helper('customer')->__('Name'),
            'index' => 'name'
        ));
        $this->addColumn('email', array(
            'header' => Mage::helper('customer')->__('Email'),
            'width' => '150',
            'index' => 'email'
        ));

        $groups = Mage::getResourceModel('customer/group_collection')
                ->addFieldToFilter('customer_group_id', array('gt' => 0))
                ->load()
                ->toOptionHash();

        $this->addColumn('group', array(
            'header' => Mage::helper('customer')->__('Group'),
            'width' => '100',
            'index' => 'group_id',
            'type' => 'options',
            'options' => $groups,
        ));

        $this->addColumn('Telephone', array(
            'header' => Mage::helper('customer')->__('Telephone'),
            'width' => '100',
            'index' => 'billing_telephone'
        ));

        $this->addColumn('billing_postcode', array(
            'header' => Mage::helper('customer')->__('ZIP'),
            'width' => '90',
            'index' => 'billing_postcode',
        ));

        $this->addColumn('billing_country_id', array(
            'header' => Mage::helper('customer')->__('Country'),
            'width' => '100',
            'type' => 'country',
            'index' => 'billing_country_id',
        ));

        $this->addColumn('billing_region', array(
            'header' => Mage::helper('customer')->__('State/Province'),
            'width' => '100',
            'index' => 'billing_region',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('website_id', array(
                'header' => Mage::helper('customer')->__('Website'),
                'align' => 'center',
                'width' => '80px',
                'type' => 'options',
                'options' => Mage::getSingleton('adminhtml/system_store')->getWebsiteOptionHash(true),
                'index' => 'website_id',
            ));
        }
        $this->addExportType('adminhtml/customer/exportCsv', Mage::helper('customer')->__('CSV'));
        $this->addExportType('adminhtml/customer/exportXml', Mage::helper('customer')->__('Excel XML'));

        return parent::_prepareColumns();
    }

    /**
     * Prepare grid massaction block
     *
     * @return Bridge_Batchcode_Block_Adminhtml_Customer_Grid
     */
    protected function _prepareMassaction() {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()
                ->setFormFieldName('customer');

        return $this;
    }

    /**
     * Grid url getter
     * @return string

     */
    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array('_current' => true, 'id' => $this->getBatchId(), 'batch_number' => $this->getBatchCode()));
    }

}
