<?php
	$addon = rex_addon::get('skh3');
	
	
	
	// create skh3_seminare if not exists
	rex_sql_table::get(rex::getTable('skh3_seminare'))
		->ensureColumn(new rex_sql_column('seminar_id', 'mediumint(8) unsigned', $nullable = false, $default = null, $extra = 'AUTO_INCREMENT'))
		->ensureColumn(new rex_sql_column('seminar_start', 'date', $nullable = false))
		->ensureColumn(new rex_sql_column('seminar_ende', 'date', $nullable = false))
		->ensureColumn(new rex_sql_column('seminar_ort', 'varchar(100)', $nullable = false))
		->ensureColumn(new rex_sql_column('seminar_typ', 'tinyint(3) unsigned', $nullable = false, $default = 0))
		->ensureColumn(new rex_sql_column('seminar_online', 'enum("offline", "online")', $nullable = false, $default = 'offline'))
		->setPrimaryKey('seminar_id')
		->ensure();
		
	// create skh3_geldgeber if not exists
	rex_sql_table::get(rex::getTable('skh3_geldgeber'))
		->ensureColumn(new rex_sql_column('geldgeber_id', 'mediumint(8) unsigned', $nullable = false, $default = null, $extra = 'AUTO_INCREMENT'))
		->ensureColumn(new rex_sql_column('name', 'varchar(255)', $nullable = false))
		->ensureColumn(new rex_sql_column('website', 'varchar(255)', $nullable = false))
		->setPrimaryKey('geldgeber_id')
		->ensureIndex(new rex_sql_index('website', ['website'], $type = rex_sql_index::UNIQUE))
		->ensure();
	
	
	// create skh3_foerdern if not exists
	rex_sql_table::get(rex::getTable('skh3_foerdern'))
		->ensureColumn(new rex_sql_column('seminar_id', 'mediumint(8) unsigned', $nullable = false))
		->ensureColumn(new rex_sql_column('geldgeber_id', 'mediumint(8) unsigned', $nullable = false))
		->setPrimaryKey(['seminar_id', 'geldgeber_id'])
		->ensureForeignKey(new rex_sql_foreign_key('fk_foerd_geldgeber', rex::getTable('skh3_geldgeber'), ['geldgeber_id' => 'geldgeber_id'], $onUpdate = rex_sql_foreign_key::CASCADE))
		->ensureForeignKey(new rex_sql_foreign_key('fk_foerd_seminare', rex::getTable('skh3_seminare'), ['seminar_id' => 'seminar_id'], $onUpdate = rex_sql_foreign_key::CASCADE), $onDelete = rex_sql_foreign_key::CASCADE )
		->ensure();
		
  // create skh3_err_msg if not exists
	rex_sql_table::get(rex::getTable('skh3_err_msg'))
		->ensureColumn(new rex_sql_column('msg', 'varchar(80)', $nullable = false))
		->setPrimaryKey('msg')
		->ensure();
?>
