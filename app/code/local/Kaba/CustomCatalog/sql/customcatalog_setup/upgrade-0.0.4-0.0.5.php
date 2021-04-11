<?php

$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('customcatalog_products')} ADD COLUMN link_id INT(10) AUTO_INCREMENT PRIMARY KEY FIRST;
");

$installer->endSetup();