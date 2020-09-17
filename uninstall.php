<?php
	$addon = rex_addon::get('skh3');
	rex_sql_table::get(rex::getTable('skh3_err_msg'))->drop();
	rex_sql_table::get(rex::getTable('skh3_koop'))->drop();
	rex_sql_table::get(rex::getTable('skh3_foerdern'))->drop();
	rex_sql_table::get(rex::getTable('skh3_leitung'))->drop();
	rex_sql_table::get(rex::getTable('skh3_refis'))->drop();
	rex_sql_table::get(rex::getTable('skh3_verantwortung'))->drop();
	rex_sql_table::get(rex::getTable('skh3_personen_lok'))->drop();
	rex_sql_table::get(rex::getTable('skh3_seminare_lok'))->drop();
	rex_sql_table::get(rex::getTable('skh3_seminartyp_lok'))->drop();
	rex_sql_table::get(rex::getTable('skh3_waehrung'))->drop();
	rex_sql_table::get(rex::getTable('skh3_personen'))->drop();
	rex_sql_table::get(rex::getTable('skh3_partner'))->drop();
	rex_sql_table::get(rex::getTable('skh3_geldgeber'))->drop();
	rex_sql_table::get(rex::getTable('skh3_seminare'))->drop();
?>
