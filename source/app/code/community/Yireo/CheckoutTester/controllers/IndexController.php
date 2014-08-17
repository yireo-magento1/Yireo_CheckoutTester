<?php
/**
 * Yireo CheckoutTester for Magento 
 *
 * @package     Yireo_CheckoutTester
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright (C) 2014 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * CheckoutTester frontend controller
 *
 * @category    CheckoutTester
 * @package     Yireo_CheckoutTester
 */
class Yireo_CheckoutTester_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Empty page
     *
     */
    public function indexAction()
    {
        echo 'not implemented';exit;
    }

    /**
     * Success page
     *
     */
    public function successAction()
    {
        // Check access
        if(Mage::helper('checkouttester')->hasAccess() == false) {
            die('Access denied');
        }

        // Load the order ID
        $orderId = (int)Mage::helper('checkouttester')->getOrderId();
        $urlId = (int)$this->getRequest()->getParam('order_id');
        if(!empty($urlId)) {
            $orderId = $urlId;
        }

        // Load the order
        $order = Mage::getModel('sales/order')->load($orderId);
        // Order on the order
        if(!$order->getId() > 0) {
            die('Invalid order ID');
        }

        // Load the session
        Mage::getModel('checkout/session')->setLastOrderId($order->getId())
            ->setLastRealOrderId($order->getIncrementId());

        // Load the layout
        $this->loadLayout();

        // Optionally dispatch an event
        if((bool)Mage::getStoreConfig('checkouttester/settings/checkout_onepage_controller_success_action')) {
            Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($orderId)));
        }

        // Render the layout
        $this->renderLayout();
    }
}
