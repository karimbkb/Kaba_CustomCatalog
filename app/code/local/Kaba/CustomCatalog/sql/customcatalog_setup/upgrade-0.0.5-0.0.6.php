<?php

$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('customcatalog')} DROP COLUMN catalog_filter_serialized;
");

$installer->endSetup();