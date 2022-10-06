<?php
  error_reporting(E_ALL);
  $lang_id = rex_request('lang_id', 'int');
  $func = rex_request('func', 'string');

  //Seminartypen auflisten
  if ($func == '') 
  {
    $query = 'SELECT typ_id, lang_id, bezeichnung FROM ' . rex::getTablePrefix() .'skh3_seminartyp_lok ORDER BY typ_id, lang_id ' ;
    //echo $query ;
    $list = rex_list::factory($query) ;
    $thIcon = '<a href="' . $list->getUrl(['func' => 'add']) . '" title="'.$this->i18n('column_hashtag') . ' ' . rex_i18n::msg('add') . '"><i class="rex-icon rex-icon-add-action"></i></a>';
    //Spalte zum hinzufügen und editieren
    $tdIcon = '<i class="rex-icon fa-file-text-o"></i>';
		$list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
    $list->setColumnParams($thIcon, array('func' => 'edit','typ_id' => '###typ_id###'));
    //spalte zum löschen
    $tdDelete = 'löschen';
    $list->addColumn('Löschen', $tdDelete, 5, array( '<th>###VALUE###</th>', '<td class="rex-icon">###VALUE###</td>' ));
    $list->setColumnParams('Löschen', array('func' => 'del','typ_id' => '###typ_id###'));
    $list->addTableColumnGroup(array(5, 5, 5, '*', 5));
    $list->setColumnLabel('typ_id', 'Seminartyp'); 
    $list->setColumnLabel('lang_id', 'Sprache');
    $list->setColumnLabel('bezeichnung', 'Bezeichnung');
    $list->show();
	}
  //Typ ändern oder hinzufügen
  if ($func == 'add' || $func == 'edit')
  {
		$typ_id = rex_request('typ_id', 'int');
    if($func == 'add') 
    { //wenn kein Seminartyp ausgewählt wurde
      $formLabel = $this->i18n('seminartyp_formcaption_add');
      $seminartyp = new skh3\seminartyp(null) ;
    }
    //ID für add und 
    if($func == 'edit')
    {
      $formLabel = $this->i18n('seminartyp_formcaption_edit');
      $seminartyp = new skh3\seminartyp($typ_id) ;
    }
    /*
    //Formular anzeigen
    * rex_form geht hier doof oder ich bin zu doof
    $form = rex_form::factory(rex::getTable('skh3_seminartyp_lok'), '', 'typ_id='.rex_request('typ_id', 'int', 0));
    $form->setLanguageSupport('typ_id','lang_id');
    //Start - add bezeichnung-field
		$field = $form->addTextField('bezeichnung');
		//$field->setLabel($this->i18n('snippets_label_description'));
		$field->setLabel('Bezeichnung');
		//End - add bezeichnung-field

		if ($func == 'edit') {
			$form->addParam('typ_id', $typ_id);
		}

		$content = $form->get();
		$fragment = new rex_fragment();
		$fragment->setVar('class', 'edit', false);
		$fragment->setVar('title', $formLabel, false);
		$fragment->setVar('body', $content, false);
		$content = $fragment->parse('core/page/section.php');

		echo $content;
		*/
    echo('<div class="rex-addon-output">') ;
    $headline = $func == 'edit' ? 'seminartyp ändern' : 'Neuer Seminartyp' ; 
    echo('<h2 class="rex-hl2">' . $headline . ' (ID ' . $seminartyp->getTypID() . ' )</h2>') ;
    if(isset($_POST['submit']))
    {
      //hier Array für Typen
      foreach($_POST['bezeichnung'] as $key => $value)
      {
				echo('key: '.$key.' value: '.$value);
        $seminartyp->setClang($key, $key);
        $seminartyp->setBezeichnung($key, $value);
      }
      if($seminartyp->seminartypSave())
        echo('Speichern erfolgreich');
      else
        echo('ein Fehler ist aufgetreten');
    }
    else
    {
      echo('<div class="skh3-form">') ;
      echo ('<form action="" method="post">') ;
      //Sprachen aus DB holen
      $sql = rex_sql::factory();
      $queryClang = 'SELECT id, name FROM ' . rex::getTablePrefix() . 'clang;' ;
      $sql->setQuery($queryClang) ;
      for($i = 0; $i < $sql->getRows(); $i++)
      {
        echo '<h3>' . $sql->getValue('name') .'</h3>';
        echo '<label for="bezeichnung">Bezeichnung</label><input class="txt" type="text" name="bezeichnung[]" id="bezeichung" value="'.$seminartyp->getBezeichnung($i).'" /><br />';
        $sql->next();
      }
      echo('<input type="submit" class="btn" name="submit" value="Speichern" />') ;
      echo('</form></div></div>') ;
    }
  }
  //Seminartyp löschen
  if($func == 'del')
  {
		$typ_id = rex_request('typ_id', 'int');
    $seminartyp = new skh3\seminartyp($typ_id);
    $seminartyp->seminartypDelete();
  }
?>

