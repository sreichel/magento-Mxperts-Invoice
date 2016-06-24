<?php
/**
 * @category Mxperts
 * @package Mxperts_Invoice
 * @author TMEDIA cross communications <info@tmedia.de>, Johannes Teitge <teitge@tmedia.de>
 * @copyright TMEDIA cross communications, Doris Teitge-Seifert
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mxperts_Invoice_Model_Invoice extends Mage_Payment_Model_Method_Abstract
{

    protected $_isGateway               = false;
    protected $_canAuthorize            = false;
    protected $_canCapture              = false;
    protected $_canCapturePartial       = false;
    protected $_canRefund               = false;
    protected $_canVoid                 = false;
    protected $_canUseInternal          = false;
    protected $_canUseCheckout          = true;
    protected $_canUseForMultishipping  = true;

    // Eindeutiger Bezeichner
    protected $_code = 'invoice';
    
    // Klassen fuer unsere Blocks/Templates
    protected $_formBlockType = 'invoice/form';
    protected $_infoBlockType = 'invoice/info';
           
	  // Kundengruppe des aktuellen Users auslesen
    public function getCurrentCustomerGroup()
	  {
		  $session = Mage::getSingleton('customer/session');
		  if (! $session->isLoggedIn()) $customer_group_id = Mage_Customer_Model_Group::NOT_LOGGED_IN_ID;
		  else $customer_group_id = $session->getCustomerGroupId();
		  return $customer_group_id;		
	  }             
           
           

    // Ausgabe des Titels aus dem Backend
    public function getCODTitle()
    {
        return $this->getConfigData('title');
    } 
    
    public function getPaymentMethodType()
    {
        return $this->_paymentMethod;
    }    
    
    // Ausgabe von Daten aus dem Backend
    public function getInfoText($fieldname)
    {
        return $this->getConfigData($fieldname);
    }

    public function canUseCheckout()
    {
        $canUse = true;
	      if($this->getConfigData('customergroups') != '') { 
          $customer_groups = explode(',',$this->getConfigData('customergroups'));
          $activecustomer_groups_id = $this->getCurrentCustomerGroup(); 
          if (!in_array($activecustomer_groups_id,$customer_groups)) {
            $canUse = false;
          }        
        }
        return $canUse; 	    
	  }                        
}
