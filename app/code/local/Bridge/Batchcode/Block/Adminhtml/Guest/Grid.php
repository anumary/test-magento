<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Guest_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_batch_code = '';
    protected $_entity_id = 0;

    public function __construct()
    {
        parent::__construct();
        $this->setId('sales_order_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Get the selected batch code
     *
     * @return string
     */
    public function getBatchCode()
    {
        return $this->_batch_code;
    }

    /**
     * Get the selected batch id
     *
     * @return integer
     */
    public function getBatchId()
    {
        return $this->_entity_id;
    }

    /**
     * Get the selected batch ids as array
     *
     * @return array
     */
    public function getAllBatchId()
    {
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
     * Retrieve collection class
     *
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'sales/order_grid_collection';
    }

    protected function _prepareCollection()
    {
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

        $collection = Mage::getResourceModel('sales/order_collection')
                        ->addAttributeToSelect('*')
                        ->addAddressFields()
                        ->addFieldToFilter('entity_id', array('in' => $orderIds))
                        ->addFieldToFilter('customer_is_guest', array('eq' => 1))
        ;
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('customer_firstname', array(
            'header' => Mage::helper('sales')->__('First Name'),
            'index' => 'customer_firstname',
            'type' => 'text',
            'width' => '100px',
        ));

        $this->addColumn('customer_lastname', array(
            'header' => Mage::helper('sales')->__('Last Name'),
            'index' => 'customer_lastname',
            'type' => 'text',
            'width' => '100px',
        ));
        $this->addColumn('customer_email', array(
            'header' => Mage::helper('sales')->__('Email'),
            'index' => 'customer_email',
            'type' => 'text',
            'width' => '100px',
        ));
        $this->addColumn('telephone', array(
            'header' => Mage::helper('sales')->__('Telephone'),
            'index' => 'telephone',
            'type' => 'text',
            'filter' => false,
            'sortable' => false,
            'width' => '100px',
        ));
        $this->addColumn('postcode', array(
            'header' => Mage::helper('sales')->__('Postcode'),
            'index' => 'postcode',
            'filter' => false,
            'sortable' => false,
            'type' => 'text',
            'width' => '100px',
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));

        return parent::_prepareColumns();
    }

    /**
     * Prepare grid massaction block
     *
     * @return Bridge_Batchcode_Block_Adminhtml_Customer_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('customer');

        return $this;
    }

    /**
     * Grid url getter
     * @return string

     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true, 'id' => $this->getBatchId(), 'batch_number' => $this->getBatchCode()));
    }

}
