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
 * CheckoutTester helper
 */
class Yireo_CheckoutTester_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Switch to determine whether this extension is enabled or not
     *
     * @return bool
     */
    public function enabled()
    {
        if ((bool)Mage::getStoreConfig('advanced/modules_disable_output/Yireo_CheckoutTester')) {
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
        $ip = Mage::getStoreConfig('checkouttester/settings/ip');
        $ip = trim($ip);

        $realIp = $this->getIpAddress();

        if (!empty($ip) && $realIp) {
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

        return true;
    }

    /**
     * Get the current IP address
     *
     * @return mixed
     */
    public function getIpAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];

        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * Return the order ID
     *
     * @return string
     */
    public function getOrderIdFromConfig()
    {
        return (int) Mage::getStoreConfig('checkouttester/settings/order_id');
    }

    /**
     * Return the last order ID in this database
     *
     * @return int
     */
    public function getLastInsertedOrderId()
    {
        $orders = Mage::getModel('sales/order')->getCollection()
            ->setOrder('created_at', 'DESC')
            ->setPageSize(1)
            ->setCurPage(1);

        if (empty($orders)) {
            return 0;
        }

        $firstOrder = $orders->getFirstItem();
        if (empty($firstOrder)) {
            return 0;
        }

        return (int) $firstOrder->getEntityId();
    }
}
