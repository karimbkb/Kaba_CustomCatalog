<?php

$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('customcatalog')} CHANGE catalog_filter_serialzed catalog_filter_serialized text;
-- DROP TABLE IF EXISTS {$this->getTable('customcatalog_products')};
CREATE TABLE {$this->getTable('customcatalog_products')} (
  `page_id` int(255) unsigned NOT NULL,
  `product_id` int(255) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='CustomCatalog product collections';
");

$installer->endSetup();