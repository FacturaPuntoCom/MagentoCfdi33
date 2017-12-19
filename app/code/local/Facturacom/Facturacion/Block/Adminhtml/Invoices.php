<?php
/**
 * Block for Facturacom Admin
 */
class Facturacom_Facturacion_Block_Adminhtml_Invoices extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    function __construct(){
        // The blockGroup must match the first half of how we call the block, and controller matches the second half
        // ie. facturacom_facturacion/adminhtml_invoices
        $this->_blockGroup = 'facturacom_facturacion';
        $this->_controller = 'adminhtml_invoices';
        $this->_headerText = $this->__('Facturas generadas');
        $this->_addButtonLabel = Mage::helper('facturacom_facturacion')->__('Configuración');

        parent::__construct();
    }
}
