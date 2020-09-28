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
		
	// create skh3_seminartyp_lok if not exists
	rex_sql_table::get(rex::getTable('skh3_seminartyp_lok'))
		->ensureColumn(new rex_sql_column('typ_id', 'tinyint(3) unsigned', $nullable = false, $default = null))
		->ensureColumn(new rex_sql_column('lang_id', 'int(11)', $nullable = false))
		->ensureColumn(new rex_sql_column('bezeichnung', 'varchar(255)', $nullable = false))
		->setPrimaryKey(['typ_id', 'lang_id'])
		->ensure();
  	
		
	// create skh3_geldgeber if not exists
	rex_sql_table::get(rex::getTable('skh3_geldgeber'))
		->ensureColumn(new rex_sql_column('geldgeber_id', 'mediumint(8) unsigned', $nullable = false, $default = null, $extra = 'AUTO_INCREMENT'))
		->ensureColumn(new rex_sql_column('name', 'varchar(255)', $nullable = false))
		->ensureColumn(new rex_sql_column('website', 'varchar(255)', $nullable = false))
		->setPrimaryKey('geldgeber_id')
		->ensureIndex(new rex_sql_index('name', ['name'], $type = rex_sql_index::UNIQUE))
		->ensureIndex(new rex_sql_index('website', ['website'], $type = rex_sql_index::UNIQUE))
		->ensure();
	
	
	// create skh3_partner if not exists
	rex_sql_table::get(rex::getTable('skh3_partner'))
		->ensureColumn(new rex_sql_column('partner_id', 'mediumint(8) unsigned', $nullable = false, $default = null, $extra = 'AUTO_INCREMENT'))
		->ensureColumn(new rex_sql_column('name', 'varchar(255)', $nullable = false))
		->ensureColumn(new rex_sql_column('website', 'varchar(255)', $nullable = false))
		->setPrimaryKey('partner_id')
		->ensureIndex(new rex_sql_index('name', ['name'], $type = rex_sql_index::UNIQUE))
		->ensureIndex(new rex_sql_index('website', ['website'], $type = rex_sql_index::UNIQUE))
		->ensure();
	
	
	// create skh3_personen if not exists
	rex_sql_table::get(rex::getTable('skh3_personen'))
		->ensureColumn(new rex_sql_column('person_id', 'mediumint(8) unsigned', $nullable = false, $default = null, $extra = 'AUTO_INCREMENT'))
		->ensureColumn(new rex_sql_column('vorname', 'varchar(255)', $nullable = false))
		->ensureColumn(new rex_sql_column('name', 'varchar(255)', $nullable = false))
		->setPrimaryKey('person_id')
		->ensureIndex(new rex_sql_index('name', ['vorname', 'name'], $type = rex_sql_index::UNIQUE))
		->ensure();
	
	// create skh3_personen_lok if not exists
	/*
	rex_sql_table::get(rex::getTable('skh3_personen_lok'))
		->ensureColumn(new rex_sql_column('person_id', 'mediumint(8) unsigned', $nullable = false))
		->ensureColumn(new rex_sql_column('lang_id', 'int(11)', $nullable = false))
		->ensureColumn(new rex_sql_column('beschreibung', 'text', $nullable = false))
		->setPrimaryKey(['person_id', 'lang_id'])
		->ensureForeignKey(new rex_sql_foreign_key('fk_personenlok_personen', rex::getTable('skh3_personen'), ['person_id' => 'person_id'], $onUpdate = rex_sql_foreign_key::CASCADE))
		->ensure();
	*/
	
	// create skh3_leitung if not exists
	rex_sql_table::get(rex::getTable('skh3_leitung'))
		->ensureColumn(new rex_sql_column('seminar_id', 'mediumint(8) unsigned', $nullable = false))
		->ensureColumn(new rex_sql_column('person_id', 'mediumint(8) unsigned', $nullable = false))
		->setPrimaryKey(['seminar_id', 'person_id'])
		->ensureForeignKey(new rex_sql_foreign_key('fk_leitung_personen', rex::getTable('skh3_personen'), ['person_id' => 'person_id'], $onUpdate = rex_sql_foreign_key::CASCADE))
		->ensureForeignKey(new rex_sql_foreign_key('fk_leitung_seminare', rex::getTable('skh3_seminare'), ['seminar_id' => 'seminar_id'], $onUpdate = rex_sql_foreign_key::CASCADE, $onDelete = rex_sql_foreign_key::CASCADE))
		->ensure();
		
  // create skh3_refis if not exists
	rex_sql_table::get(rex::getTable('skh3_refis'))
		->ensureColumn(new rex_sql_column('seminar_id', 'mediumint(8) unsigned', $nullable = false))
		->ensureColumn(new rex_sql_column('person_id', 'mediumint(8) unsigned', $nullable = false))
		->setPrimaryKey(['seminar_id', 'person_id'])
		->ensureForeignKey(new rex_sql_foreign_key('fk_refis_personen', rex::getTable('skh3_personen'), ['person_id' => 'person_id'], $onUpdate = rex_sql_foreign_key::CASCADE))
		->ensureForeignKey(new rex_sql_foreign_key('fk_refis_seminare', rex::getTable('skh3_seminare'), ['seminar_id' => 'seminar_id'], $onUpdate = rex_sql_foreign_key::CASCADE, $onDelete = rex_sql_foreign_key::CASCADE))
		->ensure();
		
	// create skh3_verantwortung if not exists
	rex_sql_table::get(rex::getTable('skh3_verantwortung'))
		->ensureColumn(new rex_sql_column('seminar_id', 'mediumint(8) unsigned', $nullable = false))
		->ensureColumn(new rex_sql_column('person_id', 'mediumint(8) unsigned', $nullable = false))
		->setPrimaryKey(['seminar_id', 'person_id'])
		->ensureForeignKey(new rex_sql_foreign_key('fk_verantwortung_personen', rex::getTable('skh3_personen'), ['person_id' => 'person_id'], $onUpdate = rex_sql_foreign_key::CASCADE))
		->ensureForeignKey(new rex_sql_foreign_key('fk_verantwortung_seminare', rex::getTable('skh3_seminare'), ['seminar_id' => 'seminar_id'], $onUpdate = rex_sql_foreign_key::CASCADE, $onDelete = rex_sql_foreign_key::CASCADE))
		->ensure();	
	
	
	// create skh3_waehrung if not exists
	rex_sql_table::get(rex::getTable('skh3_waehrung'))
		->ensureColumn(new rex_sql_column('waehrung_id', 'tinyint(3) unsigned', $nullable = false, $default = null, $extra = 'AUTO_INCREMENT'))
		->ensureColumn(new rex_sql_column('bezeichnung', 'varchar(40)', $nullable = false))
		->ensureColumn(new rex_sql_column('kurzform', 'char(3)', $nullable = false))
		->setPrimaryKey('waehrung_id')
		->ensureIndex(new rex_sql_index('bezeichnung', ['bezeichnung'], $type = rex_sql_index::UNIQUE))
		->ensureIndex(new rex_sql_index('kurzform', ['kurzform'], $type = rex_sql_index::UNIQUE))
		->ensure();
	
	// create skh3_seminare_lok if not exists
	rex_sql_table::get(rex::getTable('skh3_seminare_lok'))
		->ensureColumn(new rex_sql_column('seminar_id', 'mediumint(8) unsigned', $nullable = false))
		->ensureColumn(new rex_sql_column('clang', 'int(11)', $nullable = false))
		->ensureColumn(new rex_sql_column('titel', 'varchar(255)', $nullable = false))
		->ensureColumn(new rex_sql_column('untertitel', 'varchar(255)', $nullable = false))
		->ensureColumn(new rex_sql_column('beschreibung', 'text', $nullable = false))
		->ensureColumn(new rex_sql_column('kosten', 'int(10)', $nullable = false, $default = 0))
		->ensureColumn(new rex_sql_column('waehrung_id', 'tinyint(3) unsigned', $nullable = false))
		->ensureColumn(new rex_sql_column('zielgruppe', 'varchar(255)', $nullable = false, $default = ''))
		->setPrimaryKey(['seminar_id', 'clang'])
		->ensureForeignKey(new rex_sql_foreign_key('fk_seminarelok_seminare', rex::getTable('skh3_seminare'), ['seminar_id' => 'seminar_id'], $onUpdate = rex_sql_foreign_key::CASCADE, $onDelete = rex_sql_foreign_key::CASCADE ))
		->ensureForeignKey(new rex_sql_foreign_key('fk_seminarlok_waehrung', rex::getTable('skh3_waehrung'), ['waehrung_id' => 'waehrung_id'], $onUpdate = rex_sql_foreign_key::CASCADE), $onDelete = rex_sql_foreign_key::CASCADE )
		->ensure();
	
	// create skh3_foerdern if not exists
	rex_sql_table::get(rex::getTable('skh3_foerdern'))
		->ensureColumn(new rex_sql_column('seminar_id', 'mediumint(8) unsigned', $nullable = false))
		->ensureColumn(new rex_sql_column('geldgeber_id', 'mediumint(8) unsigned', $nullable = false))
		->setPrimaryKey(['seminar_id', 'geldgeber_id'])
		->ensureForeignKey(new rex_sql_foreign_key('fk_foerd_geldgeber', rex::getTable('skh3_geldgeber'), ['geldgeber_id' => 'geldgeber_id'], $onUpdate = rex_sql_foreign_key::CASCADE))
		->ensureForeignKey(new rex_sql_foreign_key('fk_foerd_seminare', rex::getTable('skh3_seminare'), ['seminar_id' => 'seminar_id'], $onUpdate = rex_sql_foreign_key::CASCADE), $onDelete = rex_sql_foreign_key::CASCADE )
		->ensure();
	
	
	// create skh3_koop if not exists
	rex_sql_table::get(rex::getTable('skh3_koop'))
		->ensureColumn(new rex_sql_column('seminar_id', 'mediumint(8) unsigned', $nullable = false))
		->ensureColumn(new rex_sql_column('partner_id', 'mediumint(8) unsigned', $nullable = false))
		->setPrimaryKey(['seminar_id', 'partner_id'])
		->ensureForeignKey(new rex_sql_foreign_key('fk_koop_partner', rex::getTable('skh3_partner'), ['partner_id' => 'partner_id'], $onUpdate = rex_sql_foreign_key::CASCADE))
		->ensureForeignKey(new rex_sql_foreign_key('fk_koop_seminare', rex::getTable('skh3_seminare'), ['seminar_id' => 'seminar_id'], $onUpdate = rex_sql_foreign_key::CASCADE), $onDelete = rex_sql_foreign_key::CASCADE )
		->ensure();
	
		
  // create skh3_err_msg if not exists
	rex_sql_table::get(rex::getTable('skh3_err_msg'))
		->ensureColumn(new rex_sql_column('msg', 'varchar(80)', $nullable = false))
		->setPrimaryKey('msg')
		->ensure();
?>
