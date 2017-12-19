<?php
/**
 * Controller for Facturacom Invoicing
 */
class Facturacom_Facturacion_Adminhtml_InvoicesController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Call the initAction method which will set some basic params for each action
     */
    public function indexAction(){
        $this->_initAction()
             ->renderLayout();
    }

    /**
     * New action - call form
     */
    public function newAction(){
        //We just forward the new action to a blank edit form
        $this->_redirect('*/*/config');
    }

    public function configAction(){
        $this->_initAction();

        $model = Mage::getModel('facturacom_facturacion/conf');

        //Load record
        $collectionConfig = current($model->getCollection()->getData());
        $model->load($collectionConfig['id']);

        $facturahelper = Mage::helper('facturacom_facturacion/factura');
        $series = $facturahelper->getSeries();

        $moneda = $facturahelper->getMoneda();
        $usoCfdi = $facturahelper->getUsoCFDI();
        Mage::register('facturacom_facturacion_series', $series);
        Mage::register('facturacom_facturacion_moneda', $moneda);
        Mage::register('facturacom_facturacion_usocfdi', $usoCfdi);

        // $this->dd($model->getIepsconfig());

        //Check if record is loaded
        if(!$model->getId()){
            Mage::getSingleton('adminhtml/session')->addError($this->__('This invoice no longer exists.'));
            $this->_redirect('*/*/');

            return;
        }

        $this->_title($model->getId() ? $model->getFile() : $this->__('New invoice'));

        $data = Mage::getSingleton('adminhtml/session')->getOrdersData(true);

        if(!empty($data)){
            $model->setData($data);
        }

        Mage::register('facturacom_facturacion', $model);

        $this->_initAction()
            ->_addBreadcrumb($id ? $this->__('Edit invoice') : $this->__('New invoice'), $id ? $this->__('Edit invoice') : $this->__('New invoice'))
            ->_addContent($this->getLayout()->createBlock('facturacom_facturacion/adminhtml_invoices_edit')->setData('action', $this->getUrl('*/*/save')))
            ->renderLayout();
    }

    /**
     * Edit invoice action
     */
    public function editAction(){
        $this->_initAction();

        //Get id if available
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('facturacom_facturacion/invoices');

        if($id){
            //Load record
            $model->load($id);

            //check if record is loaded
            if(!$model->getId()){
                Mage::getSingleton('adminhtml/session')->addError($this->__('This invoice no longer exists.'));
                $this->_redirect('*/*/');

                return;
            }
        }

        $this->_title($model->getId() ? $model->getOrderId() : $this->__('New Invoice'));

        $data = Mage::getSingleton('adminhtml/session')->getOrderData(true);

        if(!empty($data)){
            $model->setData($data);
        }

        Mage::register('facturacom_facturacion', $model);

        $this->_initAction()
             ->_addBreadcrumb($id ? $this->__('Edit Invoice') : $this->__('New Invoice'), $id ? $this->__('Edit Invoice') : $this->__('New Invoice'))
             ->_addContent($this->getLayout()->createBlock('facturacom_facturacion/adminhtml_invoices_edit')->setData('action', $this->getUrl('*/*/save')))
             ->renderLayout();
    }

    /**
     * Save action
     */
    public function saveAction(){
        if($postData = $this->getRequest()->getPost()){
            // Save data in database
            $model = Mage::getSingleton('facturacom_facturacion/conf');
            $sandbox = (isset($postData['sandbox'])) ? $postData['sandbox'] : 0;
            $apiurl = 'https://factura.com/api/';
            $iepsconfig = (isset($postData['iepsconfig'])) ? $postData['iepsconfig'] : 0;
            $iepscalc = (isset($postData['iepscalc'])) ? $postData['iepscalc'] : 0;

            settype($sandbox, "integer");

            if($sandbox == 1){
                $apiurl = 'http://devfactura.in/api/';
            }

            $configData = array(
                'form_key'          => $postData['form_key'],
                'id'                => $postData['id'],
                'apiurl'            => $apiurl,
                'apikey'            => $postData['apikey'],
                'apisecret'         => $postData['apisecret'],
                'serie'             => $postData['serie'],
                'dayoff'            => $postData['dayoff'],
                'activatedate'      => $postData['activatedate'],
                'version'           => '2.0.0',
                'systemurl'         => Mage::getBaseUrl(),
                'widgetheadtitle'   => $postData['widgetheadtitle'],
                'widgetdescription' => $postData['widgetdescription'],
                'widgetheadbg'      => $postData['widgetheadbg'],
                'widgetheadfcolor'  => $postData['widgetheadfcolor'],
                'exchangerateapikey' => $postData['exchangerateapikey'],
                'sandbox'           => $sandbox,
                'iepsconfig'        => $iepsconfig,
                'iepscalc'          => $iepscalc,
                'moneda'            => $postData['moneda'],
                'reg_fiscal'        => $postData['reg_fiscal'],
                'tipo_relacion'     => $postData['tipo_relacion'],
                'uso_cfdi'          => $postData['uso_cfdi'],
            );

            // $this->dd($configData);

            $model->setData($configData);
            $model->setData('sandbox', $sandbox);

            try{
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('La configuración se ha guardado con éxito.'));
                $this->_redirect('*/*/config');

                return;
            }catch(Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }catch(Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($this->__('Ha ocurrido un error al guardar el archivo con SO.'));
            }

            Mage::getSingleton('adminhtml/session')->setOrdersData($postData);
            $this->_redirect('*/*/');
            return;

        }
    }

    private function dd($arg) {
        echo "<pre>";
        var_dump($arg);
        echo "<pre>";
        die;
    }

    public function gridAction(){
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('facturacom_facturacion/adminhtml_invoices_grid')->toHtml()
        );
    }

    /**
     * Download PDF file.
     * @TODO add conf. apiurl -url diferente-
     */
    public function downloadAction(){
        $id = $this->getRequest()->getParam('id');
        $file = 'https://factura.com/api/publica/invoice/'.$id.'/pdf';

        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="'.$id.'.pdf"');
        readfile($file);
    }

    public function messageAction(){
        $data = Mage::getModel('facturacom_facturacion/invoices')->load($this->getRequest()->getParam('id'));
        echo $data->getContent();
    }

    /**
     * Initialize action
     *
     * Here, we set the breadcrumbs and the active menu
     *
     * @return Mage_Adminhtml_Controller_Action
     */
    protected function _initAction(){
        $this->loadLayout()
            // Make the active menu match the menu config nodes (whitout 'children' inbetwwen)
            ->_setActiveMenu('sales/facturacom_facturacion_invoices')
            ->_title($this->__('Sales'))->_title($this->__('Facturas'))
            ->_addBreadcrumb($this->__('Sales'), $this->__('Sales'))
            ->_addBreadcrumb($this->__('Invoices'), $this->__('Facturas'));

        return $this;
    }

    /**
     * Check currently called action by permissions for current user
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/facturacom_facturacion_invoices');
    }

}
