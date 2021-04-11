<?php

$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('customcatalog')} ADD COLUMN product_collection_type VARCHAR(10);
");

$installer->endSetup();