<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Batchcode_Batches extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('batchcodeProduct');
        $this->setUseAjax(false);
        $this->setDefaultSort('entity_id');
        $this->setSaveParametersInSession(false);

        $this->setPagerVisibility(false);
        $this->setFilterVisibility(false);
        $this->setHeadersVisibility(false);
    }

    /**
     * Prepare grid collection object
     *
     * @return this
     */
    protected function _prepareCollection()
    {
        $product_id = $this->getData('product_id');
        $collection = Mage::getModel('batchcode/batchcode')
                        ->getCollection()
                        ->getBatchByProduct($product_id)
        ;
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('name', array(
            'header' => Mage::helper('batchcode')->__('Batch Number'),
            'align' => 'left',
            'index' => 'batch_number',
            'width' => '50px',
        ));

        $this->addColumn('content', array(
            'header' => Mage::helper('batchcode')->__('Qty'),
            'width' => '50px',
            'index' => 'qty',
        ));

        $this->addColumn('entity_id', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'entity_id',
            'inline_css' => 'checkbox entities',
            'field_name' => 'entity_id',
            'values' => $this->getSelectedProducts(),
            'align' => 'center',
            'index' => 'entity_id',
            'use_index' => true,
        ));

        return parent::_prepareColumns();
    }

}
