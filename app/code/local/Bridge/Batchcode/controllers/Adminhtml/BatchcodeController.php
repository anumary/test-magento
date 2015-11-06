<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Adminhtml_BatchcodeController extends Mage_Adminhtml_Controller_Action {

    protected function _initBatchcode($idFieldName = 'id') {
        $batchcodeId = (int) $this->getRequest()
                        ->getParam($idFieldName);
        if ($batchcodeId) {
            $batchcode = Mage::getModel('batchcode/batchcode')
                    ->load($batchcodeId);
        }

        Mage::register('current_batchcode', $batchcode);

        return $this;
    }

    /**
     * Admin area batch code entry point
     * Always redirects to the startup page url
     * Provide batch code grid view
     */
    public function indexAction() {
        $this->_title($this->__('Batchcode'))->_title($this->__('Manage Batchcode'));

        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }
        $this->loadLayout();
        $this->_setActiveMenu('catalog');
        $this->renderLayout();
    }

    /**
     * Ajax callback for grid actions
     */
    public function gridAction() {
        $this->loadLayout();
        $this->getResponse()->setBody(
                $this->getLayout()
                        ->createBlock('batchcode/adminhtml_batchcode_grid')
                        ->toHtml()
        );
    }

    /**
     * Batchcode new action
     * Form to add new batch code
     */
    public function newAction() {
        $this->_title($this->__('Batchcode'))->_title($this->__('New Batchcode'));

        $this->loadLayout();

        $this->_setActiveMenu('catalog');

        $this->renderLayout();
    }

    /**
     * Batchcode edit action
     * Form to edit batch code
     */
    public function editAction() {
        $this->_title($this->__('Batchcode'))->_title($this->__('Edit Batchcode'));

        $id = $this->getRequest()->getParam('id', null);
        $model = Mage::getModel('batchcode/batchcode');
        if ($id) {
            $model->load((int) $id);

            if ($model->getId()) {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);

                if ($data) {
                    $model->setData($data)->setId($id);
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('batchcode')->__('Batchcode does not exist'));
                $this->_redirect('*/*/');
            }
        }
        Mage::register('batchcode_data', $model);

        $this->loadLayout();
        $this->_setActiveMenu('catalog');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * Batchcode search action
     * Form to search customers by batch code
     */
    public function searchAction() {
        $model = Mage::getModel('batchcode/batchcode');
        Mage::register('batchcode_data', $model);
        $this->loadLayout();
        $this->_setActiveMenu('catalog');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * Batchcode delete action
     * Delete batch code
     */
    public function deleteAction() {
        $this->_initBatchcode();
        $batchcode = Mage::registry('current_batchcode');
$batchcodeId = $batchcode->getId();
        if ($batchcodeId) {
            try {
                 $prev_qty = $batchcode->getQty();
                 $batchproduct = Mage::getModel('batchcode/product')
                    ->load($batchcodeId, 'batch_id');
            $prev_product_id = $batchproduct->getProductId();
                $batchcode->delete();

                $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($prev_product_id);
if ( ($stockItem->getId() > 0) && ($stockItem->getManageStock())) {
	$qty = $stockItem->getQty();
        $qty_new = $qty-$prev_qty;
	$stockItem->setQty($qty_new);
	$stockItem->save();
}



                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('batchcode')->__('The batchcode has been deleted.'));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Batchcode massdelete action
     * Delete multiple batch codes
     */
    public function massDeleteAction() {
        $batchcodesIds = $this->getRequest()->getParam('batchcode');

        if (!is_array($batchcodesIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('batchcode')->__('Please select batchcode(s).'));
        } else {
            try {
                $batchcode = Mage::getModel('batchcode/batchcode');
                foreach ($batchcodesIds as $batchcodeId) {
                    $batchcode
                            ->reset()
                            ->load($batchcodeId);
                    $prev_qty = $batchcode->getQty();
                    $batchproduct = Mage::getModel('batchcode/product')
                    ->load($batchcodeId, 'batch_id');
            $prev_product_id = $batchproduct->getProductId();
                            $batchcode->delete();
           $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($prev_product_id);
if ( ($stockItem->getId() > 0) && ($stockItem->getManageStock())) {
	$qty = $stockItem->getQty();
        $qty_new = $qty-$prev_qty;
	$stockItem->setQty($qty_new);
	$stockItem->save();
}
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('batchcode')->__('Total of %d record(s) were deleted.', count($batchcodesIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Batchcode save action
     */
    public function saveAction() {
        try {
  $prev_qty = 0;
            $params = $this->getRequest()->getPost();
            $get = $this->getRequest()->getParams();
            $id = $params['batch_entity_id'];
            $edit = false;
            if ($id) {
                $batchedit = Mage::getModel('batchcode/batchcode')
                        ->load($id);
                $action = Mage::helper('batchcode')->__('Edited');
                $prev_qty = $batchedit->getQty();
                $edit = true;
            } else {
                $batchedit = Mage::getModel('batchcode/batchcode');
                $action = Mage::helper('batchcode')->__('Added');
            }

            $batch_number = $params['batch_number'];
            $productId = $params['product_id'];
            $batchqty = $params['qty'];
            $batchorder = $params['salespriority'];
            $expirationdate = $params['expiration_date'];
            $status = $params['onsales'];
            $this->_getSession()->addSuccess($this->__('The Batch code has been successfully ' . $action));
            $batchedit
            //= Mage::getModel('batchcode/batchcode')
               //   ->load($id, 'entity_id')
                    ->setSalesPriority($batchorder)
                    ->setBatchNumber($batch_number)
                    ->setExpirationDate($expirationdate)
                    ->setQty($batchqty)
                    ->setOnsales($status)
                    ->setEnabled($status)
                    ->save();
            $id = $batchedit->getId();
            $batchproduct = Mage::getModel('batchcode/product')
                    ->load($id, 'batch_id');
            $prev_product_id = $batchproduct->getProductId();
            $batchproduct ->setBatchId($id)
                    ->setProductId($productId)
                    ->save();



            if(($productId > 0) )
            {
           if($batchqty > $prev_qty)
            {
$to_add = round($batchqty-$prev_qty);
$to_subtract = 0;
            }  else {
               $to_add = 0;
               $to_subtract =  round($prev_qty-$batchqty);
            }

     if($prev_product_id == $productId)
     {
     $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
if ( ($stockItem->getId() > 0) && ($stockItem->getManageStock())) {
	$qty = $stockItem->getQty();
        $qty_new = $qty+$to_add-$to_subtract;
	$stockItem->setQty($qty_new);
	$stockItem->save();
}
     }
     else
     {
  $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($prev_product_id);
if ( ($stockItem->getId() > 0) && ($stockItem->getManageStock())) {
	$qty = $stockItem->getQty();
        $qty_new = $qty-$prev_qty;
	$stockItem->setQty($qty_new);
	$stockItem->save();
}


     $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
if ( ($stockItem->getId() > 0) && ($stockItem->getManageStock())) {
	$qty = $stockItem->getQty();
        $qty_new = $qty+$batchqty;
	$stockItem->setQty($qty_new);
	$stockItem->save();
}
     }

            }
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
        }
        if ($get['back']) {
            $this->_redirect('*/*/' . $get['back'], array('_current' => true, 'id' => $id));
        } else {
            $this->_redirect('*/*/index');
        }
    }

}
