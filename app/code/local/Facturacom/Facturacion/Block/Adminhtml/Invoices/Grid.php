<?php
/**
 * Grid for admin panel Facturacom Invoices
 */
class Facturacom_Facturacion_Block_Adminhtml_Invoices_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    function __construct(){
        parent::__construct();

        //Set some defaults for the grid
        $this->setDefaultSort('id');
        $this->setId('facturacom_facturacion_invoices_grid');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * Return the model we are using for the grid
     *
     * @return String
     */
    protected function _getCollectionClass(){
        return 'facturacom_facturacion/invoices_collection';
    }

    /**
     * Get and set the collection for the grid
     *
     * @return Type
     */
    protected function _prepareCollection(){
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Add the columns that should appear in the grid
     *
     * @return Type
     */
    protected function _prepareColumns(){
        $this->addColumn('id', array(
            'header' => $this->__('ID'),
            'align'  => 'right',
            'width'  => '50px',
            'index'  => 'id'
        ));

        $this->addColumn('order_id', array(
            'header' => $this->__('Order No'),
            'index'  => 'order_id'
        ));

        $this->addColumn('invoice_id', array(
            'header' => $this->__('Invoice No'),
            'index'  => 'invoice_id'
        ));

        $this->addColumn('status', array(
            'header' => $this->__('Status'),
            'index'  => 'status'
        ));

        $this->addColumn('actions', array(
            'header' => $this->__('Actions'),
            'index' => 'id',
            'width' => '170px',
            'filter' => false,
            'sortable'  => false,
            'renderer'  => 'Facturacom_Facturacion_Block_Adminhtml_Invoices_Cancel_Render',
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl(){
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * This is where our row data will link to
     *
     * @var $row
     * @return Redirect
     */
    public function getRowUrl($row)
    {
        // This is where our row data will link to
        return $this->getUrl('*/*/download', array('id' => $row->getInvoiceId()));
    }


}
