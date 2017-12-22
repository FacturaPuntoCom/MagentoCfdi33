<?php
/**
 * Factura Helper for Facturacom Invoicing
 *
 * Class Index
 * – getOrderFromLocal
 * – saveLocalOrder
 * – getCustomerByRFC
 * – getAccountDetails
 * – createCustomer
 * – createInvoice
 * – setCookie
 * – getCookie
 * – getCookies
 * – deleteCookie
 */
class Facturacom_Facturacion_Helper_Factura extends Mage_Core_Helper_Abstract
{
    private $apiUrl       = 'http://devfactura.in/api/v1/';

    /**
     * Getting order by Order Num. from Database
     *
     * @param String $order_num
     * @return Object
     */
    public function getOrderFromLocal($order_num){
        $order = Mage::getModel('facturacom_facturacion/invoices')->load($order_num, 'order_id')->getData();
        return $order;
    }

    /**
     * Saving order in Database Facturacom's table
     *
     * @param Array $order_data
     * @return Int
     */
    public function saveLocalOrder($order_data){
        $data = array(
            'order_id'   => $order_data['order_number'],
            'order_ext'  => $order_data['id'],
            'invoice_id' => $order_data['f_uid'],
            'order_source' => $order_data['source'],
            'status'     => 1,
            'last_update' => time(),
        );

        $model = Mage::getModel('facturacom_facturacion/invoices')->addData($data);
        try{
            $insertId = $model->save()->getId();
        }catch(Exception $e){
            echo $e->getMessage();
        }

        return $insertId;
    }

    /**
     * Getting customer by RFC from Factura.com
     *
     * @param String $customerRfc
     * @return Object
     */
    public function getCustomerByRFC($customerRfc){
        $apimethod = 'clients/' . $customerRfc;
        $request = 'GET';

        return $this->apiCall($apimethod, $request);
    }

    /*
	 * Create invoice in factura.com system
	 *
	 * @return Object
	 *
	 */
    public function getAccountDetails(){
        $apimethod = 'current/account';
        $request = 'GET';

        return $this->apiCall($apimethod, $request);
    }

    /**
     * Crating customer in factura.com system
     *
     * @param Array $data
     * @return Object
     */
    public function createCustomer($data){

        if($data['method'] == 'create'){
            $apimethod = 'clients/create';
        }else{
            $apimethod = 'clients/' . $data['uid'] . '/update';
        }

        $request = 'POST';
        $params = array(
            'nombre'          => $data['g_nombre'],
            'apellidos'       => $data['g_apellidos'],
            'email'           => $data['g_email'],
            'telefono'        => $data['f_telefono'],
            'razons'          => $data['f_nombre'],
            'rfc'             => $data['f_rfc'],
            'calle'           => $data['f_calle'],
            'numero_exterior' => $data['f_exterior'],
            'numero_interior' => $data['f_interior'],
            'codpos'          => $data['f_cp'],
            'colonia'         => $data['f_colonia'],
            'estado'          => $data['f_estado'],
            'ciudad'          => $data['f_municipio'],
            'delegacion'      => $data['f_municipio'],
            'save'            => true,
        );

        return $this->apiCall($apimethod, $request, $params);
    }

