<?php
/**
 * Yireo CheckoutTester for Magento 
 *
 * @package     Yireo_CheckoutTester
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright (C) 2014 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

class Yireo_CheckoutTester_Block_Field_Link extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $link = $this->getFrontendLink();
        $html = '<a href="'.$link.'" target="_new">'
            . $this->__('Open success page in new window')
            . '</a>';
        return $html;
    }

    public function getFrontendLink()
    {
        $storeId = Mage::app()->getWebsite(true)->getDefaultGroup()->getDefaultStoreId();
        return Mage::app()->getStore($storeId)->getUrl('checkouttester/index/success');
    }
}
