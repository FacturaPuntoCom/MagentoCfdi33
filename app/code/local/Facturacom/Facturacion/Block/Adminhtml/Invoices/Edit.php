<?php
/**
 * View/Edit Invoice for Facturacom Invoices
 */
class Facturacom_Facturacion_Block_Adminhtml_Invoices_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    function __construct(){
        $this->_blockGroup = 'facturacom_facturacion';
        $this->_controller = 'adminhtml_invoices';

        parent::__construct();

        $this->_updateButton('save', 'label', $this->__('Guardar configuración'));
        $this->_updateButton('delete', 'label', $this->__('Cancelar'));
    }

    /**
     * Get Header text
     *
     * @return string
     */
    public function getHeaderText(){
        return $this->__('Editar configuración');
    }
}
