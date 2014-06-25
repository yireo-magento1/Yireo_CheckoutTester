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
        Mage::getModel('checkout/session')->setLastOrderId($order->getId())
            ->setLastRealOrderId($order->getIncrementId());

        // Load the layout
        $this->loadLayout();
        Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($orderId)));
        $this->renderLayout();
    }
}
