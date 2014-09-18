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

        // Fetch variables
        $lastOrderId = Mage::helper('checkouttester')->getLastOrderId();
        $urlId = (int)$this->getRequest()->getParam('order_id');

        // Load the order from setting or URL
        $orderId = (int)Mage::helper('checkouttester')->getOrderId();
        if(!empty($urlId)) $orderId = $urlId;
        $order = Mage::getModel('sales/order')->load($orderId);

        // Try to use this ID as an increment ID
        if(!$order->getId() > 0 && $orderId > $lastOrderId) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        }

        // Load the last order if this is still invalid
        if(!$order->getId() > 0 && $lastOrderId > 0) {
            $order = Mage::getModel('sales/order')->load($lastOrderId);
        }

        // Fail when there is still no order yet
        if(!$order->getId() > 0) {
            die('Invalid order ID');
        }

        Mage::register('current_order', $order);

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
