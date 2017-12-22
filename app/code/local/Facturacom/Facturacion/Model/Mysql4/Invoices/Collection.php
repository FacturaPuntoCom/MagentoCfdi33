<?php
/**
 * Collection for Facturacom Invoicing
 */
class Facturacom_Facturacion_Model_Mysql4_Invoices_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    function _construct(){
        $this->_init('facturacom_facturacion/invoices');
    }
}
