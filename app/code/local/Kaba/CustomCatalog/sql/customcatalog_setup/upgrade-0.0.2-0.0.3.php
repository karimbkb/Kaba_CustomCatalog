<?php

$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('customcatalog')} ADD COLUMN meta_title varchar(255);
ALTER TABLE {$this->getTable('customcatalog')} ADD COLUMN meta_keywords varchar(255);
ALTER TABLE {$this->getTable('customcatalog')} ADD COLUMN meta_description varchar(255);
");

$installer->endSetup();