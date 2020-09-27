<?php
	error_reporting('E_ALL') ;
	$func = rex_request('func', 'string') ;
	echo("Hier kommt das Partnergeraffel") ;

  if ($func == '') 
  {
    $query='SELECT partner_id, name, website FROM ' . rex::getTablePrefix() . 'skh3_partner' ;
    $list = rex_list::factory($query) ;
    //Spalte zum Hinzufügen und Editieren
    $thIcon = '<a href="' . $list->getUrl(['func' => 'add']) . '" title="' . $this->i18n('column_hashtag') . ' ' . rex_i18n::msg('add') . '"><i class="rex-icon rex-icon-add-action"></i></a>' ;
    $tdIcon = '<i class="rex-icon fa-file-text-o"></i>' ;
		$list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']) ;
    $list->setColumnParams($thIcon, array('func' => 'edit','partner_id' => '###partner_id###')) ;
    //spalte zum löschen
    $tdDelete = 'löschen' ;
    $list->addColumn('Löschen', $tdDelete, 4, array( '<th>###VALUE###</th>', '<td class="rex-icon">###VALUE###</td>' )) ;
    $list->setColumnParams('Löschen', array('func' => 'del','partner_id' => '###partner_id###')) ;
    //id entfernen
    $list->removeColumn('partner_id') ;
    $list->addTableColumnGroup(array(5, '*', '*', 5)) ;
    $list->setColumnLabel('name', 'Partner') ;
    $list->setColumnLabel('website', 'Website') ;
    //felder sortierbar machen
    $list->setColumnSortable('name') ;
    $list->show() ;
  }
  //Partner hinzufügen oder ändern
  if ($func == 'add' || $func == 'edit') 
  {
    $partner_id = rex_request('partner_id', 'int') ;
    //if($func == 'add') //wenn keine Partner ausgewählt wurde
    //  $partner = new partner(null) ;
    //ID für add und 
    //if($func == 'edit')
    //  $partner = new partner($partner_id) ;
    
    //Formular anzeigen
    $form = rex_form::factory(rex::getTable('skh3_partner'), '', 'partner_id=' . rex_request('partner_id', 'int', 0), 'post', rex::getProperty('debug')) ;
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
    // altes Formular
    echo('<div class="rex-addon-output">') ;
    $headline = $func == 'edit' ? 'Partner bearbeiten' : 'neuer Partner' ;
    echo('<h2 class="rex-hl2">'. $headline . ' (ID ' . $partner->getPartnerID() . ' )</h2>') ;
    if(isset($_POST['submit'])) 
    {
      $partner->setName($_POST['name']) ;
      $partner->setWebseite($_POST['website']) ;
      if($partner->partnerSave())
        echo('Speichern erfolgreich') ;
      else
        echo('ein Fehler ist aufgetreten') ;
    }
    else
    {
      echo('<div class="skh3-form">') ;
      echo('<form action="" method="post">') ;
      echo('<label for="name">Name</label><input type="text" class="txt" name="name" id="name" value="' . $partner->getName() . '" /><br />') ;
      echo('<label for="website">Website</label><input type="text" class="txt" name="webseite" id="webseite" value="'. $partner->getWebseite() . '"<br />') ;
      echo('<input type="submit" class="btn" name="submit" value="Speichern" />') ;
      echo('</form></div></div>') ;
    }
    */
  }
  //Partner löschen
  if($func == 'del')
  {
		$partner_id = rex_request('partner_id', 'int') ;
    $partner = new partner($partner_id) ;
    $partner->partnerDelete() ;
  }
?>
