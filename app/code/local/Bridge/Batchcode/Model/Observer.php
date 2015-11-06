<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Model_Observer
{
    const FLAG_SHOW_CONFIG = 'showConfig';
    const FLAG_SHOW_CONFIG_FORMAT = 'showConfigFormat';

    private $request;

    public function checkForConfigRequest($observer)
    {
    }

    public function test($observer)
    {
    }

    public function ModuleStatus($observer)
    {
    }

 public function salesOrderShipmentSaveAfter(Varien_Event_Observer $observer)
    {
         if( Mage::getSingleton('core/session')->getBatchAssign()) {
              Mage::getSingleton('core/session')->setBatchAssign(false);
         }
        elseif (Mage::registry('batch_assigned_to_items')){
        Mage::unregister('batch_assigned_to_items');
         }else
         {
        Mage::register('batch_assigned_to_items',true);
        $shipment = $observer->getEvent()->getShipment();
        $post = $observer->getEvent()->getPost();
        $selectedbatches = $post['batch_id'];
        $order = $shipment->getOrder();
        $storeId = $order->getStoreId();
        $orderId = $order->getId();
        $items = $shipment->getItemsCollection();       
        foreach ($items as $order_item) {
        $itemid = $order_item->getOrderItemId();
        $qty_toship = $order_item->getQty();

            $productId = $order_item->getProductId();
            $qty_waiting = $qty_toship;


          /*  $batches = $batchmodel
                        ->getCollection()
                        ->getBatchByProduct($productId);
            */
            $batches = $selectedbatches[$itemid];
            foreach ($batches as $batchid) {
          //  $batchid = $batchdetails->getId();
              $batchdetails =  Mage::getModel('batchcode/batchcode')->load($batchid);
                $available_qty = $batchdetails->getQty();
                if ($qty_waiting > 0) {
                    if ($qty_waiting > $available_qty) {
                        $shipped_qty = $available_qty;
                    } else {
                        $shipped_qty = $qty_waiting;
                    }
                    $finalqty = $available_qty - $shipped_qty;
                    $status = ($finalqty ? 1 : 0);
                    $batchdetails
                            ->setQty($finalqty)
                            ->setOnsales($status)
                            ->setEnabled($status)
                            ->save();
        $batchorder_item = Mage::getModel('batchcode/order_item');
                    $batchorder_item
                           // ->load($id)
                            //->setId($id)
                         //   ->load()
                            ->setItemId($itemid)
                            ->setOrderId($orderId)
                            ->setBatchId($batchid)
                            ->setProductId($productId)
                            ->setQty($shipped_qty)
                            ->save();

                    $qty_waiting = $qty_waiting - $available_qty;
                }
            }
        }
         }
           return;
    }

    private function setHeader()
    {
        $format = isset($this->request->{self::FLAG_SHOW_CONFIG_FORMAT}) ?
                $this->request->{self::FLAG_SHOW_CONFIG_FORMAT} : 'xml';
        switch ($format) {
            case 'text':
                header("Content-Type: text/plain");
                break;
            default:
                header("Content-Type: text/xml");
        }
    }

    private function outputConfig()
    {
        die(Mage::app()->getConfig()->getNode()->asXML());
    }

}
