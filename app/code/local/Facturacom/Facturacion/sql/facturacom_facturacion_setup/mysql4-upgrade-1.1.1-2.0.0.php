<?php
/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();

// Alter tabler 'facturacom_facturacion_config'
$installer->run("
    ALTER TABLE {$this->getTable('facturacom_facturacion/conf')}
    ADD COLUMN `sandbox` INT NULL DEFAULT 1 AFTER `id`,
    ADD COLUMN `iepsconfig` INT NULL AFTER `activatedate`,
    ADD COLUMN `iepscalc` DOUBLE NULL AFTER `iepsconfig`,
    ADD COLUMN `moneda` VARCHAR(20) NULL AFTER `iepscalc`,
    ADD COLUMN `exchangerateapikey` VARCHAR(255) NULL AFTER `moneda`,
    ADD COLUMN `uso_cfdi` VARCHAR(20) NULL AFTER `exchangerateapikey`;
");

// Add Product attributes
// clave unidad
$installer->addAttribute('catalog_product', 'claveUnidad', array(
    'group' => 'General',
    'label' => 'Clave Unidad (SAT)',
    'input' => 'text',
    'type' => 'varchar',
    'required' => 0,
    'visible_on_front' => 0,
    'filterable' => 0,
    'searchable' => 0,
    'comparable' => 0,
    'user_defined' => 1,
    'is_configurable' => 0,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'note' => '',
));
// unidad (texto)
$installer->addAttribute('catalog_product', 'textoUnidad', array(
    'group' => 'General',
    'label' => 'Unidad de Servicio (SAT)',
    'input' => 'text',
    'type' => 'varchar',
    'required' => 0,
    'visible_on_front' => 0,
    'filterable' => 0,
    'searchable' => 0,
    'comparable' => 0,
    'user_defined' => 1,
    'is_configurable' => 0,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'note' => '',
));

// claveProdServ
$installer->addAttribute('catalog_product', 'claveProdServ', array(
    'group' => 'General',
    'label' => 'Clave Producto/Servicio (SAT)',
    'input' => 'text',
    'type' => 'varchar',
    'required' => 0,
    'visible_on_front' => 0,
    'filterable' => 0,
    'searchable' => 0,
    'comparable' => 0,
    'user_defined' => 1,
    'is_configurable' => 0,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'note' => '',
));

// usaIeps
$installer->addAttribute('catalog_product', 'usaIeps', array(
    'group'    => 'General',
    'label' => 'Aplicar IEPS a este producto.',
    'type' => 'int',
    'input' => 'boolean',
    'visible' => true,
    'required' => false,
    'position' => 21,
    'global' => 'Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL',
    'note' => "select yes no"
));

$installer->endSetup();
