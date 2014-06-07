<?php
/**
 * Yireo Common
 *
 * @author Yireo
 * @package Yireo_Common
 * @copyright Copyright 2014
 * @license Open Source License (OSL v3) (OSL)
 * @link http://www.yireo.com
 */

/*
 * Feed Model
 */
class Yireo_CheckoutTester_Model_Feed extends Mage_AdminNotification_Model_Feed
{
    /**
     * Return the feed URL
     */
    protected $customFeedUrl = 'www.yireo.com/extfeed?format=feed&platform=magento&extension=checkouttester';

    /**
     * Return the feed URL
     *
     * @return string
     */
    public function getFeedUrl() 
    {
        return Mage::getStoreConfigFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://'.$this->customFeedUrl;
    }

    /**
     * Try to update feed
     *
     * @return mixed
     */
    public function updateIfAllowed()
    {
        // Is this the backend
        if (Mage::app()->getStore()->isAdmin() == false) {
            return false;
        }

        // Is the backend-user logged-in
        if (Mage::getSingleton('admin/session')->isLoggedIn() == false) {
            return false;
        }

        // Is the feed disabled?
        if((bool)Mage::getStoreConfig('yireo/common/disabled')) {
            return false;
        }

        // Update the feed
        $this->checkUpdate();
    }

    /**
     * Override the original method
     *
     * @return SimpleXMLElement
     */
    public function getFeedData()
    {
        // Get the original data
        $feedXml = parent::getFeedData();

        if ($feedXml && $feedXml->channel && $feedXml->channel->item) {
            foreach ($feedXml->channel->item as $item) {

                // Add the severity to each item
                $feedXml->channel->item->severity = Mage_AdminNotification_Model_Inbox::SEVERITY_NOTICE;
            }
        }

        return $feedXml;
    }
}
