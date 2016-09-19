<?php
/**
 * Yireo CheckoutTester for Magento
 *
 * @package     Yireo_CheckoutTester
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
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
     * @var Yireo_CheckoutTester_Helper_Data
     */
    protected $helper;

    /**
     * @var Mage_Sales_Model_Order
     */
    protected $orderModel;

    /**
     * @var Mage_Checkout_Model_Session
     */
    protected $checkoutSession;

    /**
     * Semiconstructor
     */
    protected function _construct()
    {
        $this->helper = Mage::helper('checkouttester');
        $this->orderModel = Mage::getModel('sales/order');

        parent::_construct();
    }

    /**
     * @return Mage_Checkout_Model_Session
     */
    public function getCheckoutSession() {
        if(!$this->checkoutSession) {
            $this->checkoutSession = Mage::getModel('checkout/session');
        }
        return $this->checkoutSession;
    }

    /**
     * Empty page action
     */
    public function indexAction()
    {
        // Not implemented
    }

    /**
     * Success page action
     */
    public function successAction()
    {
        // Check access
        if ($this->helper->hasAccess() == false) {
            die('Access denied');
        }
        // Check if module output is enabled
        if (!$this->helper->enabled()) {
            die('Module output disabled');
        }

        // Fetch the order
        $order = $this->getOrder();

        // Fail when there is no valid order
        if ($order === false) {
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

        $orderIdFromConfig = (int)$this->helper->getOrderIdFromConfig();
        $order = $this->loadOrder($orderIdFromConfig);
        if ($order) {
            return $order;
        }

        $lastOrderId = $this->helper->getLastInsertedOrderId();
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
        $order = $this->orderModel->load($orderId);
        if ($order->getId() > 0) {
            return $order;
        }

        $order = $this->orderModel->loadByIncrementId($orderId);
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
        $currentOrder = Mage::registry('current_order');
        if (empty($currentOrder)) {
            Mage::register('current_order', $order);
        }

        // Load the session with this order
        $this->getCheckoutSession()->setLastOrderId($order->getId())
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
