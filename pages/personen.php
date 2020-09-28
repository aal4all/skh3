<?php
	error_reporting('E_ALL');
	$func = rex_request('func', 'string') ;
	echo("Hier kommt das PErsonengeraffel") ;

  if ($func == '') 
  {
    $query='SELECT person_id, vorname, name FROM ' . rex::getTablePrefix() . 'skh3_personen' ;
    $list = rex_list::factory($query) ;
    $thIcon = '<a href="' . $list->getUrl(['func' => 'add']) . '" title="' . $this->i18n('column_hashtag') . ' ' . rex_i18n::msg('add') . '"><i class="rex-icon rex-icon-add-action"></i></a>' ;
    $tdIcon = '<i class="rex-icon fa-file-text-o"></i>' ;
    $list->addColumn($thIcon, $tdIcon, 0, array( '<th class="rex-icon">###VALUE###</th>', '<td class="rex-icon">###VALUE###</td>' )) ;
    $list->setColumnParams($thIcon, array('func' => 'edit','person_id' => '###person_id###')) ;
    //spalte zum löschen
    $tdDelete = 'löschen' ;
    $list->addColumn('Löschen', $tdDelete, 5, array( '<th>###VALUE###</th>', '<td class="rex-icon">###VALUE###</td>' )) ;
    $list->setColumnParams('Löschen', array('func' => 'del','person_id' => '###person_id###')) ;
    //id entfernen
    $list->removeColumn('person_id') ;
    $list->addTableColumnGroup(array(5, '*', '*', 5)) ;
    $list->setColumnLabel('vorname', 'Vorname') ; 
    $list->setColumnLabel('name', 'Name') ;
    //felder sortierbar machen
    $list->setColumnSortable('vorname') ;
    $list->setColumnSortable('name') ;
    $list->show();
  }
  //Namen ändern oder hinzufügen
  if ($func == 'add' || $func == 'edit') 
  {
    $person_id = rex_request('person_id', 'int') ;
    //if($func == 'add') //wenn keine Person ausgewählt wurde
    //  $person = new skh3\personen(null, $lang_id) ;
    //ID für add und 
    //if($func == 'edit')
    //  $person = new skh3\personen($person_id, $lang_id) ;
    //Formular anzeigen
    $form = rex_form::factory(rex::getTable('skh3_personen'), '', 'person_id=' . rex_request('person_id', 'int', 0), 'post', rex::getProperty('debug')) ;
    //Start - add firstname-field
		$field = $form->addTextField('vorname');
		//$field->setLabel($this->i18n('snippets_label_description'));
		$field->setLabel('Vorame');
		$field->getValidator()->add( 'notEmpty', 'Das Feld Name darf nicht leer sein.');
		//End - add firstname-field
		//Start - add lastname-field
		$field = $form->addTextField('name');
		//$field->setLabel($this->i18n('snippets_label_description'));
		$field->setLabel('Name');
		$field->getValidator()->add( 'notEmpty', 'Das Feld Website darf nicht leer sein.');
		//End - add lastname-field
		if ($func == 'edit') {
			$form->addParam('partner_id', $partner_id) ;
		}

		$content = $form->get() ;
		$fragment = new rex_fragment() ;
		$fragment->setVar('class', 'edit', false) ;
		$fragment->setVar('title', $formLabel, false) ;
		$fragment->setVar('body', $content, false) ;
		$content = $fragment->parse('core/page/section.php') ;

		echo $content ;
    /*
    // altes formular
    echo('<div class="rex-addon-output">') ;
    $headline = $func == 'edit' ? 'Person bearbeiten' : 'Neue Person' ;
    echo('<h2 class="rex-hl2">' . $headline . ' (ID ' . $person->getPersonID() . ' )</h2>') ;
    if(isset($_POST['submit'])) 
    {
      $person->setVorname($_POST['vorname']) ;
      $person->setName($_POST['name']) ;
      //$person->setBeschreibung($_POST['beschreibung']);
      if($person->personSave())
        echo('Speichern erfolgreich') ;
      else
        echo('ein Fehler ist aufgetreten') ;
    }
    else
    {
      echo('<div class="skh3-form">') ;
      echo('<form action="" method="post">') ;
      echo('<label for="vorname">Vorname</label><input type="text" class="txt" name="vorname" id="vorname" value="' . $person->getVorname() . '" /><br />') ;
      echo('<label for="name">Name</label><input type="text" class="txt" name="name" id="name" value="' . $person->getName() . '" /><br />') ;
      echo('<input type="submit" class="btn" name="submit" value="Speichern" />') ;
      echo('</form></div></div>') ;
    }
    */
  }
  //person löschen
  if($func == 'del')
  {
    $person_id = rex_request('person_id', 'int') ;
    $person = new skh3\personen($person_id, $lang_id) ;
    $person->personDelete();
  }
?>
