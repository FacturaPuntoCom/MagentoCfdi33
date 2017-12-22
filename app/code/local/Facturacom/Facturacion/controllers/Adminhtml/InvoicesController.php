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
     * Customer invoice action
     */
    public function customerAction(){
        die('cliente');
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
     * Save action – TODO
     */
    public function saveAction(){
        if($postData = $this->getRequest()->getPost()){
            // Save data in database
            $model = Mage::getSingleton('facturacom_facturacion/conf');

            $ivaconfig = (isset($postData['ivaconfig'])) ? $postData['ivaconfig'] : 0;

            $configData = array(
                'form_key'          => $postData['form_key'],
                'id'                => $postData['id'],
                'apikey'            => $postData['apikey'],
                'apisecret'         => $postData['apisecret'],
                'serie'             => $postData['serie'],
                'dayoff'            => $postData['dayoff'],
                'activatedate'      => $postData['activatedate'],
                'version'           => '1.1.0',
                'systemurl'         => Mage::getBaseUrl(),
                'widgetheadtitle'   => $postData['widgetheadtitle'],
                'widgetdescription' => $postData['widgetdescription'],
                'widgetheadbg'      => $postData['widgetheadbg'],
                'widgetheadfcolor'  => $postData['widgetheadfcolor'],
                'ivaconfig'         => $ivaconfig,
            );

            $model->setData($configData);

            try{
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('La configuración se ha guardado con éxito.'));
                $this->_redirect('*/*/');

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

    public function gridAction(){
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('facturacom_facturacion/adminhtml_invoices_grid')->toHtml()
        );
    }

    public function downloadAction(){
        $id = $this->getRequest()->getParam('id');

        $file = 'http://devfactura.in/api/publica/invoice/'.$id.'/pdf';

        // $file="https://www.sedi.ca/sedi/SVTWeeklySummaryACL?name=W1ALLPDFI&locale=en_CA";
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
            ->_title($this->__('Sales'))->_title($this->__('Invoices'))
            ->_addBreadcrumb($this->__('Sales'), $this->__('Sales'))
            ->_addBreadcrumb($this->__('Invoices'), $this->__('Invoices'));

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
