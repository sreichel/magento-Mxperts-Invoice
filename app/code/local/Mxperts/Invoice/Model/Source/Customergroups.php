<?php
/**
 * @category Mxperts
 * @package Mxperts_Invoice
 * @author TMEDIA cross communications <info@tmedia.de>, Johannes Teitge <teitge@tmedia.de>
 * @copyright TMEDIA cross communications, Doris Teitge-Seifert
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Mxperts_Invoice_Model_Source_Customergroups
{
    public function toOptionArray()
    {        
      $customer_groups[] = array('value'=>Mage_Customer_Model_Group::NOT_LOGGED_IN_ID, 'label'=>'NOT LOGGED IN');    
      $groups = Mage::helper('customer')->getGroups()->toOptionArray();
      foreach($groups as $group){
        $customer_groups[] = array('value'=>$group['value'], 'label'=>$group['label']);        
      }
      return $customer_groups;             
    }
}