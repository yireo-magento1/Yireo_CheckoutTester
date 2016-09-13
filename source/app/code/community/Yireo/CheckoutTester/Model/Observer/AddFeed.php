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

/**
 * CheckoutTester observer to various Magento events
 */
class Yireo_CheckoutTester_Model_Observer_AddFeed
{
    /**
     * Method fired on the event <controller_action_predispatch>
     *
     * @param Varien_Event_Observer $observer
     *
     * @return Yireo_CheckoutTester_Model_Observer_AddFeed
     * @event controller_action_predispatch
     */
    public function execute($observer)
    {
        // Run the feed
        Mage::getModel('checkouttester/feed')->updateIfAllowed();

        return $this;
    }
}
