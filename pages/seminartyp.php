<?php
    error_reporting('E_ALL');
    echo("seminartypen") ;
    $typ_id = rex_request('typ_id', 'int');
    $lang_id = rex_request('lang_id', 'int');
    $func = rex_request('func', 'string');

    //Seminartypen auflisten
    if ($func == '') 
	{
		$query = 'SELECT typ_id, lang_id, bezeichnung FROM ' . rex::getTablePrefix() .'skh3_seminartyp_lok ORDER BY typ_id, lang_id ;' ;
                echo $query ;
//		$list = rex_list::factory($query) ;
                $sql = rex_sql::factory();
                $result = $sql->setQuery($query);
                echo gettype($result) ;
                echo $result->getRows() ;
//		$thIcon = '<a href="'. $list->getUrl(array('func' => 'add')) .'"><img src="media/document_plus.gif" alt="add" title="add" /></a>' ;
		//bis redaxo 4.4: $tdIcon = '<a href="'. $list->getUrl(array('func' => 'edit','typ_id' => '###typ_id###')).'"><img src="media/document.gif" alt="edit" title="edit" /></a>';
    //bis redaxo 4.4: $tdDelete = '<a href="'. $list->getUrl(array('func' => 'del','typ_id' => '###typ_id###')).'">löschen</a>';
//		$tdIcon = '<img src="media/document.gif" alt="edit" title="edit" />';
//		$tdDelete = 'löschen';
    //Spalte zum hinzufügen und editieren
//		$list->addColumn($thIcon, $tdIcon, 0, array( '<th class="rex-icon">###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' )); 
//		$list->setColumnParams($thIcon, array('func' => 'edit','typ_id' => '###typ_id###'));
		//spalte zum löschen
//		$list->addColumn('Löschen', $tdDelete, 5, array( '<th>###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' ));
//		$list->setColumnParams('Löschen', array('func' => 'del','typ_id' => '###typ_id###'));
//		$list->addTableColumnGroup(array(5, 5, 5, '*', 5));
//		$list->setColumnLabel('typ_id', 'Seminartyp'); 
//		$list->setColumnLabel('lang_id', 'Sprache');
//		$list->setColumnLabel('bezeichnung', 'Bezeichnung');
//		$list->show();
	}
  //Typ ändern oder hinzufügen
//  if ($func == 'add' || $func == 'edit') 
//  {             
//		if($func == 'add') //wenn kein Seminartyp ausgewählt wurde
//			$seminartyp = new seminartyp(null) ;
		//ID für add und 
//		if($func == 'edit')
//			$seminartyp = new seminartyp($typ_id) ;
		//Formular anzeigen
?>
<div class="rex-addon-output">
<?php $headline = $func == 'edit' ? 'seminartyp ändern' : 'Neuer Seminartyp'; ?>
	<h2 class="rex-hl2"><?php echo $headline; echo (' (ID ' . $seminartyp->getTypID() . ' )'); ?></h2>
<?php
//	if(isset($_POST['submit'])) 
//	{
		//hier Array für Typen
//		foreach($_POST['bezeichnung'] as $key => $value)
//		{
			//$seminartyp->setClang($key, $key);
//			$seminartyp->setBezeichnung($key, $value);
//		}
//		if($seminartyp->seminartypSave())
//			echo('Speichern erfolgreich');
//		else
//			echo('ein Fehler ist aufgetreten');
//	}
//	else
//	{ 
//?>
	<div class="skh3-form">
		<form action="" method="post">
//<?php
	//Sprachen aus DB holen
//	$sql = rex_sql::factory();
//	$queryClang = 'SELECT id, name FROM ' . $REX['TABLE_PREFIX'] . 'clang;' ;
//	$sql->setQuery($queryClang);
//	for($i = 0; $i < $sql->getRows(); $i++)
//	{
//		echo '<h3>' . $sql->getValue('name') .'</h3>';
//		echo '<label for="bezeichnung">Bezeichnung</label><input class="txt" type="text" name="bezeichnung[]"// id="bezeichung" value="'.$seminartyp->getBezeichnung($i).'" /><br />';
//		$sql->next();
//	}
//?>
			<input type="submit" class="btn" name="submit" value="Speichern" />
		</form>
	</div>
</div>
<?php
//	}
//	}
	//Seminartyp löschen
//	if($func == 'del')
//	{
//		$seminartyp = new seminartyp($typ_id);
//		$seminartyp->seminartypDelete();
//	}
?>

