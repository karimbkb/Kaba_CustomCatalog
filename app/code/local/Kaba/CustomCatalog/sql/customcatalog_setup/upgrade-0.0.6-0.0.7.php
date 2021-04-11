<?php

$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('customcatalog')} CHANGE websites stores varchar(255);
");

$installer->endSetup();