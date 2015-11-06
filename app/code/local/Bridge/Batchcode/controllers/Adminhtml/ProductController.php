<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_Adminhtml_ProductController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Prepare block for chooser
     *
     * Product chooser grid
     */
    public function chooserAction()
    {
        $request = $this->getRequest();
        switch ($request->getParam('attribute')) {

            case 'skubatchcode':

                $block = $this->getLayout()->createBlock(
                                'batchcode/adminhtml_product', 'product',
                                array(
                                    'js_form_object' => $request->getParam('form'),
                        ));

                break;
            default:
                $block = false;
                break;
        }

        if ($block) {
            $this->getResponse()
                    ->setBody($block->toHtml()
            );
        }
    }

    protected function _isAllowed()
    {
        return true;
    }

}
