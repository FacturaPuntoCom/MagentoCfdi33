<?php
/**
 * Cookie Helper for Facturacom Invoicing
 */
class Facturacom_Facturacion_Helper_Cookie extends Mage_Core_Helper_Abstract
{

    /**
     * Setting cookie in Magento
     *
     * @param String $name
     * @param Type $value
     * @return Void
     */
    public function setCookie($name, $value){
        $period   = Mage::getModel('core/cookie')->getLifetime($name);
        $path     = Mage::getModel('core/cookie')->getPath($name);
        $domain   = Mage::getModel('core/cookie')->getDomain($name);
        $secure   = Mage::getModel('core/cookie')->isSecure($name);
        $httponly = Mage::getModel('core/cookie')->getHttponly($name);

        Mage::getModel('core/cookie')->set($name, $value, $period, $path,
                            $domain, $secure, $httponly);
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
     * Deleting a cookie by name in Magento
     *
     * @param String $name
     * @return Void
     */
    public function deleteCookie($name){
        Mage::getModel('core/cookie')->delete($name);
    }

    /**
     * Getting all cookies in Magento
     *
     * @return Array
     */
    public function getCookies(){
        return 9;
        // return Mage::getModel('core/cookie')->get();
    }
}
