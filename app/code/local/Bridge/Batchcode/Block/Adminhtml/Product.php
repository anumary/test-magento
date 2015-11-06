<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Block_Adminhtml_Product extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct($arguments = array()) {
        parent::__construct($arguments);
        if ($this->getRequest()->getParam('current_grid_id')) {
            $this->setId($this->getRequest()->getParam('current_grid_id'));
        } else {
            $this->setId('skuChooserGrid_' . $this->getId());
        }

        $form = $this->getJsFormObject();

        $this->setRowClickCallback("$form.chooserGridRowClick.bind($form)");
        $this->setCheckboxCheckCallback("$form.chooserGridCheckboxCheck.bind($form)");
        $this->setRowInitCallback("$form.chooserGridRowInit.bind($form)");
        $this->setDefaultSort('sku');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
        if ($this->getRequest()->getParam('collapse')) {
            $this->setIsCollapsed(true);
        }
    }

    /**
     * Retrieve quote store object
     * @return Mage_Core_Model_Store
     */
    public function getStore() {
        return Mage::app()->getStore();
    }

    protected function _addColumnFilterToCollection($column) {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_products') {
            $selected = $this->_getSelectedProducts();
            if (empty($selected)) {
                $selected = '';
            }

            if ($column->getFilter()->getValue()) {
                $this->getCollection()
                        ->addFieldToFilter('entity_id', array(
                            'in' => $selected
                ));
            } else {
                $this->getCollection()
                        ->addFieldToFilter('entity_id', array(
                            'nin' => $selected
                ));
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * Prepare Catalog Product Collection
     *
     * @return Bridge_Batchcode_Block_Adminhtml_Product
     */
    protected function _prepareCollection() {
        $collection = Mage::getResourceModel('catalog/product_collection')
                ->setStoreId(0)
                ->addAttributeToSelect('name', 'type_id', 'attribute_set_id');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Define Cooser Grid Columns and filters
     *
     * @return Bridge_Batchcode_Block_Adminhtml_Product
     */
    protected function _prepareColumns() {
        $this->setSelectedProducts($this->_getSelectedProducts());

        $this->addColumn('in_products', array(
            'header_css_class' => 'head-massaction',
            'type' => 'radio',
            'html_name' => 'in_products',
            'values' => $this->getSelectedProducts(),
            'align' => 'center',
            'index' => 'entity_id'
        ));

        $this->addColumn('entity_id', array(
            'header' => Mage::helper('sales')->__('ID'),
            'sortable' => true,
            'width' => '60px',
            'index' => 'entity_id',
        ));

        $this->addColumn('chooser_sku', array(
            'header' => Mage::helper('sales')->__('SKU'),
            'name' => 'chooser_sku',
            'width' => '80px',
            'index' => 'sku'
        ));
        $this->addColumn('chooser_name', array(
            'header' => Mage::helper('sales')->__('Product Name'),
            'name' => 'chooser_name',
            'index' => 'name'
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/chooser', array(
                    '_current' => true,
                    'current_grid_id' => $this->getId(),
                    'collapse' => null
        ));
    }

    protected function _getSelectedProducts() {
        $products = $this->getRequest()->getPost('selected', array());
        if (!$products) {

            $id = $this->getRequest()->getParam('id');
            $batchproduct = Mage::getModel('batchcode/product')
                    ->load($id, 'batch_id')
                    ->getProductId();

            $products[] = $batchproduct;
        }

        return $products;
    }

}
