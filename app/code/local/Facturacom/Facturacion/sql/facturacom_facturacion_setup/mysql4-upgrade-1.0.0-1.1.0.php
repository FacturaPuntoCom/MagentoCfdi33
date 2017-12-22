<?php
/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;
$installer->startSetup();

//Alter tabler 'facturacom_facturacion_config'
$installer->run("
    ALTER TABLE {$this->getTable('facturacom_facturacion/conf')}
    ADD COLUMN `ivaconfig` INT NULL AFTER `activatedate`;
");
