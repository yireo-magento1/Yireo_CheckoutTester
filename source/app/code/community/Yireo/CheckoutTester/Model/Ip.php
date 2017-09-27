<?php
/**
 * Yireo CheckoutTester
 *
 * @author Yireo
 * @package CheckoutTester
 * @copyright Copyright 2016
 * @license Open Source License (OSL v3)
 * @link https://www.yireo.com
 */

class Yireo_CheckoutTester_Model_Ip extends Mage_Core_Model_Config_Data
{
    public function getCommentText(Mage_Core_Model_Config_Element $element, $currentValue)
    {
        return Mage::helper('core')->__('CSV-list of IP-addresses allowed access. <br>Your IP is: <strong>' . $_SERVER['REMOTE_ADDR'] . '</strong>');
    }
}