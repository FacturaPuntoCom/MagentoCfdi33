<?php
class Facturacom_Facturacion_Model_Mysql4_Conf_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    function _construct(){
        parent::_construct();
        $this->_init('facturacom_facturacion/conf');
    }
}