    /*
	 * Create invoice in factura.com system
	 *
	 * @param String $payment_m
	 * @param String $payment_t
	 * @param String $num_cta_m
	 * @return Object
	 *
	 */
    public function createInvoice($payment_m, $payment_t, $num_cta_m){

        $apimethod = 'invoice/create';
        $request = 'POST';

        $order = json_decode($this->getCookie('order'));
        $products = json_decode($this->getCookie('line_items'));
        $customer = json_decode($this->getCookie('customer'));

        $items = array();
        $discount = 0;
        $calculate_tax = 1.16;

        foreach($products as $product):
            $unidad = "Producto"; //o producto o servicio (TG no devuelve esto)
            $ivaconfig = $product->ivaconfig;

            if($ivaconfig == 0){
                $calculate_tax = 1;
            }

            $product_price = ($product->price/$product->qty) / $calculate_tax;
            $discount += $product->discount;

            $ivaconcept = ($ivaconfig == 1) ? "yes" : "no";

            $product_data = array(
                'cantidad'  => $product->qty,
                'unidad'    => $unidad,
                'concept'   => $product->name,
                'precio'    => $product_price,
                'ivaconcept' => $ivaconcept,
                'subtotal'  => $product_price * $product->qty,
            );

            // echo "<pre>";
            // var_dump($product);
            // var_dump($product_data);
            // die;

            array_push($items, $product_data);
        endforeach;

        switch ($payment_m) {
            case '03':
                $payment_method = $payment_m;
                $numero_cuenta  = $num_cta_m;
                break;

            case '04':
                $payment_method = $payment_m;
                $numero_cuenta  = $num_cta_m;
                break;

            case '28':
                $payment_method = $payment_m;
                $numero_cuenta  = $num_cta_m;
                break;

            default:
                $payment_method = $payment_m;
                $numero_cuenta  = "No Identificado";
                break;
        }

        //Getting configuration
        $conf = (object) current(Mage::getModel('facturacom_facturacion/conf')->getCollection()->getData());
        $serie = $conf->serie;

        $params = array(
            'rfc' => $customer->Data->RFC,
            'items' => $items,
            'numerocuenta' => $numero_cuenta,
            'formapago' => 'Pago en una Sola Exhibicion',
            'metodopago' => $payment_method,
            'currencie' => 'MXN',
            'iva' => 1,
            'num_order' => $order->order_number,
            'seriefactura' => $serie,
            // 'save' => true,
            'descuento' => abs($order->total_discount), //$discount - ($discount * 0.16),//sacar descuentos totales
            'send_email' => true,
        );

        // echo "<pre>";
        // var_dump($params);
        // die;

        $invoice = $this->apiCall($apimethod, $request, $params);

        if($invoice->status == 'success'){
            //save order into orders db table
            $order_data = array(
                'order_number'  => $order->order_number,
                'id'            => $order->id,
                'f_uid'         => $invoice->invoice_uid,
                'source'        => 'magento',
            );
            $this->saveLocalOrder($order_data);
            // @TODO change status in magento
        }

        return $invoice;
    }

    /**
     * Execute curl call to Factura.com's API
     *
     * @param String $apimethod
     * @param String $request
     * @param Array $params
     * @return Object
     */
    private function apiCall($apimethod, $request, $params = null, $debug = null){

        //Getting configuration data
        $conf = (object) current(Mage::getModel('facturacom_facturacion/conf')->getCollection()->getData());

        $ch = curl_init();
        $url = $conf->apiurl . $apimethod;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);

        if(!isset($params)){
            $params = 'no data';
        }

        if($request == 'POST'){
            $dataString = json_encode($params);

            $httpheader = array(
                'Content-Type: application/json',
                'Content-Length:' . strlen($dataString),
                'F-PLUGIN:c963d66bb5ff4b1eb3927744825e820a1f7fd6d6',
                'F-API-KEY:' . $conf->apikey,
                'F-SECRET-KEY:' . $conf->apisecret
            );

            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
            if($debug == true){
                var_dump($dataString);
            }
        }else{
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'F-PLUGIN:c963d66bb5ff4b1eb3927744825e820a1f7fd6d6',
                'F-API-KEY:' . $conf->apikey,
                'F-SECRET-KEY:' . $conf->apisecret
            ));
        }

        //@TODO Curl Logs

        try{
            $data = curl_exec($ch);
            if(curl_error($ch)){
                return 'error:' . curl_error($ch);
            }
            curl_close($ch);
        }catch(Exception $e){
            print('Exception occured: ' . $e->getMessage());
        }

        return json_decode($data);
    }

    /**
     * Setting cookie in Magento
     *
     * @param String $name
     * @param Type $value
     * @return Void
     */
    public function setCookie($name, $value){
        $period   = Mage::getModel('core/cookie')->getLifetime($name);

        Mage::getModel('core/cookie')->set($name, $value, $period);
    }

    /**
     * Getting a cookie by name in Magento
     *
     * @param String $name
     * @return Array
     */
    public function getCookie($name){
        return Mage::getModel('core/cookie')->get($name);
    }

    /**
     * Getting all cookies in Magento
     *
     * @return Array
     */
    public function getCookies(){
        return Mage::getModel('core/cookie')->get();
    }

    /**
     * Deleting a cookie by name in Magento
     *
     * @param String $name
     * @return Void
     */
    public function deleteCookie($name){
        Mage::getModel('core/cookie')->delete($name);
    }

}
