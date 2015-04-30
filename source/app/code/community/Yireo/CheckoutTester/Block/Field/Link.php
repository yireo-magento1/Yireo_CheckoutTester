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
 * Class Yireo_CheckoutTester_Block_Field_Link
 */
class Yireo_CheckoutTester_Block_Field_Link extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Return the elements HTML value
     *
     * @param Varien_Data_Form_Element_Abstract $element
     *
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $link = $this->getFrontendLink();
        $html = '<a href="' . $link . '" target="_new">'
            . $this->__('Open success page in new window')
            . '</a>';

        return $html;
    }

    /**
     * Return the frontend link
     *
     * @return string
     */
    public function getFrontendLink()
    {
        $storeId = Mage::app()->getWebsite(true)->getDefaultGroup()->getDefaultStoreId();
        return Mage::app()->getStore($storeId)->getUrl('checkouttester/index/success');
    }
}
