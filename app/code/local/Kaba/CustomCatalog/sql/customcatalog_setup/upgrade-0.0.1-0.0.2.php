<?php

$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('customcatalog')} ADD COLUMN is_active int(1);
ALTER TABLE {$this->getTable('customcatalog')} ADD COLUMN websites varchar(255);
ALTER TABLE {$this->getTable('customcatalog')} ADD COLUMN from_date datetime default NULL;
ALTER TABLE {$this->getTable('customcatalog')} ADD COLUMN to_date datetime default NULL;
");

$installer->endSetup();