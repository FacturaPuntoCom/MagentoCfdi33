<?php
/**
 * Resource Mysql4 for Facturacom Invoicing
 */
class Facturacom_Facturacion_Model_Mysql4_Conf extends Mage_Core_Model_Mysql4_Abstract
{
    function _construct(){
        $this->_init('facturacom_facturacion/conf', 'id');
    }
}
