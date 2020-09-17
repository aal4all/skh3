<?php
	$addon = rex_addon::get('skh3');
	rex_sql_table::get(rex::getTable('skh3_err_msg'))->drop();
	rex_sql_table::get(rex::getTable('skh3_foerdern'))->drop();
	rex_sql_table::get(rex::getTable('skh3_geldgeber'))->drop();
	rex_sql_table::get(rex::getTable('skh3_seminare'))->drop();
?>
