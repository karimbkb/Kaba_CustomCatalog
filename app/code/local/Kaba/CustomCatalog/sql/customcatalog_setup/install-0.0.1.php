<?php

$installer = $this;
$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('customcatalog')};
CREATE TABLE {$this->getTable('customcatalog')} (
  `page_id` int(10) unsigned NOT NULL auto_increment,
  `page_name` varchar(255) NOT NULL default '',  
  `page_url` varchar(255) NOT NULL default '',  
  `banner_image_top` varchar(255) NOT NULL default '',  
  `headline` varchar(255) NOT NULL default '',  
  `text` varchar(255) NOT NULL default '',  
  `catalog_filter_serialzed` varchar(2550) NOT NULL default '',  
  `created_at` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='CustomCatalog page information';

");

$installer->endSetup();