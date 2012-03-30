<?php
require_once 'includes/init.php';
$table_index=1;
mysql_query('CREATE TABLE IF NOT EXISTS `'.TABLE_NAME.$table_index.'` (
  `item_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(20) NOT NULL,
  `parent_id` bigint(20) NOT NULL,
  `parent_table_name`varchar(20) NULL,
  `name` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `url` varchar(100) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 MAX_ROWS=2000000 AUTO_INCREMENT=1 ;');
for($i=1;$i<=49000;$i++){
    mysql_query('INSERT INTO '.TABLE_NAME.$table_index.'
                 SET unique_id="'.$table_index.'_'.$i.'",
                     name="name'.$i.'",
                     title="title'.$i.'",
                     url="http.url'.$i.'.com"');
    if($i%MAX_ITEMS_PER_TABLE==0&&$i!=49000){
        $table_index++;
        mysql_query('CREATE TABLE IF NOT EXISTS `'.TABLE_NAME.$table_index.'` (
  `item_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(20) NOT NULL,
  `parent_id` bigint(20) NOT NULL,
  `parent_table_name`varchar(20),
  `name` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `url` varchar(100) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 MAX_ROWS=2000000 AUTO_INCREMENT=1 ;');
    }
}