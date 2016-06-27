<?php
/**
 * @category    Mxperts
 * @package     Mxperts_Invoice
 * @authors     TMEDIA cross communications <info@tmedia.de>, Johannes Teitge <teitge@tmedia.de>, Daniel Sasse <d.sasse1984@googlemail.com>
 * @copyright   TMEDIA cross communications, Doris Teitge-Seifert
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Mxperts_Invoice_Block_Form extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('invoice/form.phtml');
    }
}
