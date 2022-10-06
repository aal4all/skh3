<?php
  error_reporting(E_ALL);
  echo("hier kommt das alte Seminargeraffel") ;
  $seminar_id = rex_request('seminar_id', 'int');
  $func = rex_request('func', 'string');
  
  if ($func == '') {
    $orderBy = rex_request("sort", "string", "");
    $query='SELECT ' . rex::getTablePrefix() . 'skh3_seminare.seminar_id, titel, seminar_start, seminar_ende, seminar_ort 
      FROM ' . rex::getTablePrefix() . 'skh3_seminare 
      INNER JOIN ' . rex::getTablePrefix() . 'skh3_seminare_lok 
      ON ' . rex::getTablePrefix() . 'skh3_seminare.seminar_id = ' . rex::getTablePrefix() . 'skh3_seminare_lok.seminar_id WHERE ' . rex::getTablePrefix() . 'skh3_seminare_lok.clang=' . rex_clang::getCurrentId() . ' AND seminar_start < date(now()) '; 
    //echo $query;
    $list = rex_list::factory($query);
    if ($orderBy == "") {
      header('Location: ' . str_replace('&amp;', '&', $list->getUrl(array('sort' => 'seminar_start', 'sorttype' => 'desc'))));
    }
    //Spalten hinzufügen
    $tdDelete = '<i class="rex-icon rex-icon-package-delete"></i>';
    $list->addColumn('Del', $tdDelete, 5) ;
    $list->setColumnParams('Del', array('func' => 'del','seminar_id' => '###seminar_id###'));
    $tdCopy = 'copy';
    $list->addColumn('Kopie', $tdCopy, 6); //, array( '<th>###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' ));
    $list->setColumnParams('Kopie', array('func' => 'duplicate','seminar_id' => '###seminar_id###'));
    $list->addTableColumnGroup(array(20, '*', 70, 70, 90, 40,40));
    $list->setColumnLabel('seminar_id', 'id'); 
    $list->setColumnLabel('titel', 'Seminar'); 
    $list->setColumnLabel('seminar_start', 'Beginn');
    $list->setColumnLabel('seminar_ende', 'Ende');
    $list->setColumnLabel('seminar_ort', 'Ort');
    
    //felder sortierbar machen
    $list->setColumnSortable('titel');
    $list->setColumnSortable('seminar_start');
    $list->show();
  }
  
  //Seminar löschen
  if($func == 'del')
  {
    $seminar = new skh3\seminar($seminar_id);
    $seminar->seminarDelete();
  }
        
  //Seminar kopieren
  if($func == 'duplicate')
  {
    $origSeminar = new skh3\seminar($seminar_id); //originales Seminar
    $dupSeminar = new skh3\seminar(); //Kopie
    //Stammdaten
    $dupSeminar->setSeminarStart('2028-01-01');
    $dupSeminar->setSeminarEnde('2028-01-01');
    $dupSeminar->setSeminarOrt($origSeminar->getSeminarOrt());
    $dupSeminar->setSeminarTyp($origSeminar->getSeminarTyp());
    //lok
    $dupSeminar->setAllTitel($origSeminar->getAllTitel());
    $dupSeminar->setAllUntertitel($origSeminar->getAllUntertitel());
    $dupSeminar->setAllBeschreibung($origSeminar->getAllBeschreibung());
    $dupSeminar->setAllKosten($origSeminar->getAllKosten());
    $dupSeminar->setAllWaehrung($origSeminar->getAllWaehrung());
    $dupSeminar->setAllZielgruppe($origSeminar->getAllZielgruppe());
    //pers, Partner, Cashcows
    $dupSeminar->setAllRefis($origSeminar->getAllRefis());
    $dupSeminar->setAllLeitung($origSeminar->getAllLeitung());
    $dupSeminar->setAllVerantwortung($origSeminar->getAllVerantwortung());
    $dupSeminar->setAllPartner($origSeminar->getAllPartner());
    $dupSeminar->setAllGeldgeber($origSeminar->getAllGeldgeber());
                
    //Speichern
    if($dupSeminar->seminarSave())
      echo('Kopieren erfolgreich');
    else
      echo('<font color="red">ein Fehler ist aufgetreten</font>');
  }
?>
