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
    /*
     * Switch to determine whether this extension is enabled or not
     * 
     * @access public
     * @param null
     * @return string
     */
    public function enabled()
    {
        return true;
    }

    /*
     * Method to determine whether the current user has access to this page
     * 
     * @access public
     * @param null
     * @return string
     */
    public function hasAccess()
    {
        $ip = Mage::getStoreConfig('checkouttester/settings/ip');
        $ip = trim($ip);
        if(!empty($ip)) {
            $ips = explode(',', $ip);
            foreach($ips as $ip) {
                $ip = trim($ip);
                if(empty($ip)) continue;
                if($ip == $_SERVER['REMOTE_ADDR']) {
                    return true;
                }
            }
            return false;
        }

        return true;
    }

    /*
     * Return the order ID
     * 
     * @access public
     * @param null
     * @return string
     */
    public function getOrderId()
    {
        return (int)Mage::getStoreConfig('checkouttester/settings/order_id');
    }
}
