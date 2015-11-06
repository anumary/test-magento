<?php

/**
 * Bridge Batchcode
 *
 * @category      Bridge
 * @package       Bridge_Batchcode
 * @copyright     Copyright (c) 2013 Bridge India. (http://bridge-india.in)
 * @license       http://bridge-india.in/disclaimer/magento
 */
class Bridge_Batchcode_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Front end entry point
     * Always redirects to the startup page url
     */
     public function indexAction()
        {
            $this->getLayout();
            $this->renderLayout();
        }

}
