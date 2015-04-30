<?php
/**
 * Yireo CheckoutTester for Magento
 *
 * @package     Yireo_CheckoutTester
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
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
     * Empty page action
     */
    public function indexAction()
    {
        echo 'not implemented';
        exit;
    }

    /**
     * Success page action
     */
    public function successAction()
    {
        // Check access
        if (Mage::helper('checkouttester')->hasAccess() == false) {
            die('Access denied');
        }

        // Fetch the order
        $order = $this->getOrder();

        // Fail when there is no valid order
        if ($order == false) {
            die('Invalid order ID');
        }

        // Register this order
        $this->registerOrder($order);

        // Load the layout
        $this->loadLayout();

        // Render the layout
        $this->renderLayout();
    }

    /**
     * Method to fetch the current order
     *
     * @return Mage_Sales_Model_Order | false
     */
    protected function getOrder()
    {
        $orderIdFromUrl = (int)$this->getRequest()->getParam('order_id');
        $order = $this->loadOrder($orderIdFromUrl);
        if ($order) {
            return $order;
        }

        $orderIdFromConfig = (int)Mage::helper('checkouttester')->getOrderIdFromConfig();
        $order = $this->loadOrder($orderIdFromConfig);
        if ($order) {
            return $order;
        }

        $lastOrderId = Mage::helper('checkouttester')->getLastInsertedOrderId();
        $order = $this->loadOrder($lastOrderId);
        if ($order) {
            return $order;
        }

        return false;
    }

    /**
     * Method to try to load an order from an unvalidated ID
     *
     * @param int $orderId
     *
     * @return Mage_Sales_Model_Order | false
     */
    protected function loadOrder($orderId)
    {
        $order = Mage::getModel('sales/order')->load($orderId);
        if ($order->getId() > 0) {
            return $order;
        }

        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        if ($order->getId() > 0) {
            return $order;
        }

        return false;
    }

    /**
     * Method to register the order in this session
     *
     * @param Mage_Sales_Model_Order $order
     */
    protected function registerOrder($order)
    {
        // Register this order as the current order
        Mage::register('current_order', $order);

        // Load the session with this order
        Mage::getModel('checkout/session')->setLastOrderId($order->getId())
            ->setLastRealOrderId($order->getIncrementId());

        // Optionally dispatch an event
        $this->dispatchEvents($order);
    }

    /**
     * Method to optionally dispatch order-related events
     *
     * @param Mage_Sales_Model_Order $order
     */
    public function dispatchEvents($order)
    {
        if ((bool)Mage::getStoreConfig('checkouttester/settings/checkout_onepage_controller_success_action')) {
            Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($order->getId())));
        }
    }
}
