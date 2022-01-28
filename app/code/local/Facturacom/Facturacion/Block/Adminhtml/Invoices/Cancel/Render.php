<?php

/**
 * Esta clase sobreescribe el método de renderización de la columna del grid.
 * Muestra u oculta el boton de cancelar según el estatus del invoice
 */
class Facturacom_Facturacion_Block_Adminhtml_Invoices_Cancel_Render extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {   
        $id = $row->getData($this->getColumn()->getIndex());

        $model = Mage::getModel('facturacom_facturacion/invoices')->load($id);
                        
        $status = $model->getStatus();

        if($status == '1') {
            $text = '<a href="'.Mage::helper('adminhtml')->getUrl('*/*/cancel', array("id" => $row->getId())).'">Cancelar</a>';
        } else {
            $text = 'Cancelada';
        }
        return $text; 
    }
}