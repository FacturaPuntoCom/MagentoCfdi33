<?php
/**
 * Form to edit invoice for Facturacom Invoicing
 */
class Facturacom_Facturacion_Block_Adminhtml_Invoices_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
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

        // $form = new Varien_Data_Form(array(
        //     'id'        => 'edit_form',
        //     'action'    => $this->getUrl('*/*/save'),
        //     'method'    => 'post'
        // ));

        $form = new Varien_Data_Form(array(
            'id'     => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post'
        ));

        // $fieldset = $form->addFieldset('my_form', array('legend'=>'ABC'));
        // $fieldset = $form->addFieldset('my_fieldset', array('legend' => 'Your fieldset title'));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('checkout')->__('Configuración'),
            'class'  => 'fieldset-wide'
        ));

        if($model->getId()){
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }

        //form fields to edit configuration
        $fieldset->addField('sandbox', 'checkbox', array(
            'name'  => 'sandbox',
            'label' => Mage::helper('checkout')->__('Sandbox Mode'),
            'onclick'   => 'this.value = this.checked ? 1 : 0;',
            'checked' => $model->getSandbox(),
            'required' => false,
            'after_element_html' => 'Usar el ambiente de Desarrollo.<p class="nm"><small>Marque esta opción sólo si está probando el Módulo, ya que las facturas creadas no serán válidas.</small></p>',
        ));

        $fieldset->addField('apikey', 'text', array(
            'name'      => 'apikey',
            'label'     => Mage::helper('checkout')->__('Api Key'),
            'title'     => Mage::helper('checkout')->__('Api Key'),
            'required'  => true,
            'after_element_html' => '<p class="nm"><small>' . ' Lo obtienes en tu administrador de factura.com' . '</small></p>',
        ));

        $fieldset->addField('apisecret', 'text', array(
            'name'      => 'apisecret',
            'label'     => Mage::helper('checkout')->__('Secret Key'),
            'title'     => Mage::helper('checkout')->__('Secret Key'),
            'required'  => true,
            'after_element_html' => '<p class="nm"><small>' . ' Lo obtienes en tu administrador de factura.com' . '</small></p>',
        ));

        $series = Mage::registry('facturacom_facturacion_series');
        $fieldset->addField('serie', 'select', array(
            'name'      => 'serie',
            'label'     => Mage::helper('checkout')->__('Serie'),
            'title'     => Mage::helper('checkout')->__('Serie'),
            'required'  => false,
            'options' => $series,
            'after_element_html' => '<p class="nm"><small>' . 'Selecciona la serie con la que se va a crear la factura. (Importante guardar API KEY y SECRET KEY para obtener las Series).' . '</small></p>',
        ));

        $moneda = Mage::registry('facturacom_facturacion_moneda');
        $fieldset->addField('moneda', 'select', array(
            'name'      => 'moneda',
            'label'     => Mage::helper('checkout')->__('Moneda'),
            'title'     => Mage::helper('checkout')->__('Moneda'),
            'required'  => false,
            'options' => $moneda,
            'after_element_html' => '<p class="nm"><small>' . 'Selecciona la Moneda.' . '</small></p>',
        ));

        $usoCfdi = Mage::registry('facturacom_facturacion_usocfdi');
        $fieldset->addField('uso_cfdi', 'select', array(
            'name'      => 'uso_cfdi',
            'label'     => Mage::helper('checkout')->__('Uso de CFDI'),
            'title'     => Mage::helper('checkout')->__('Uso de CFDI'),
            'required'  => false,
            'options' => $usoCfdi,
            'after_element_html' => '<p class="nm"><small>' . 'Selecciona el Uso de CFDI para crear facturas.' . '</small></p>',
        ));

        $fieldset->addField('dayoff', 'select', array(
            'name'      => 'dayoff',
            'label'     => Mage::helper('checkout')->__('Días de tolerancia'),
            'title'     => Mage::helper('checkout')->__('Serie'),
            'required'  => true,
            'options'   => array(
                '0' => 'Selecciona una opción',
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '8',
                '9' => '9',
                '10' => '10',
                '11' => '11',
                '12' => '12',
                '13' => '13',
                '14' => '14',
                '15' => '15',
                '16' => '16',
                '17' => '17',
                '18' => '18',
                '19' => '19',
                '20' => '20',
                '21' => '21',
                '22' => '22',
                '23' => '23',
                '24' => '24',
                '25' => '25',
                '26' => '26',
                '27' => '27',
                '28' => '28',
                '29' => '29',
                '30' => '30',
            ),
            'after_element_html' => '<p class="nm"><small>' . ' Días después de pasado el mes de compra permitido facturar' . '</small></p>',
        ));

        $fieldset->addField('activatedate', 'date', array(
            'name'      => 'activatedate',
            'label'     => Mage::helper('checkout')->__('Fecha de activación'),
            'title'     => Mage::helper('checkout')->__('Serie'),
            'required'  => true,
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'format'    => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT) ,
            'value'     => date( Mage::app()->getLocale()->getDateStrFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                                        strtotime('today') ),
            'after_element_html' => '<p class="nm"><small>' . ' Fecha a partir de la cual está permitido facturar' . '</small></p>',
        ));

        $fieldset->addField('widgetheadtitle', 'text', array(
            'name'      => 'widgetheadtitle',
            'label'     => Mage::helper('checkout')->__('Título del widget'),
            'title'     => Mage::helper('checkout')->__('Título del widget'),
            'after_element_html' => '<p class="nm"><small>' . ' Título del widget que se mostrará en el área de clientes' . '</small></p>',
        ));

        $fieldset->addField('widgetdescription', 'textarea', array(
            'name'      => 'widgetdescription',
            'label'     => Mage::helper('checkout')->__('Descripción del widget'),
            'title'     => Mage::helper('checkout')->__('Descripción del widget'),
            'after_element_html' => '<p class="nm"><small>' . ' Descripción del widget que se mostrará en el área de clientes (acepta html)' . '</small></p>',
        ));

        $fieldset->addField('widgetheadbg', 'text', array(
            'name'      => 'widgetheadbg',
            'label'     => Mage::helper('checkout')->__('Color de fondo del header del widget'),
            'title'     => Mage::helper('checkout')->__('Color de fondo del header del widget'),
            'required'  => true,
            'style' => 'width: 280px !important',
            'after_element_html' => '<p class="nm"><small>' . ' Color de fondo del header del widget que se mostrará en el área de clientes (Ejemplo: #EFF0F1)' . '</small></p>',
        ));

        $fieldset->addField('widgetheadfcolor', 'text', array(
            'name'      => 'widgetheadfcolor',
            'label'     => Mage::helper('checkout')->__('Color de letra del header del widget'),
            'title'     => Mage::helper('checkout')->__('Color de letra del header del widget'),
            'required'  => true,
            'style' => 'width: 280px !important',
            'after_element_html' => '<p class="nm"><small>' . ' Color de letra del header del widget que se mostrará en el área de clientes (Ejemplo: #393318)' . '</small></p>',
        ));

        $iva_fieldset = $fieldset->addFieldset('taxes_config', array(
            'legend' => 'Configuración de Impuestos',
        ));

        $fieldset->addField('exchangerateapikey', 'text', array(
            'name'      => 'exchangerateapikey',
            'label'     => Mage::helper('checkout')->__('1forge Api Key'),
            'title'     => Mage::helper('checkout')->__('1forge Api Key'),
            'required'  => false,
            'after_element_html' => '<p class="nm"><small>' . ' Lo obtienes en tu panel de <a href="https://1forge.com" target="_blank">https://1forge.com</a>' . '</small></p>',
        ));

        $iva_fieldset->addField('iepsconfig', 'checkbox', array(
            'name' => 'iepsconfig',
            'label' => Mage::helper('checkout')->__('Activar IEPS'),
            'onclick'   => 'this.value = this.checked ? 1 : 0;',
            'checked' => $model->getIepsconfig(),
            'required' => false,
            'after_element_html' => 'Activar el calculo de IEPS.<p class="nm"><small>Marque esta opción si los productos (o algunos) tienen IEPS.</small></p>',
        ));

        $iva_fieldset->addField('iepscalc', 'text', array(
            'name'      => 'iepscalc',
            'label'     => Mage::helper('checkout')->__('Porcentaje de IEPS'),
            'title'     => Mage::helper('checkout')->__('Porcentaje de IEPS'),
            'required'  => false,
            'style' => 'width: 80px !important',
            'after_element_html' => '%<p class="nm"><small>' . 'Establecer el porcentaje de IEPS a calcular (SIN EL signo %). <em>Es necesario sólo si la opción Activar IEPS está habilitada.</em>' . '</small></p>',
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
