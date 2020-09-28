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
    //Spalte für Beschreibungsicon
    $tdBesch = '<img src="media/document.gif" alt="description" title="description" />' ;
    $list->addColumn('Beschr', $tdBesch, 4, array( '<th>###VALUE###</th>', '<td class="rex-icon">###VALUE###</td>' )) ;
    $list->setColumnParams('Beschr',array('func' => 'desc','person_id' => '###person_id###')) ;
    //spalte zum löschen
    $tdDelete = 'löschen' ;
    $list->addColumn('Löschen', $tdDelete, 5, array( '<th>###VALUE###</th>', '<td class="rex-icon">###VALUE###</td>' )) ;
    $list->setColumnParams('Löschen', array('func' => 'del','person_id' => '###person_id###')) ;
    //id entfernen
    $list->removeColumn('person_id') ;
    $list->addTableColumnGroup(array(25, '*', '*', 5, 5)) ;
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
    if($func == 'add') //wenn keine Person ausgewählt wurde
      $person = new personen(null, $clang) ;
    //ID für add und 
    if($func == 'edit')
      $person = new personen($person_id, $clang) ;
    //Formular anzeigen
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
  }
  //person löschen
  if($func == 'del')
  {
    $person_id = rex_request('person_id', 'int') ;
    $person = new personen($person_id, $clang) ;
    $person->personDelete();
  }
  //beschreibungen
  if($func == 'desc')
  {
		$person_id = rex_request('person_id', 'int') ;
    $query='SELECT person_id, clang, beschreibung FROM '. \rex::getTablePrefix() . 'skh3_personen_lok WHERE person_id='.$person_id ;
    $list = rex_list::factory($query) ;
    $thIcon = '<a href="'. $list->getUrl(array('func' => 'descAdd','person_id' => $person_id)) .'"><img src="media/document_plus.gif" alt="add" title="add" /></a>' ;
    $tdIcon = '<img src="media/document.gif" alt="edit" title="edit" />' ;
    $tdDescDel = 'löschen' ;
    //Beschreibung bearbeiten
    $list->addColumn($thIcon, $tdIcon, 0, array( '<th class="rex-icon">###VALUE###</th>', '<td>###VALUE###</td>' )) ;
    $list->setColumnParams($thIcon, array('func' => 'descEdit','person_id' => '###person_id###', 'clang' => '###clang###')) ;
    //Beschreibung löschen
    $list->addColumn('Löschen', $tdDescDel, 4, array( '<th>###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' )) ;
    $list->setColumnParams('Löschen', array('func' => 'descDel','person_id' => '###person_id###', 'clang' => '###clang###')) ;
    //$list->removeColumn('person_id');
    $list->setColumnLabel('clang', 'Sprache') ;
    $list->setColumnLabel('beschreibung', 'Beschreibung') ;
    $list->show() ;
  }
  //Beschreibungen bearbeiten
  if($func == 'descAdd' || $func == 'descEdit')
  {
    $person = new personen($person_id, $clang) ;
    echo('<div class="rex-addon-output">') ;
    $headline = $func == 'descEdit' ? 'Personenbeschreibung bearbeiten' : 'Neue Personenbeschreibung' ;
    echo('<h2 class="rex-hl2">' . $headline . ' (ID ' . $person->getPersonID() . ')</h2>') ;
    if(isset($_POST['desc_submit'])) 
    {
      $person->setBeschreibung($_POST['beschreibung']) ;
      if($person->personBeschreibungSave())
        echo('Speichern erfolgreich') ;
      else
        echo('ein Fehler ist aufgetreten') ;
    }
    else
    {
      echo('<div class="skh3-form">') ;
      echo('<form action="" method="post">') ;
      if($func == 'descAdd') //Wenn neuer Eintrag, Menu für Sprachen anzeigen
      {
        echo('<label for="sprache">Sprache</label><select name="clang" id="sprache">') ;
        echo('<option value="">Sprache wählen</option>') ;
        //Sprachen aus DB holen
        $sql = rex_sql::factory() ;
        $queryClang = 'SELECT id, name FROM ' . \rex::getTablePrefix() . 'clang;' ;
        $sql->setQuery($queryClang) ;
        for($i = 0; $i < $sql->getRows(); $i++)
        {
          echo '<option value="' . $sql->getValue('id') .'">' . $sql->getValue('name') .'</option>' ;
          $sql->next();
        }
        echo('</select> <br />');
      }
      echo('<label for="beschreibung">Beschreibung</label><textarea class="txt" name="beschreibung" id="beschreibung" cols="50" rows="5">' . $person->getBeschreibung() . '</textarea><br />') ;
      echo('<input type="submit" class="btn" name="desc_submit" value="Speichern" />') ;
      echo('</form></div></div>') ;
    }
  }
  //Beschreibung löschen
  if($func == 'descDel')
  {
    $person = new personen($person_id, $clang);
    $person->beschreibungDelete();
  }
?>
