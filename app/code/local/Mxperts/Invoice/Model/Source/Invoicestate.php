<?php
/**
 * @category    Mxperts
 * @package     Mxperts_Invoice
 * @authors     TMEDIA cross communications <info@tmedia.de>, Johannes Teitge <teitge@tmedia.de>, Daniel Sasse <d.sasse1984@googlemail.com>
 * @copyright   TMEDIA cross communications, Doris Teitge-Seifert
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Mxperts_Invoice_Model_Source_Invoicestate
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => Mage_Sales_Model_Order_Invoice::STATE_OPEN,
                'label' => Mage::helper('invoice')->__('Open')
            ),
            array(
                'value' => Mage_Sales_Model_Order_Invoice::STATE_PAID,
                'label' => Mage::helper('invoice')->__('Paid')
            )
        );
    }
}
