<?php

$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('customcatalog')} ADD COLUMN serialized_filters TEXT;
");

$installer->endSetup();