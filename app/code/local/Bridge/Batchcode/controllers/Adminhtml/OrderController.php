<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Adminhtml_OrderController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Admin area customer search result of batch code input
     * Provide customerse grid view
     */
    public function indexAction()
    {
        $this->_title($this->__('Batchcode'))->_title($this->__('Orders'));
        if ($this->getRequest()->getParam('ajax')) {
            $this->_forward('grid');

            return;
        }

        $this->loadLayout();

        $this->_setActiveMenu('catalog');

        $this->renderLayout();
    }

    /**
     * Admin area customer search result of batch code input
     * Provide customerse grid view
     * Ajax callback for grid actions
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
                        $this->getLayout()
                        ->createBlock('batchcode/adminhtml_order_grid')
                        ->toHtml()
        );
    }

    /**
     * Export customer grid to csv format
     */
    public function exportCsvAction()
    {
        $fileName = 'orders.csv';
        $content = $this->getLayout()
                        ->createBlock('batchcode/adminhtml_order_grid')
                        ->getCsvFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export customer grid to XML format
     */
    public function exportExcelAction()
    {
        $fileName = 'orders.xml';
        $content = $this->getLayout()
                        ->createBlock('batchcode/adminhtml_order_grid')
                        ->getExcelFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function addExportType($url, $label)
    {
        $this->_exportTypes[] = new Varien_Object(
                        array(
                            'url' => $this->getUrl($url,
                                    array(
                                        '_current' => true,
                                        'filter' => $this->getParam($this->getVarNameFilter(), null)
                                    )
                            ),
                            'label' => $label
                        )
        );

        return $this;
    }

    protected function _isAllowed()
    {
        return true;
    }

}
