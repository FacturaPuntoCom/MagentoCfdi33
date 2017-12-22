<?php
/**
 * Model for Facturacom Invoicing
 */
class Facturacom_Facturacion_Model_Conf extends Mage_Core_Model_Abstract
{
    function _construct(){
        parent::_construct();
        $this->_init('facturacom_facturacion/conf');
    }
}
