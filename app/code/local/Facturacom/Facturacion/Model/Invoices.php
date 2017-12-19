<?php
/**
 * Model for Facturacom Invoicing
 */
class Facturacom_Facturacion_Model_Invoices extends Mage_Core_Model_Abstract
{
    function _construct(){
        $this->_init('facturacom_facturacion/invoices');
    }
}
