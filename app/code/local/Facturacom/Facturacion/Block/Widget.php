<?php
/**
 * Block for Facturacom Frontend
 */
class Facturacom_Facturacion_Block_Widget extends Mage_Core_Block_Template
{

    public function num(){
        $helper = Mage::helper('facturacion');
        return '2*2 = '.$helper->bytwo(2);
    }

    public function getModuleTitle(){
        $moduleConfiguration = current($this->getModuleConfiguration());
        return $moduleConfiguration['widgetheadtitle'];
    }

    public function getModuleDescription(){
        $moduleConfiguration = current($this->getModuleConfiguration());
        return $moduleConfiguration['widgetdescription'];
    }

    public function getModuleHeadColor(){
        $moduleConfiguration = current($this->getModuleConfiguration());
        return $moduleConfiguration['widgetheadbg'];
    }

    public function getModuleHeadFontColor(){
        $moduleConfiguration = current($this->getModuleConfiguration());
        return $moduleConfiguration['widgetheadfcolor'];
    }

    public function getModuleConfiguration(){
        $collection = Mage::getModel('facturacom_facturacion/conf')->getCollection();
        return $collection->getData();
    }

    public function getHistoricInvoices(){
        $collection = Mage::getModel('facturacom_facturacion/invoices')->getCollection();//->getCollection();
        return $collection->getData();
    }
}
