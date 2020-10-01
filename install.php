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
		->ensureForeignKey(new rex_sql_foreign_key('fk_foerd_seminare', rex::getTable('skh3_seminare'), ['seminar_id' => 'seminar_id'], $onUpdate = rex_sql_foreign_key::CASCADE, $onDelete = rex_sql_foreign_key::CASCADE))
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
		
		
		
		
  $waehrungdata = [
    ['waehrung_id' => 0 , 'bezeichnung' => 0, 'kurzform' => 'k.A'],
    ['waehrung_id' => 1 , 'bezeichnung' => 'Euro', 'kurzform' => 'EUR'],
    ['waehrung_id' => 2 , 'bezeichnung' => 'Polnischer Zloty', 'kurzform' => 'PLN'],
    ['waehrung_id' => 3 , 'bezeichnung' => 'Ägyptisches Pfund', 'kurzform' => 'EGP'],
    ['waehrung_id' => 4 , 'bezeichnung' => 'Albanischer Lek', 'kurzform' => 'ALL'],
    ['waehrung_id' => 5 , 'bezeichnung' => 'Algerischer Dinar', 'kurzform' => 'DZD'],
    ['waehrung_id' => 6 , 'bezeichnung' => 'Argentinischer Peso', 'kurzform' => 'ARS'],
    ['waehrung_id' => 7 , 'bezeichnung' => 'Aruba-Florin', 'kurzform' => 'AWG'],
    ['waehrung_id' => 8 , 'bezeichnung' => 'Äthiopischer Birr', 'kurzform' => 'ETB'],
    ['waehrung_id' => 9 , 'bezeichnung' => 'Australischer Dollar', 'kurzform' => 'AUD'],
    ['waehrung_id' => 10 , 'bezeichnung' => 'Bahama Dollar', 'kurzform' => 'BSD'],
    ['waehrung_id' => 11 , 'bezeichnung' => 'Bahrain-Dinar', 'kurzform' => 'BHD'],
    ['waehrung_id' => 12 , 'bezeichnung' => 'Bangladeschischer Taka', 'kurzform' => 'BDT'],
    ['waehrung_id' => 13 , 'bezeichnung' => 'Barbados-Dollar', 'kurzform' => 'BBD'],
    ['waehrung_id' => 14 , 'bezeichnung' => 'Belarusian Rubel', 'kurzform' => 'BYR'],
    ['waehrung_id' => 15 , 'bezeichnung' => 'Belize-Dollar', 'kurzform' => 'BZD'],
    ['waehrung_id' => 16 , 'bezeichnung' => 'Bermuda-Dollar', 'kurzform' => 'BMD'],
    ['waehrung_id' => 17 , 'bezeichnung' => 'Bhutanischer Ngultrum', 'kurzform' => 'BTN'],
    ['waehrung_id' => 18 , 'bezeichnung' => 'Bolivianischer Boliviano', 'kurzform' => 'BOB'],
    ['waehrung_id' => 19 , 'bezeichnung' => 'Botswanischer Pula', 'kurzform' => 'BWP'],
    ['waehrung_id' => 20 , 'bezeichnung' => 'Brasilianischer Real', 'kurzform' => 'BRL'],
    ['waehrung_id' => 21 , 'bezeichnung' => 'Britisches Pfund', 'kurzform' => 'GBP'],
    ['waehrung_id' => 22 , 'bezeichnung' => 'Brunei-Dollar', 'kurzform' => 'BND'],
    ['waehrung_id' => 23 , 'bezeichnung' => 'Bulgarischer Lew', 'kurzform' => 'BGN'],
    ['waehrung_id' => 24 , 'bezeichnung' => 'Burundi-Franc', 'kurzform' => 'BIF'],
    ['waehrung_id' => 25 , 'bezeichnung' => 'CFA Franc BCEAO', 'kurzform' => 'XOF'],
    ['waehrung_id' => 26 , 'bezeichnung' => 'Chilenischer Peso', 'kurzform' => 'CLP'],
    ['waehrung_id' => 27 , 'bezeichnung' => 'Chinesischer Renminbi', 'kurzform' => 'CNY'],
    ['waehrung_id' => 28 , 'bezeichnung' => 'Costa-Rican-Colón', 'kurzform' => 'CRC'],
    ['waehrung_id' => 29 , 'bezeichnung' => 'Dänische Krone', 'kurzform' => 'DKK'],
    ['waehrung_id' => 30 , 'bezeichnung' => 'Djibouti-Franc', 'kurzform' => 'DJF'],
    ['waehrung_id' => 31 , 'bezeichnung' => 'Dominikanischer Peso', 'kurzform' => 'DOP'],
    ['waehrung_id' => 32 , 'bezeichnung' => 'Ecuadorianischer Sucre', 'kurzform' => 'ECS'],
    ['waehrung_id' => 33 , 'bezeichnung' => 'El-Salvador-Colon', 'kurzform' => 'SVC'],
    ['waehrung_id' => 34 , 'bezeichnung' => 'Eritreischer Nafra', 'kurzform' => 'ERN'],
    ['waehrung_id' => 35 , 'bezeichnung' => 'Estnische Krone', 'kurzform' => 'EEK'],
    ['waehrung_id' => 36 , 'bezeichnung' => 'Falkland Pfund', 'kurzform' => 'FKP'],
    ['waehrung_id' => 37 , 'bezeichnung' => 'Fidschi Dollar', 'kurzform' => 'FJD'],
    ['waehrung_id' => 38 , 'bezeichnung' => 'Französischer Pazifischer Franc', 'kurzform' => 'XPF'],
    ['waehrung_id' => 39 , 'bezeichnung' => 'Gambischer Dalasi', 'kurzform' => 'GMD'],
    ['waehrung_id' => 40 , 'bezeichnung' => 'Ghanaischer Cedi', 'kurzform' => 'GHC'],
    ['waehrung_id' => 41 , 'bezeichnung' => 'Gibraltar-Pfund', 'kurzform' => 'GIP'],
    ['waehrung_id' => 42 , 'bezeichnung' => 'Guatemaltekischer Quetzal', 'kurzform' => 'GTQ'],
    ['waehrung_id' => 43 , 'bezeichnung' => 'Guinea-Franc', 'kurzform' => 'GNF'],
    ['waehrung_id' => 44 , 'bezeichnung' => 'Guyana-Dollar', 'kurzform' => 'GYD'],
    ['waehrung_id' => 45 , 'bezeichnung' => 'Haitianische Gourde', 'kurzform' => 'HTG'],
    ['waehrung_id' => 46 , 'bezeichnung' => 'Honduranische Lempira', 'kurzform' => 'HNL'],
    ['waehrung_id' => 47 , 'bezeichnung' => 'Hong-Kong Dollar', 'kurzform' => 'HKD'],
    ['waehrung_id' => 48 , 'bezeichnung' => 'Indische Rupie', 'kurzform' => 'INR'],
    ['waehrung_id' => 49 , 'bezeichnung' => 'Indonesische Rupie', 'kurzform' => 'IDR'],
    ['waehrung_id' => 50 , 'bezeichnung' => 'Irakischer Dinar', 'kurzform' => 'IQD'],
    ['waehrung_id' => 51 , 'bezeichnung' => 'Iranischer Rial', 'kurzform' => 'IRR'],
    ['waehrung_id' => 52 , 'bezeichnung' => 'Isländische Krone', 'kurzform' => 'ISK'],
    ['waehrung_id' => 53 , 'bezeichnung' => 'Israelischer Schekel', 'kurzform' => 'ILS'],
    ['waehrung_id' => 54 , 'bezeichnung' => 'Jamaika-Dollar', 'kurzform' => 'JMD'],
    ['waehrung_id' => 55 , 'bezeichnung' => 'Japanischer Yen', 'kurzform' => 'JPY'],
    ['waehrung_id' => 56 , 'bezeichnung' => 'Jordanischer Dinar', 'kurzform' => 'JOD'],
    ['waehrung_id' => 57 , 'bezeichnung' => 'Kaiman-Dollar', 'kurzform' => 'KYD'],
    ['waehrung_id' => 58 , 'bezeichnung' => 'Kambodschanischer Riel', 'kurzform' => 'KHR'],
    ['waehrung_id' => 59 , 'bezeichnung' => 'Kanadischer Dollar', 'kurzform' => 'CAD'],
    ['waehrung_id' => 60 , 'bezeichnung' => 'Kape-Verde-Escudo', 'kurzform' => 'CVE'],
    ['waehrung_id' => 61 , 'bezeichnung' => 'Katar Rial', 'kurzform' => 'QAR'],
    ['waehrung_id' => 62 , 'bezeichnung' => 'Kazakhstani Tenge', 'kurzform' => 'KZT'],
    ['waehrung_id' => 63 , 'bezeichnung' => 'Kenianischer Schilling', 'kurzform' => 'KES'],
    ['waehrung_id' => 64 , 'bezeichnung' => 'Kolumbianischer Peso', 'kurzform' => 'COP'],
    ['waehrung_id' => 65 , 'bezeichnung' => 'Komoren-Franc', 'kurzform' => 'KMF'],
    ['waehrung_id' => 66 , 'bezeichnung' => 'Kroatische Kuna', 'kurzform' => 'HRK'],
    ['waehrung_id' => 67 , 'bezeichnung' => 'Kubanischer Peso', 'kurzform' => 'CUP'],
    ['waehrung_id' => 68 , 'bezeichnung' => 'Kuwaitischer Dinar', 'kurzform' => 'KWD'],
    ['waehrung_id' => 69 , 'bezeichnung' => 'Laotischer Kip', 'kurzform' => 'LAK'],
    ['waehrung_id' => 70 , 'bezeichnung' => 'Lesothischer Loti', 'kurzform' => 'LSL'],
    ['waehrung_id' => 71 , 'bezeichnung' => 'Lettischer Lats', 'kurzform' => 'LVL'],
    ['waehrung_id' => 72 , 'bezeichnung' => 'Libanesisches Pfund', 'kurzform' => 'LBP'],
    ['waehrung_id' => 73 , 'bezeichnung' => 'Liberianischer Dollar', 'kurzform' => 'LRD'],
    ['waehrung_id' => 74 , 'bezeichnung' => 'Libyscher Dinar', 'kurzform' => 'LYD'],
    ['waehrung_id' => 75 , 'bezeichnung' => 'Litauischer Litas', 'kurzform' => 'LTL'],
    ['waehrung_id' => 76 , 'bezeichnung' => 'Macau Pataca', 'kurzform' => 'MOP'],
    ['waehrung_id' => 77 , 'bezeichnung' => 'Malawi-Kwacha', 'kurzform' => 'MWK'],
    ['waehrung_id' => 78 , 'bezeichnung' => 'Malaysischer Ringgit', 'kurzform' => 'MYR'],
    ['waehrung_id' => 79 , 'bezeichnung' => 'Maledivischer Rufiyan', 'kurzform' => 'MVR'],
    ['waehrung_id' => 80 , 'bezeichnung' => 'Maltesische Lira', 'kurzform' => 'MTL'],
    ['waehrung_id' => 81 , 'bezeichnung' => 'Marrokanischer Dirham', 'kurzform' => 'MAD'],
    ['waehrung_id' => 82 , 'bezeichnung' => 'Mauretanischer Ouguiya', 'kurzform' => 'MRO'],
    ['waehrung_id' => 83 , 'bezeichnung' => 'Mauritius-Rupie', 'kurzform' => 'MUR'],
    ['waehrung_id' => 84 , 'bezeichnung' => 'Mazedonischer Denar', 'kurzform' => 'MKD'],
    ['waehrung_id' => 85 , 'bezeichnung' => 'Mexikanischer Peso', 'kurzform' => 'MXN'],
    ['waehrung_id' => 86 , 'bezeichnung' => 'Moldau-Leu', 'kurzform' => 'MDL'],
    ['waehrung_id' => 87 , 'bezeichnung' => 'Mongolisher Tugrik', 'kurzform' => 'MNT'],
    ['waehrung_id' => 88 , 'bezeichnung' => 'Myanmarischer Kyat', 'kurzform' => 'MMK'],
    ['waehrung_id' => 89 , 'bezeichnung' => 'Namibischer Dollar', 'kurzform' => 'NAD'],
    ['waehrung_id' => 90 , 'bezeichnung' => 'Nepalesische Rupie', 'kurzform' => 'NPR'],
    ['waehrung_id' => 91 , 'bezeichnung' => 'Neuseeländischer Dollar', 'kurzform' => 'NZD'],
    ['waehrung_id' => 92 , 'bezeichnung' => 'Nicaraguanischer Cordoba', 'kurzform' => 'NIO'],
    ['waehrung_id' => 93 , 'bezeichnung' => 'Nigerianische Naira', 'kurzform' => 'NGN'],
    ['waehrung_id' => 94 , 'bezeichnung' => 'NL-Antillen-Gulden', 'kurzform' => 'ANG'],
    ['waehrung_id' => 95 , 'bezeichnung' => 'Nordkoreanischer Won', 'kurzform' => 'KPW'],
    ['waehrung_id' => 96 , 'bezeichnung' => 'Norwegische Krone', 'kurzform' => 'NOK'],
    ['waehrung_id' => 97 , 'bezeichnung' => 'Omanischer Rial', 'kurzform' => 'OMR'],
    ['waehrung_id' => 98 , 'bezeichnung' => 'Ostkaribischer Dollar', 'kurzform' => 'XCD'],
    ['waehrung_id' => 99 , 'bezeichnung' => 'Pakistanische Rupie', 'kurzform' => 'PKR'],
    ['waehrung_id' => 100 , 'bezeichnung' => 'Panamesisches Balboa', 'kurzform' => 'PAB'],
    ['waehrung_id' => 101 , 'bezeichnung' => 'Papua-Neuguinea-Kina', 'kurzform' => 'PGK'],
    ['waehrung_id' => 102 , 'bezeichnung' => 'Paraguay Guarani', 'kurzform' => 'PYG'],
    ['waehrung_id' => 103 , 'bezeichnung' => 'Peruanischer Sol', 'kurzform' => 'PEN'],
    ['waehrung_id' => 104 , 'bezeichnung' => 'Philippinischer Peso', 'kurzform' => 'PHP'],
    ['waehrung_id' => 105 , 'bezeichnung' => 'Ruanda-Franc', 'kurzform' => 'RWF'],
    ['waehrung_id' => 106 , 'bezeichnung' => 'Rumänischer Leu', 'kurzform' => 'RON'],
    ['waehrung_id' => 107 , 'bezeichnung' => 'Russischer Rubel', 'kurzform' => 'RUB'],
    ['waehrung_id' => 108 , 'bezeichnung' => 'Salomonen-Dollar', 'kurzform' => 'SBD'],
    ['waehrung_id' => 109 , 'bezeichnung' => 'Sambischer Kwacha', 'kurzform' => 'ZMK'],
    ['waehrung_id' => 110 , 'bezeichnung' => 'Sao Tome Dobras', 'kurzform' => 'STD'],
    ['waehrung_id' => 111 , 'bezeichnung' => 'Saudiarabischer Rial', 'kurzform' => 'SAR'],
    ['waehrung_id' => 112 , 'bezeichnung' => 'Schwedische Krone', 'kurzform' => 'SEK'],
    ['waehrung_id' => 113 , 'bezeichnung' => 'Schweizer Franken', 'kurzform' => 'CHF'],
    ['waehrung_id' => 114 , 'bezeichnung' => 'Seychellen-Rupie', 'kurzform' => 'SCR'],
    ['waehrung_id' => 115 , 'bezeichnung' => 'Sierraleonische Leone', 'kurzform' => 'SLL'],
    ['waehrung_id' => 116 , 'bezeichnung' => 'Simbabwe-Dollar', 'kurzform' => 'ZWD'],                 
    ['waehrung_id' => 117 , 'bezeichnung' => 'Singapur Dollar', 'kurzform' => 'SGD'],
    ['waehrung_id' => 118 , 'bezeichnung' => 'Slovakische Krone', 'kurzform' => 'SKK'],
    ['waehrung_id' => 119 , 'bezeichnung' => 'Slovenischer Tolar', 'kurzform' => 'SIT'],
    ['waehrung_id' => 120 , 'bezeichnung' => 'Somalischer Schilling', 'kurzform' => 'SOS'],
    ['waehrung_id' => 121 , 'bezeichnung' => 'Sri-Lanka-Rupie', 'kurzform' => 'LKR'],
    ['waehrung_id' => 122 , 'bezeichnung' => 'St. Helena Pfund', 'kurzform' => 'SHP'],
    ['waehrung_id' => 123 , 'bezeichnung' => 'Südafrikanischer Rand', 'kurzform' => 'ZAR'],
    ['waehrung_id' => 124 , 'bezeichnung' => 'Sudanesisches Pfund', 'kurzform' => 'SDG'],
    ['waehrung_id' => 125 , 'bezeichnung' => 'Südkoreanische Won', 'kurzform' => 'KRW'],
    ['waehrung_id' => 126 , 'bezeichnung' => 'Swasiländischer Lilangeni', 'kurzform' => 'SZL'],
    ['waehrung_id' => 127 , 'bezeichnung' => 'Syrisches Pfund', 'kurzform' => 'SYP'],
    ['waehrung_id' => 128 , 'bezeichnung' => 'Taiwanesischer Dollar', 'kurzform' => 'TWD'],
    ['waehrung_id' => 129 , 'bezeichnung' => 'Tansania-Schilling', 'kurzform' => 'TZS'],
    ['waehrung_id' => 130 , 'bezeichnung' => 'Thailändischer Baht', 'kurzform' => 'THB'],
    ['waehrung_id' => 131 , 'bezeichnung' => 'Tongaische Paanga', 'kurzform' => 'TOP'],
    ['waehrung_id' => 132 , 'bezeichnung' => 'Trinidad/Tobago Dollar', 'kurzform' => 'TTD'],
    ['waehrung_id' => 133 , 'bezeichnung' => 'Tschechische Krone', 'kurzform' => 'CZK'],
    ['waehrung_id' => 134 , 'bezeichnung' => 'Tunesischer Dinar', 'kurzform' => 'TND'],
    ['waehrung_id' => 135 , 'bezeichnung' => 'Türkische Lira', 'kurzform' => 'TRY'],
    ['waehrung_id' => 136 , 'bezeichnung' => 'Uganda Schilling', 'kurzform' => 'UGX'],
    ['waehrung_id' => 137 , 'bezeichnung' => 'Ukrainische Griwna', 'kurzform' => 'UAH'],
    ['waehrung_id' => 138 , 'bezeichnung' => 'Ungarische Forint', 'kurzform' => 'HUF'],
    ['waehrung_id' => 139 , 'bezeichnung' => 'Unzen Aluminium', 'kurzform' => 'XAL'],
    ['waehrung_id' => 140 , 'bezeichnung' => 'Unzen Kupfer', 'kurzform' => 'XCP'],
    ['waehrung_id' => 141 , 'bezeichnung' => 'Unzen Palladium', 'kurzform' => 'XPD'],
    ['waehrung_id' => 142 , 'bezeichnung' => 'Unzen Platin', 'kurzform' => 'XPT'],
    ['waehrung_id' => 143 , 'bezeichnung' => 'Unzen Silber', 'kurzform' => 'XAG'],
    ['waehrung_id' => 144 , 'bezeichnung' => 'Uruguayischer Peso', 'kurzform' => 'UYU'],
    ['waehrung_id' => 145 , 'bezeichnung' => 'US-Dollar', 'kurzform' => 'USD'],
    ['waehrung_id' => 146 , 'bezeichnung' => 'VAE-Dirham', 'kurzform' => 'AED'],
    ['waehrung_id' => 147 , 'bezeichnung' => 'Vanuatu-Vatu', 'kurzform' => 'VUV'],
    ['waehrung_id' => 148 , 'bezeichnung' => 'Venezuelanischer Bolivar Fuerte', 'kurzform' => 'VEF'],
    ['waehrung_id' => 149 , 'bezeichnung' => 'Vietnamesischer Dong', 'kurzform' => 'VND'],
    ['waehrung_id' => 150 , 'bezeichnung' => 'West-Samoanischer Tala', 'kurzform' => 'WST'],
    ['waehrung_id' => 151 , 'bezeichnung' => 'Yemeni Rial', 'kurzform' => 'YER'],
    ['waehrung_id' => 152 , 'bezeichnung' => 'Zentraler Afrikaner CfA BEAC', 'kurzform' => 'XAF']
  ] ;
  $sql = rex_sql::factory() ;
  $sql->setTable(rex::getTable('skh3_waehrung')) ;
  foreach ($waehrungdata as $row) {
    $sql->addRecord(static function (rex_sql $record) use ($row) {
      $record->setValues($row) ;
    }) ;
  }
  $sql->insertOrUpdate() ;

?>
