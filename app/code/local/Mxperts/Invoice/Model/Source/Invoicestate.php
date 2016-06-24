<?php
/**
 * @category Mxperts
 * @package Mxperts_Invoice
 * @author TMEDIA cross communications <info@tmedia.de>, Johannes Teitge <teitge@tmedia.de>
 * @copyright TMEDIA cross communications, Doris Teitge-Seifert
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Mxperts_Invoice_Model_Source_Invoicestate
{
    public function toOptionArray()
    {               
      $state[] = array('value'=>Mage_Sales_Model_Order_Invoice::STATE_OPEN, 'label'=>Mage::helper('invoice')->__('Open'));    
      $state[] = array('value'=>Mage_Sales_Model_Order_Invoice::STATE_PAID, 'label'=>Mage::helper('invoice')->__('Paid'));
      return $state;             
    }
}