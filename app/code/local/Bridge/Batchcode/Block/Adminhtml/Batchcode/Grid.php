<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Batchcode_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct($arguments = array()) {
        parent::__construct($arguments);
        $this->setId('batchcodeGrid');
        $this->setUseAjax(true);
        $this->setDefaultSort('entity_id');
        $this->setSaveParametersInSession(true);
        
    }

    

    /**
     * Prepare grid collection object
     *
     * @return this
     */
    protected function _prepareCollection() {
        $collection = Mage::getModel('batchcode/batchcode')
                ->getCollection()
                ->getAllBatchDetails();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('entity_id', array(
            'header' => Mage::helper('batchcode')->__('ID'),
            'align' => 'right',
            'width' => '10px',
            'index' => 'entity_id',
        ));

        $this->addColumn('product_id', array(
            'header' => Mage::helper('batchcode')->__('Product ID'),
            'align' => 'right',
            'width' => '10px',
            'index' => 'product_id',
        ));

        /* new field */
        $this->addColumn('product_desc', array(
            'header' => Mage::helper('batchcode')->__('Product Description'),
            'align' => 'left',
            'width' => '100px',
            'index' => 'product_id',
            'renderer' => new Bridge_Batchcode_Block_Adminhtml_Renderer_Productname(),
            'filter_condition_callback' => array($this, '_productFilter')
        ));

        $this->addColumn('product_sku', array(
            'header' => Mage::helper('batchcode')->__('Product SKU'),
            'align' => 'left',
            'width' => '100px',
            'index' => 'product_id',
            'renderer' => new Bridge_Batchcode_Block_Adminhtml_Renderer_Sku(),
            'filter_condition_callback' => array($this, '_skuFilter')
        ));

        $this->addColumn('expiration_date', array(
            'header' => Mage::helper('batchcode')->__('Exp date'),
            'align' => 'left',
            'width' => '100px',
            'type'      => 'date',
            'filter_index' => 'expiration_date',
            'format'    => 'dd-MM-yyyy',
            'index' => 'expiration_date',
        ));
        $this->addColumn('supplier', array(
            'header' => Mage::helper('batchcode')->__('Supplier'),
            'align' => 'left',
            'index' => 'product_id',
            'width' => '150px',
            'renderer' => new Bridge_Batchcode_Block_Adminhtml_Renderer_Supplier(),
            'filter_condition_callback' => array($this, '_supplierFilter')
        ));
        /* new field */

        $this->addColumn('name', array(
            'header' => Mage::helper('batchcode')->__('Batch Number'),
            'align' => 'left',
            'index' => 'batch_number',
            'width' => '50px',
        ));

        $this->addColumn('qty', array(
            'header' => Mage::helper('batchcode')->__('Qty'),
            'width' => '80px',
            'index' => 'qty',
            'renderer' => new Bridge_Batchcode_Block_Adminhtml_Renderer_Qty()
        ));

        $this->addColumn('onsales', array(
            'header' => Mage::helper('batchcode')->__('Onsale'),
            'width' => '50px',
            'type' => 'options',
            'options' => array(
                Mage::helper('batchcode')->__('NO'),
                Mage::helper('batchcode')->__('YES')
            ),
            'renderer' => new Bridge_Batchcode_Block_Adminhtml_Renderer_Status(),
            'index' => 'onsales'
        ));

        $this->addColumn('priority', array(
            'header' => Mage::helper('batchcode')->__('Priority'),
            'width' => '50px',
            'index' => 'sales_priority',
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('batchcode')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('batchcode')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
                )
        );

        $this->addColumn('Customers', array(
            'header' => Mage::helper('batchcode')->__('Customers'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('batchcode')->__('Customers'),
                    'url' => array('base' => 'adminhtml/customer/index'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
                )
        );

//        $this->addColumn('Guests', array(
//            'header' => Mage::helper('batchcode')->__('Guests'),
//            'width' => '100',
//            'type' => 'action',
//            'getter' => 'getId',
//            'actions' => array(
//                array(
//                    'caption' => Mage::helper('batchcode')->__('Guests'),
//                    'url' => array('base' => '*/adminhtml_guest/index'),
//                    'field' => 'id'
//                )
//            ),
//            'filter' => false,
//            'sortable' => false,
//            'index' => 'stores',
//            'is_system' => true,
//                )
//        );

        $this->addColumn('Orders', array(
            'header' => Mage::helper('batchcode')->__('Orders'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('batchcode')->__('Orders'),
                    'url' => array('base' => 'adminhtml/order/index'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
                )
        );

        return parent::_prepareColumns();
    }

    /**
     * Prepare grid massaction actions
     */
    protected function _prepareMassaction() {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('batchcode');
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('batchcode')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('batchcode')->__('Are you sure?')
        ));

        return $this;
    }

    /**
     * Grid url getter
     *
     *
     * @return string current grid url
     */
    public function getGridUrl() {
        return $this->getUrl('*/*/*/grid', array('_current' => true));
    }

    /**
     * Grid row getter
     */
    public function getRowUrl($row) {
        return $this->getUrl('*/*/*/edit', array('id' => $row->getId()));
    }



    protected function _productFilter($collection, $column) {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }
        $orderitem = Mage::getModel('catalog/product')->getCollection();
        $orderitem->addFieldToFilter('name', array('like' => '%' . $value . '%'));
        $ids = array();
        foreach ($orderitem as $item) {
            $ids[] = $item->getId();
        }     
        
        if(empty($ids)) {
         $this->getCollection()->addFieldToFilter("entity_id", 0);          
        } else {
            $this->getCollection()->addFieldToFilter("product_id", array("in", $ids));
        }
        return $this;
    }

    protected function _skuFilter($collection, $column) {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }
        $orderitem = Mage::getModel('catalog/product')->getCollection();
        $orderitem->addFieldToFilter('sku', array('like' => '%' . $value . '%'));
        $ids = array();
        foreach ($orderitem as $item) {
            $ids[] = $item->getId();
        }     
        if (empty($ids)) {
         $this->getCollection()->addFieldToFilter("entity_id", 0);          
        } else {
            $this->getCollection()->addFieldToFilter("product_id", array("in", $ids));
        }
        return $this;
    }
    
    protected function _supplierFilter($collection, $column) {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }
        $orderitem = Mage::getModel('catalog/product')->getCollection();
        $orderitem->addFieldToFilter('leverancier', array('like' => '%' . $value . '%'));
        $ids = array();
        foreach ($orderitem as $item) {
            $ids[] = $item->getId();
        }     
        if (empty($ids)) {
         $this->getCollection()->addFieldToFilter("entity_id", 0);          
        } else {
            $this->getCollection()->addFieldToFilter("product_id", array("in", $ids))
		  			;
        }
        return $this;
    }
    
}
