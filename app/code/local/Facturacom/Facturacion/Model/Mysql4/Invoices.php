<?php
/**
 * Resource Mysql4 for Facturacom Invoicing
 */
class Facturacom_Facturacion_Model_Mysql4_Invoices extends Mage_Core_Model_Mysql4_Abstract
{
    function _construct(){
        $this->_init('facturacom_facturacion/invoices', 'id');
    }
}
