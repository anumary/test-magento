<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Order_Grid extends Mage_Adminhtml_Block_Widget_Grid
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

        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $collection->addFieldToFilter('entity_id', array('in' => $orderIds));
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('real_order_id', array(
            'header' => Mage::helper('sales')->__('Order #'),
            'width' => '80px',
            'type' => 'text',
            'index' => 'increment_id',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header' => Mage::helper('sales')->__('Purchased From (Store)'),
                'index' => 'store_id',
                'type' => 'store',
                'store_view' => true,
                'display_deleted' => true,
            ));
        }

        $this->addColumn('created_at', array(
            'header' => Mage::helper('sales')->__('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '100px',
        ));

        $this->addColumn('billing_name', array(
            'header' => Mage::helper('sales')->__('Bill to Name'),
            'index' => 'billing_name',
        ));

        $this->addColumn('shipping_name', array(
            'header' => Mage::helper('sales')->__('Ship to Name'),
            'index' => 'shipping_name',
        ));

        $this->addColumn('base_grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Base)'),
            'index' => 'base_grand_total',
            'type' => 'currency',
            'currency' => 'base_currency_code',
        ));

        $this->addColumn('grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Purchased)'),
            'index' => 'grand_total',
            'type' => 'currency',
            'currency' => 'order_currency_code',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'status',
            'type' => 'options',
            'width' => '70px',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {

            $this->addColumn('action',
                    array(
                        'header' => Mage::helper('sales')->__('Action'),
                        'width' => '50px',
                        'type' => 'action',
                        'getter' => 'getId',
                        'actions' => array(
                            array(
                                'caption' => Mage::helper('sales')->__('View'),
                                'url' => array('base' => 'adminhtml/sales_order/view'),
                                'field' => 'order_id'
                            )
                        ),
                        'filter' => false,
                        'sortable' => false,
                        'index' => 'stores',
                        'is_system' => true,
            ));
        }

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
        $this->getMassactionBlock()->setFormFieldName('order_ids');

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

    public function getRowUrl($row)
    {
        $url = $this->getUrl('adminhtml/sales_order/view', array('order_id' => $row->getId()));

        return $url;
    }

}
