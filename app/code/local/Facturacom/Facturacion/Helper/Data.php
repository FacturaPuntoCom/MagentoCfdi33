<?php
/**
 * Helper class for Facturacom Invoicing
 */
class Facturacom_Facturacion_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Getting orders from Magento with status shipping
     *
     * @return Object
     */
    public function getOrders(){
        $order_collection = Mage::getModel('sales/order')
                                ->getCollection()
                                ->addAttributeToSelect('*');
        return $order_collection;
    }


}
