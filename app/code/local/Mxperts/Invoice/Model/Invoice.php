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
    protected $_canCapture              = true;
    protected $_canCapturePartial       = false;
    protected $_canRefund               = false;
    protected $_canVoid                 = false;
    protected $_canUseInternal          = true;
    protected $_canUseCheckout          = true;
    protected $_canUseForMultishipping  = false;

    // Eindeutiger Bezeichner
    protected $_code = 'invoice';
    
    // Klassen fuer unsere Blocks/Templates
    protected $_formBlockType = 'invoice/form';
    protected $_infoBlockType = 'invoice/info';
    
    protected $_orders = null;    
    protected $_amount = 0;    
           
	  // Kundengruppe des aktuellen Users auslesen
    public function getCurrentCustomerGroup()
	  {  
		  $session = Mage::getSingleton('customer/session');
		  if (! $session->isLoggedIn()) $customer_group_id = Mage_Customer_Model_Group::NOT_LOGGED_IN_ID;
		  else $customer_group_id = $session->getCustomerGroupId();
		  return $customer_group_id;		
	  }             
           
       
     public function authorize(Varien_Object $payment, $amount)
    {
        // If invoice enabled in backend 
        if ((int)$this->getConfigData('create_invoice') == '1') 
        {
      	    $order = $payment->getOrder();
        	  $real_order_id	= $payment->getOrder()->getRealOrderId();
        	  $order->loadByIncrementId($real_order_id);			   
           
		       if ($order->canInvoice())
           {			
              	$invoice = $order->prepareInvoice();
            		$invoice->register()->capture();
            		Mage::getModel('core/resource_transaction')->addObject($invoice)->addObject($invoice->getOrder())->save();
              	$order->addRelatedObject($invoice);
              	
                //if option send Email is activated in backend
                if ((int)$this->getConfigData('send_invoice_email') == 1) 
                {
                  $order->sendNewOrderEmail(); 
                }                                                             
              	
           }

        }
        return $this;
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

    public function initOrders() {
        $this->_orders = Mage::getResourceModel('sales/order_collection')
            ->addAttributeToSelect('*')
            ->joinAttribute('shipping_firstname', 'order_address/firstname', 'shipping_address_id', null, 'left')
            ->joinAttribute('shipping_lastname', 'order_address/lastname', 'shipping_address_id', null, 'left')            
            ->addAttributeToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
            ->addAttributeToFilter('status', Mage_Sales_Model_Order::STATE_COMPLETE)            
            ->addAttributeToFilter('state', array('in' => Mage::getSingleton('sales/order_config')->getVisibleOnFrontStates()))            
            ->addAttributeToSort('created_at', 'desc')
   //         ->setPageSize('5')
            ->load();    
    }
    
    public function initAmount() {
      $sess = Mage::getSingleton('checkout/session');
      $items = $sess->getQuote()->getItemsCollection()->getItems();    
      $value = 0;        
      foreach($items as $item) {
        $value += $item->getCalculationPrice() * $item->getQty();       
        $value += $item->getTaxAmount();          
      }
      $this->_amount = $value; 
    }
    
    
    public function ordersCount() {
      return count($this->_orders);
    }
    
    public function ordersValue() {
      $total = 0;    
      
      if (count($this->_orders) > 0) {      
        foreach($this->_orders as $order) {
          $total += $order->getbase_subtotal();
        }
      }
      return $total;
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
        
        if ($canUse):       
          $this->initOrders();                  
          $canUse = ( $this->ordersCount() >= (int)$this->getConfigData('orderscount') );          
        endif;               
        
        if ($canUse):
          $canUse = ( $this->ordersValue() >= (float)$this->getConfigData('ordersamount') );
        endif;
        
        if ($canUse):
          $this->initAmount(); 
          if ((int)$this->getConfigData('minamount') > 0) {      
          $canUse = ( $this->_amount >= (float)$this->getConfigData('minamount') );
          }
        endif;
        
        if ($canUse): 
          if ((int)$this->getConfigData('maxamount') > 0) {      
          $canUse = ( $this->_amount < (float)$this->getConfigData('maxamount') );
          }
        endif;
          
        
        return $canUse; 	    
	  }                        
}
