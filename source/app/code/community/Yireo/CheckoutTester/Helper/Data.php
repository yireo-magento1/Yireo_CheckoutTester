<?php
/**
 * Yireo CheckoutTester for Magento
 *
 * @package     Yireo_CheckoutTester
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright (C) 2016 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * CheckoutTester helper
 */
class Yireo_CheckoutTester_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @var Mage_Sales_Model_Order
     */
    protected $order;

    /**
     * Yireo_CheckoutTester_Helper_Data constructor.
     */
    public function __construct()
    {
        $this->order = Mage::getModel('sales/order');
    }

    /**
     * Switch to determine whether this extension is enabled or not
     *
     * @return bool
     */
    public function enabled()
    {
        if ((bool)$this->getStoreConfig('advanced/modules_disable_output/Yireo_CheckoutTester')) {
            return false;
        }

        return true;
    }

    /**
     * Method to determine whether the current user has access to this page
     *
     * @return bool
     */
    public function hasAccess()
    {
        $ip = $this->getStoreConfig('checkouttester/settings/ip');
        $ip = trim($ip);

        $realIp = $this->getIpAddress();

        if (empty($ip) || empty($realIp)) {
            return true;
        }

        $ips = explode(',', $ip);

        foreach ($ips as $ip) {
            $ip = trim($ip);

            if (empty($ip)) {
                continue;
            }

            if ($ip == $realIp) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the current IP address
     *
     * @return mixed
     */
    public function getIpAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Return the order ID
     *
     * @return string
     */
    public function getOrderIdFromConfig()
    {
        return (int)$this->getStoreConfig('checkouttester/settings/order_id');
    }

    /**
     * Return the last order ID in this database
     *
     * @return int
     */
    public function getLastInsertedOrderId()
    {
        /** @var Mage_Sales_Model_Resource_Order_Collection $orders */
        $orders = $this->order->getCollection()
            ->setOrder('created_at', 'DESC')
            ->setPageSize(1)
            ->setCurPage(1);

        if (empty($orders)) {
            return 0;
        }

        /** @var Mage_Sales_Model_Order $firstOrder */
        $firstOrder = $orders->getFirstItem();
        if (empty($firstOrder)) {
            return 0;
        }

        return (int)$firstOrder->getEntityId();
    }

    /**
     * @param $path
     * @param null $default
     *
     * @return mixed
     */
    protected function getStoreConfig($path, $default = null)
    {
        return Mage::getStoreConfig($path, $default);
    }
}
