<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Model_Order_Pdf_Items_Shipment_Default extends Mage_Sales_Model_Order_Pdf_Items_Shipment_Default {

    /**
     * Draw item line
     */
    public function draw() {
        $item = $this->getItem();

        $pdf = $this->getPdf();
        $page = $this->getPage();

        $lines = array();

        // draw Product name
        $lines[0] = array(array(
                'text' => Mage::helper('core/string')->str_split($item->getName(), 60, true, true),
                'feed' => 100,
        ));

        // draw QTY
        $lines[0][] = array(
            'text' => $item->getQty() * 1,
            'feed' => 35
        );

        // draw Batch Code

        $collection = Mage::getModel('batchcode/order_item')->getCollection()
                ->addFieldToFilter('item_id', $item[order_item_id])
                ->addFieldToSelect('batch_id')
                ->addFieldToSelect('id');
        if ($collection) {

            foreach ($collection as $slips) {
                $batch = Mage::getModel('batchcode/batchcode')->load($slips->getBatchId());
                $product = $batch->getBatchNumber();
                $expdate = $batch->getExpirationDate();
                $formatexp = date("d-m-Y", strtotime($expdate));
                $lines[][] = array(
                    'text' => $product . '[' . $formatexp . ']',
					'height'=>10,					
                    'feed' => 350
                );
            }
        } else {
            $errorcode = 'NO BATCHCODES';
            $lines[0][] = array(
                'text' => $errorcode,
                'feed' => 350
            );
        }

        // draw SKU
        $lines[0][] = array(
            'text' => Mage::helper('core/string')->str_split($this->getSku($item), 25),
            'feed' => 565,
            'align' => 'right'
        );

        // Custom options
        $options = $this->getItemOptions();
        if ($options) {
            foreach ($options as $option) {
                // draw options label
                $lines[][] = array(
                    'text' => Mage::helper('core/string')->str_split(strip_tags($option['label']), 70, true, true),
                    'font' => 'italic',
                    'feed' => 110
                );

                // draw options value
                if ($option['value']) {
                    $_printValue = isset($option['print_value']) ? $option['print_value'] : strip_tags($option['value']);
                    $values = explode(', ', $_printValue);
                    foreach ($values as $value) {
                        $lines[][] = array(
                            'text' => Mage::helper('core/string')->str_split($value, 50, true, true),
                            'feed' => 115
                        );
                    }
                }
            }
        }

        $lineBlock = array(
            'lines' => $lines,
            'height' => 20
        );

        $page = $pdf->drawLineBlocks($page, array($lineBlock), array('table_header' => true));
        $this->setPage($page);
    }

}
