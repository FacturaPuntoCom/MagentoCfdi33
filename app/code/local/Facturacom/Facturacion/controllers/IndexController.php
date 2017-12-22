<?php
/**
 * Index Controller for Frontend
 */
class Facturacom_Facturacion_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Getting index form Facturacion
     *
     * @param Int $orderID
     * @return Object
     */
     public function indexAction(){
         $facturahelper = Mage::helper('facturacom_facturacion/factura');
         $facturahelper->deleteCookie('order');
         $facturahelper->deleteCookie('customer');
         $facturahelper->deleteCookie('line_items');

         $this->loadLayout();
         $this->getLayout()->getBlock('head')->setTitle($this->__('Servicio de Facturación en Línea'));
         $this->renderLayout();
     }

     /**
      * Response for AJAX call - step one
      *
      * @return Object
      */
     public function oneAction(){
         $isAjax = Mage::app()->getRequest()->isAjax();
         if($isAjax){
             $response = array();
             $customerRfc = trim(Mage::app()->getRequest()->getPost('rfc'));
             $orderNum    = trim(Mage::app()->getRequest()->getPost('order'));
             $email       = trim(Mage::app()->getRequest()->getPost('email'));

             // helpers
             $facturahelper = Mage::helper('facturacom_facturacion/factura');
             $orderhelper   = Mage::helper('facturacom_facturacion/order');

             //search order in Magento
             $order = $orderhelper->getOrderByNum($orderNum);

             //check if order exists
             if(!isset($order->id)){
                 $response = array(
                     'error' => 400,
                     'message' => 'No existe un pedido con ese número. Por favor inténtelo con otro número.'
                 );
             }else{
             //check for the order status "complete"
                 if(in_array($order->status, array('complete','processing'), true )){

                     if($order->customer_email == $email){

                         $orderLocal = $facturahelper->getOrderFromLocal($orderNum);

                         if(isset($orderLocal['id'])){
                             $response = array(
                                 'error' => 300,
                                 'message' => 'Este pedido ya se encuentra facturado.',
                                 'data' => array(
                                     'order_local' => $orderLocal
                                 )
                             );
                         }else{

                             //Get customer by RFC
                             $customer  = $facturahelper->getCustomerByRFC($customerRfc);
                             //Get order lines
                             $orderEntity = $orderhelper->getOrderEntity($orderNum);
                             $lineItems = $orderhelper->getOrderLines($orderEntity);

                            //   echo "<pre>";
                            //   var_dump($order);
                            //   var_dump($lineItems);
                            //   die;

                             //Guardar información premilinarmente en cookies
                             $facturahelper->setCookie('order', json_encode($order));
                             $facturahelper->setCookie('customer', json_encode($customer));
                             $facturahelper->setCookie('line_items', json_encode($lineItems));

                             $response  = array(
                                 'error' => 200,
                                 'message' => 'Pedido encontrado exitósamente',
                                 'data' => array(
                                     'order' => $order,
                                     'customer' => $customer,
                                     'line_items' => $lineItems
                                 )
                             );
                         }
                     }else{
                         $response = array(
                             'error' => 400,
                             'message' => 'El email ingresado no coincide con el correo registrado en el pedido. Por favor inténtelo con otro correo.',
                             'order' => $order,
                         );
                     }
                 }else{
                     $response = array(
                         'error' => 400,
                         'message' => 'El pedido aún no se encuentra listo para facturar. Por favor espere a que su pedido sea enviado.',
                         'order' => $order,
                     );
                 }
             }

             $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
         }else{
             die('AJAX request only');
         }
     }

     /**
     * Response for AJAX call - step two
     *
     * @return Object
     */
    public function twoAction(){
        $isAjax = Mage::app()->getRequest()->isAjax();
        if($isAjax){
            $response      = array();
            $facturahelper = Mage::helper('facturacom_facturacion/factura');

            $customerData = array(
                'uid'         => Mage::app()->getRequest()->getPost('uid'),
                'method'      => Mage::app()->getRequest()->getPost('api_method'),
                'g_nombre'    => Mage::app()->getRequest()->getPost('g_nombre'),
                'g_apellidos' => Mage::app()->getRequest()->getPost('g_apellidos'),
                'g_email'     => Mage::app()->getRequest()->getPost('g_email'),
                'f_telefono'  => Mage::app()->getRequest()->getPost('f_telefono'),
                'f_nombre'    => Mage::app()->getRequest()->getPost('f_nombre'),
                'f_rfc'       => Mage::app()->getRequest()->getPost('f_rfc'),
                'f_calle'     => Mage::app()->getRequest()->getPost('f_calle'),
                'f_exterior'  => Mage::app()->getRequest()->getPost('f_exterior'),
                'f_interior'  => Mage::app()->getRequest()->getPost('f_interior'),
                'f_colonia'   => Mage::app()->getRequest()->getPost('f_colonia'),
                'f_municipio' => Mage::app()->getRequest()->getPost('f_municipio'),
                'f_estado'    => Mage::app()->getRequest()->getPost('f_estado'),
                'f_pais'      => Mage::app()->getRequest()->getPost('f_pais'),
                'f_cp'        => Mage::app()->getRequest()->getPost('f_cp'),
            );

            if( $customerData["g_nombre"] == null || $customerData["g_apellidos"] == null ||
                $customerData["g_email"] == null || $customerData["f_calle"] == null ||
                $customerData["f_colonia"] == null || $customerData["f_cp"] == null ||
                $customerData["f_estado"] == null || $customerData["f_exterior"] == null ||
                $customerData["f_municipio"] == null || $customerData["f_nombre"] == null ||
                $customerData["f_rfc"] == null || $customerData["f_telefono"] == null ){

                    $response = array(
                        'error' => 400,
                        'message' => 'No se han recibido todos los campos. Por favor revise la información del cliente.',
                        'data' => $customerData
                    );
            }

            $customerNewData = $facturahelper->createCustomer($customerData);

            //Get information saved previously
            $order     = $facturahelper->getCookie('order');
            $lineItems = $facturahelper->getCookie('line_items');
            $facturahelper->setCookie('customer', json_encode($customerNewData)); //Updating customer info

            $response  = array(
                'error' => 200,
                'message' => 'Cliente creado/actualizado exitósamente',
                'data' => array(
                    'order' => json_decode($order),
                    'customer' => $customerNewData,
                    'line_items' => json_decode($lineItems)
                )
            );

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));

        }else{
            die('AJAX request only');
        }
    }

    /**
     * Response for AJAX call - step three
     *
     * @return Object
     */
    public function threeAction(){
        $isAjax = Mage::app()->getRequest()->isAjax();
        if($isAjax){
            $response   = array();

            $payment_data = array(
                'payment_m' => Mage::app()->getRequest()->getPost('payment_m'),
                'payment_t' => Mage::app()->getRequest()->getPost('payment_t'),
                'num_cta_m' => Mage::app()->getRequest()->getPost('num_cta_m')
            );

            //helpers
            $facturahelper = Mage::helper('facturacom_facturacion/factura');

            $invoice = $facturahelper->createInvoice($payment_data['payment_m'], $payment_data['payment_t'], $payment_data['num_cta_m'] );

            $response  = array(
                'error' => 200,
                'message' => 'Factura creada exitósamente',
                'data' => array(
                    'invoice' => $invoice
                )
            );

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        }else{
            die('AJAX request only');
        }
    }

}
