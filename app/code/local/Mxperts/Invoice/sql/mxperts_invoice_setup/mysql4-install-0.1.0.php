<?php

$installer = $this;
$installer->startSetup();

$installer->setConfigData('payment/invoice/active', '1');
$installer->setConfigData('payment/invoice/title', 'Rechnung');

$installer->endSetup();
