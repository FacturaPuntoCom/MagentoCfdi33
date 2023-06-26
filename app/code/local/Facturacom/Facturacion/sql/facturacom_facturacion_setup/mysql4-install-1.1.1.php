<?php
/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;
$installer->startSetup();

// Create table 'facturacom_facturacion_invoices'
$installer->run("
    DROP TABLE IF EXISTS {$this->getTable('facturacom_facturacion/invoices')};
    CREATE TABLE {$this->getTable('facturacom_facturacion/invoices')} (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `order_id` varchar(255) NOT NULL,
        `order_ext` varchar(255) DEFAULT NULL,
        `invoice_id` varchar(255) DEFAULT NULL,
        `order_source` varchar(255) DEFAULT NULL,
        `status` int(11) NOT NULL,
        `last_update` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=latin1;
");

$installer->run("
    DROP TABLE IF EXISTS {$this->getTable('facturacom_facturacion/conf')};
    CREATE TABLE {$this->getTable('facturacom_facturacion/conf')} (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `apikey` varchar(255) DEFAULT NULL,
        `apisecret` varchar(255) DEFAULT NULL,
        `serie` varchar(255) DEFAULT NULL,
        `dayoff` int(11) DEFAULT NULL,
        `activatedate` datetime DEFAULT NULL,
        `apiurl` varchar(255) DEFAULT NULL,
        `version` varchar(255) DEFAULT NULL,
        `systemurl` varchar(255) DEFAULT NULL,
        `widgetheadtitle` varchar(255) DEFAULT NULL,
        `widgetdescription` text,
        `widgetheadbg` varchar(255) DEFAULT NULL,
        `widgetheadfcolor` varchar(255) DEFAULT NULL,
        `access_token` varchar(255) DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
");
$installer->run("
    INSERT INTO {$this->getTable('facturacom_facturacion/conf')} (`apiurl`,`access_token`)
        VALUES ('https://api.factura.com/v1/','c963d66bb5ff4b1eb3927744825e820a1f7fd6d6');
");
$installer->endSetup();
