<?php
	error_reporting(E_ALL) ;
	$func = rex_request('func', 'string') ;
	echo("hier kommt das geldgebergeraffel") ;

  if ($func == '') 
  {
    $query='SELECT geldgeber_id, name, website FROM ' . rex::getTablePrefix() . 'skh3_geldgeber' ;
    $list = rex_list::factory($query) ;
    //Spalte zum Hinzufügen und Editieren
    $thIcon = '<a href="' . $list->getUrl(['func' => 'add']) . '" title="'.$this->i18n('column_hashtag') . ' ' . rex_i18n::msg('add') . '"><i class="rex-icon rex-icon-add-action"></i></a>' ;
    $tdIcon = '<i class="rex-icon fa-file-text-o"></i>';
		$list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']) ;
    $list->setColumnParams($thIcon, array('func' => 'edit','geldgeber_id' => '###geldgeber_id###')) ;
    //spalte zum löschen
    $tdDelete = 'löschen' ;
    $list->addColumn('Löschen', $tdDelete, 4, array( '<th>###VALUE###</th>', '<td class="rex-icon">###VALUE###</td>' )) ;
    $list->setColumnParams('Löschen',array('func' => 'del','geldgeber_id' => '###geldgeber_id###')) ;
    //id entfernen
    $list->removeColumn('geldgeber_id') ;
    $list->addTableColumnGroup(array(5, '*', '*', 5)) ;
    $list->setColumnLabel('name', 'Geldgeber') ;
    $list->setColumnLabel('website', 'Website') ;
    //felder sortierbar machen
    $list->setColumnSortable('name') ;
    $list->show() ;
  }
  //geldgeber hinzufügen oder ändern
  if ($func == 'add' || $func == 'edit') 
  {
		$geldgeber_id = rex_request('geldgeber_id', 'int') ;
    //if($func == 'add') //wenn keine geldgeber ausgewählt wurde
    //  $geldgeber = new skh3\geldgeber(null) ;
    //ID für add und 
    //if($func == 'edit')
    //  $geldgeber = new skh3\geldgeber($geldgeber_id);
    //Formular anzeigen
    $form = rex_form::factory(rex::getTable('skh3_geldgeber'), '', 'geldgeber_id=' . rex_request('geldgeber_id', 'int', 0), 'post', rex::getProperty('debug'));
    //Start - add name-field
		$field = $form->addTextField('name');
		//$field->setLabel($this->i18n('snippets_label_description'));
		$field->setLabel('Name');
		$field->getValidator()->add( 'notEmpty', 'Das Feld Name darf nicht leer sein.');
		//End - add name-field
		//Start - add website-field
		$field = $form->addTextField('website');
		//$field->setLabel($this->i18n('snippets_label_description'));
		$field->setLabel('Website');
		$field->getValidator()->add( 'notEmpty', 'Das Feld Website darf nicht leer sein.');
		$field->getValidator()->add( 'url', 'Bitte eine url eingeben');
		//End - add website-field

		if ($func == 'edit') {
			$form->addParam('geldgeber_id', $geldgeber_id) ;
		}

		$content = $form->get() ;
		$fragment = new rex_fragment() ;
		$fragment->setVar('class', 'edit', false) ;
		$fragment->setVar('title', $formLabel, false) ;
		$fragment->setVar('body', $content, false) ;
		$content = $fragment->parse('core/page/section.php') ;

		echo $content ;
		
		/*
    echo('<div class="rex-addon-output">') ;
    $headline = $func == 'edit' ? 'Geldgeber bearbeiten' : 'neuer Geldgeber' ; 
    echo('<h2 class="rex-hl2">') ; 
    echo $headline ; 
    echo(' (ID ' . $geldgeber->getgeldgeberID() . ' )</h2>') ;
    if(isset($_POST['submit'])) 
    {
      $geldgeber->setName($_POST['name']) ;
      $geldgeber->setWebseite($_POST['website']) ;
      if($geldgeber->geldgeberSave())
        echo('Speichern erfolgreich') ;
      else
        echo('ein Fehler ist aufgetreten') ;
    }
    else
    { 
      echo('<div class="skh3-form">') ;
      echo('<form action="" method="post">') ;
      echo('<label for="name">Name</label><input type="text" class="txt" name="name" id="name" value="' . $geldgeber->getName() .'" /><br />') ;
      echo('<label for="website">Webseite</label><input type="text" class="txt" name="website" id="website" value="'. $geldgeber->getWebseite() . '" /><br />') ;
      echo('<input type="submit" class="btn" name="submit" value="Speichern" />') ;
      echo('</form></div></div>') ;
    }
    */
  }
  //geldgeber löschen
  if($func == 'del')
  {
		$geldgeber_id = rex_request('geldgeber_id', 'int');
    $geldgeber = new skh3\geldgeber($geldgeber_id);
    $geldgeber->geldgeberDelete();
  }
?>
