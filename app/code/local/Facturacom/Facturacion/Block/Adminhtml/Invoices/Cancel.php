<?php
/**
 * View/Edit Invoice for Facturacom Invoices
 */
class Facturacom_Facturacion_Block_Adminhtml_Invoices_Cancel extends Mage_Adminhtml_Block_Widget_Form_Container
{
    function __construct(){
        $this->_blockGroup = 'facturacom_facturacion';
        $this->_controller = 'adminhtml_invoices';
        $this->_mode = 'cancel';
        
        parent::__construct();

        $this->_removeButton('delete');

        $this->_updateButton('save', 'label', $this->__('Cancelar factura'));
    }

    /**
     * Get Header text
     *
     * @return string
     */
    public function getHeaderText(){
        return $this->__('Cancelar factura');
    }
}
