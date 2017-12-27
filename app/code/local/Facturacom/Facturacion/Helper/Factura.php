<?php
/**
 * Factura Helper for Facturacom Invoicing
 *
 * Class Index
 * – getOrderFromLocal
 * – saveLocalOrder
 * – getCustomerByRFC
 * – getAccountDetails
 * – getPaymentMethods
 * – getPaymentMethodsType
 * - getCountries
 * - getSeries
 * - getRegimen
 * - getTipoRelacion
 * - getUsoCFDI
 * – createCustomer
 * - dd
 * - limit_text
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

        $customer = $this->apiCall($apimethod, $request);

        return $customer;
    }

    /**
	 * Create invoice in factura.com system
	 *
	 * @return Object
	 */
    public function getAccountDetails(){
        $apimethod = 'current/account';
        $request = 'GET';

        return $this->apiCall($apimethod, $request);
    }

    /**
     * Get Payment Method Types from Factura.com API.
     *
     * @return Object
     */
    public function getPaymentMethodsType(){
        $apimethod = 'catalogo/FormaPago';
        $request = 'GET';
        $data = $this->apiCall($apimethod, $request, null, null, 3)->data;
        $types = array();

        foreach ($data as $value) {
            $types[$value->key] = $value->name;
        }

        return $types;
    }

    /**
     * Get Payment Methods from Factura.com API.
     *
     * @return Object
     */
    public function getPaymentMethods(){
        $apimethod = 'catalogo/MetodoPago';
        $request = 'GET';
        $data = $this->apiCall($apimethod, $request, null, null, 3)->data;
        $methods = array();

        foreach ($data as $value) {
            $methods[$value->key] = $value->name;
        }

        return $methods;
    }

    /**
     * Get Countries
     *
     * @return Object
     */
    public function getCountries(){
        $apimethod = 'catalogo/Pais';
        $request = 'GET';
        $data = $this->apiCall($apimethod, $request, null, null, 3)->data;
        $countries = array();

        foreach ($data as $value) {
            $countries[$value->key] = $value->name;
        }

        return $countries;
    }

    /**
     * Get units from the catalog.
     *
     * @return array
     */
    public function getSeries(){
        $apimethod = 'series';
        $request = 'GET';

        $data = $this->apiCall($apimethod, $request)->data;
        $response = array();

        foreach ($data as $key => $value) {
            $response[$value->SerieID] = 'Serie: ' . $value->SerieName . ' - Tipo: ' . $value->SerieType;
        }

        return $response;
    }

    /**
     * Get Regimen Fiscal from the catalog.
     *
     * @return array
     */
    public function getMoneda(){
        $apimethod = 'catalogo/Moneda';
        $request = 'GET';

        $data = $this->apiCall($apimethod, $request, null, null, 3)->data;
        $response = array();

        foreach ($data as $key => $value) {
            $response[$value->key] = $value->name;
        }

        return $response;
    }

    /**
     * Get Regimen Fiscal from the catalog.
     *
     * @return array
     */
    public function getRegimen(){
        $apimethod = 'catalogo/RegimenFiscal';
        $request = 'GET';

        $data = $this->apiCall($apimethod, $request, null, null, 3)->data;
        $response = array();

        foreach ($data as $key => $value) {
            $response[$value->key] = $value->name;
        }

        return $response;
    }

    /**
     * Get Regimen Fiscal from the catalog.
     *
     * @return array
     */
    public function getTipoRelacion(){
        $apimethod = 'catalogo/Relacion';
        $request = 'GET';

        $data = $this->apiCall($apimethod, $request, null, null, 3)->data;
        $response = array();

        foreach ($data as $key => $value) {
            $response[$value->key] = $value->name;
        }

        return $response;
    }

    /**
     * Get Uso CFDI from the catalog.
     *
     * @return array
     */
    public function getUsoCFDI(){
        $apimethod = 'catalogo/UsoCfdi';
        $request = 'GET';

        $data = $this->apiCall($apimethod, $request, null, null, 3)->data;
        $response = array();

        foreach ($data as $key => $value) {
            $response[$value->key] = $value->name;
        }

        return $response;
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
            'pais'            => $data['f_pais'],
            'save'            => true,
        );

        if ($data['f_pais'] != 'MEX' && isset($data['f_numregidtrib'])) {
            $params['numregidtrib'] = $data['f_numregidtrib'];
        }

        return $this->apiCall($apimethod, $request, $params);
    }

    private function dd($args, $die = false){
        echo "<pre>";
        print_r($args);
        echo "<pre>";

        if($die) die;
    }

    /**
     * Limit a string to the limir given, in order to short the product description.
     *
     * @param string $text
     * @param int $limit
     * @return string
     */
    private function limit_text($text, $limit) {
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            $text = substr($text, 0, $pos[$limit]) . '...';
        }

        return str_replace(array("\r", "\n"), ' ', $text);
    }

    /**
	 * Create invoice in factura.com system
	 *
	 * @param String $payment_m
	 * @param String $payment_t
	 * @param String $t_payment_m
	 * @param String $t_payment_t
	 * @param String $num_cta_m
	 * @return Object
	 */
    public function createInvoice($payment_m, $payment_t, $t_payment_m, $t_payment_t, $num_cta_m){
        $apimethod = 'cfdi33/create';
        $request = 'POST';

        //get configuration pligun taxes
        $model_conf = Mage::getModel('facturacom_facturacion/conf');
        $collectionConfig = current($model_conf->getCollection()->getData());
        $model_conf->load($collectionConfig['id']);
        $exchangerateapikey = $model_conf->getExchangerateapikey();

        $order = json_decode($this->getCookie('order'));
        $products = json_decode($this->getCookie('line_items'));
        $customer = json_decode($this->getCookie('customer'));

        $concepts = array();
        $discount = 0;

        foreach($products as $product){
            $_product = Mage::getModel('catalog/product')->load($product->product_id);
            $discount += $product->discount;

            // Taxes
            $tax_base = $product->base_price * $product->qty;
            $tax_tasa = 0.16;
            $tax_importe = $tax_base * $tax_tasa;
            $impuestos = array();
            $impuestos["Traslados"][0] = array(
                "Base" => round($tax_base, 2),
                "Impuesto" => "002",
                "TipoFactor" => "Tasa",
                "TasaOCuota" => $tax_tasa,
                "Importe" => round($tax_importe, 2),
            );

            // si tiene activado IEPS, agregar a impuestos.
            if ($model_conf->getIepsconfig() && $_product->getData('usaIeps') && $product->shipping == false) {
                $ieps_rate = (null !== $model_conf->getIepscalc()) ? $model_conf->getIepscalc() : 0;
                $ieps_tasa = $ieps_rate / 100;
                $ieps_importe = $tax_base * $ieps_tasa;

                $iesp_tmp = array(
                    "Base" => round($tax_base, 2),
                    "Impuesto" => "003",
                    "TipoFactor" => "Tasa",
                    "TasaOCuota" => $ieps_tasa,
                    "Importe" => round($ieps_importe, 2),
                );

                array_push($impuestos["Traslados"], $iesp_tmp);
            }

            if($product->shipping){
                $product_data = array(
                    "ClaveProdServ" => '78102203',
                    "Cantidad" => $product->qty,
                    "ClaveUnidad" => 'E48',
                    "Unidad" => 'Unidad de Servicio',
                    "ValorUnitario" => round($product->base_price, 2),
                    "Descripcion" => $this->limit_text($product->name, 100),
                    "Descuento" => round($product->discount, 2),
                    "Impuestos" => $impuestos,
                );
            }else{
                $product_data = array(
                    "ClaveProdServ" => $_product->getData('claveProdServ'),
                    "NoIdentificacion" => $_product->getSKU(),
                    "Cantidad" => $product->qty,
                    "ClaveUnidad" => $_product->getData('claveUnidad'),
                    "Unidad" => $_product->getData('textoUnidad'),
                    "ValorUnitario" => round($product->base_price, 2),
                    "Descripcion" => $this->limit_text($_product->getShortDescription(), 100),
                    "Descuento" => round($product->discount, 2),
                    "Impuestos" => $impuestos,
                );
            }

            array_push($concepts, $product_data);
        }

        // Moneda y Tipo de Cambio
        $moneda_config = $model_conf->getMoneda();

        $params = array(
            "Receptor" => array(
                "UID" => $customer->Data->UID,
            ),
            "TipoDocumento" => "factura",
            "Conceptos" => $concepts,
            "UsoCFDI" => $model_conf->getUso_cfdi(),
            "Serie" => $model_conf->getSerie(),
            "Redondeo" => 2,
            "FormaPago" => $t_payment_m,
            "MetodoPago" => $payment_m,
            "CondicionesDePago" => "",
            "Relacionados" => array(),
            "Moneda" => $moneda_config,
            "NumOrder" => $order->order_number,
            "Comentarios" => "",
            "EnviarCorreo" => 'true'
        );

        if ($moneda_config != 'MXN') {
            $exchange_rate = $this->getExchangeRate($exchangerateapikey, $moneda_config);
            if($exchange_rate){
                $params["TipoCambio"] = $exchange_rate;
            }
        }

        $invoice = $this->apiCall($apimethod, $request, $params, null, 3);

        if(isset($invoice->status) && $invoice->status == 'success'){
            //save order into orders db table
            $order_data = array(
                'order_number'  => $order->order_number,
                'id'            => $order->id,
                'f_uid'         => $invoice->INV->UID,
                'source'        => 'magento',
            );
            $this->saveLocalOrder($order_data);
            // @TODO change status in magento
        }

        return $invoice;
    }

    /**
     * Get exchange rate from currency given to MXN.
     *
     * @param String $exchangerateapikey
     * @param String $from
     * @param String $to
     * @return float
     */
    private function getExchangeRate($exchangerateapikey, $from, $to = 'MXN'){
        $url = 'https://forex.1forge.com/1.0.2/convert?from='.$from.'&to='.$to.'&quantity=1&api_key='.$exchangerateapikey;
        $result = file_get_contents($url);
        $result = json_decode($result);

        if (isset($result->error)) {
            return false;
        }

        return $result->value;
    }

    /**
     * Execute curl call to Factura.com's API
     *
     * @param String $apimethod
     * @param String $request
     * @param Array $params
     * @param Boolean $debug
     * @param String $version
     * @return Object
     */
    private function apiCall($apimethod, $request, $params = null, $debug = null, $version = null){

        //Getting configuration data
        $conf = (object) current(Mage::getModel('facturacom_facturacion/conf')->getCollection()->getData());
        $apiurl = $conf->apiurl;

        if($version == 3){
            $apiurl .= 'v3/';
        }else{
            $apiurl .= 'v1/';
        }

        $ch = curl_init();
        $url = $apiurl . $apimethod;

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
                echo "<pre>";
                print_r($dataString);
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
            print('Exception occured: ' . $e->getMessage());die;
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
