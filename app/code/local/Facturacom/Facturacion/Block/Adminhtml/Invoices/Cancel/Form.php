<?php
/**
 * Esta clase imprime el formulario de cancelación con motivo para la reforma fiscal 2022
 */
class Facturacom_Facturacion_Block_Adminhtml_Invoices_Cancel_Form extends Mage_Adminhtml_Block_Widget_Form
{
    function __construct(){
        parent::__construct();
        $this->setId('facturacom_facturacion_invoices_form');
        $this->setTitle($this->__('Configuración de la integración'));
    }

    /**
     * Setup form fields for inserts/updates
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm(){

        $model = Mage::registry('facturacom_facturacion');

        $form = new Varien_Data_Form(array(
            'id'     => 'edit_form',
            'action' => $this->getUrl('*/*/aceptCancelation', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post'
        ));


        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('checkout')->__('Cancelar Factura'),
            'class'  => 'fieldset-wide'
        ));

        //form fields to edit configuration
        $fieldset->addField('motivo', 'select', array(
            'name'      => 'motivo',
            'label'     => Mage::helper('checkout')->__('Motivo de cancelación'),
            'title'     => Mage::helper('checkout')->__('Motivo'),
            'style' => 'width:98%; padding:3px',
            'required'  => true,
            'onchange' => 'ocultarFolio(this.value)',
            'options' => array(
                '01' => '01 - Comprobante emitido con errores con relación',
                '02' => '02 - Comprobante emitido con errores sin relación',
                '03' => '03 - No se llevó a cabo la operación',
                '04' => '04 - Operación nominiativa relacionada en una factura global'
            )
        ))->setAfterElementHtml("
        <p class='nm'><small>Selecciona el motivo por el cual deseas cancelar el CFDI</small></p>
        <script type=\"text/javascript\">
            function ocultarFolio(value){ 
                if(value == '01'){
                    document.getElementById('folioSustituto').parentNode.parentNode.style.display = 'table-row';
                    document.getElementById('folioSustituto').classList.add('required-entry');
                  } else {
                    document.getElementById('folioSustituto').parentNode.parentNode.style.display = 'none';
                    document.getElementById('folioSustituto').classList.remove('required-entry');
                  }
            }
        </script>"); 
        
        $fieldset->addField('folioSustituto', 'text', array(
            'name'      => 'folioSustituto',
            'required'  => true,
            'label'     => Mage::helper('checkout')->__('Folio fiscal o UID del CFDI que sustituirá esta factura'),
            'title'     => Mage::helper('checkout')->__('folioSustituto'),
            'after_element_html' => '<p class="nm"><small>Escribe el folio fiscal o el UID que servirá como reemplazo de esta factura. Si no cuentas con este valor, puedes elegir el motivo de cancelación numero 2</small></p>',
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
