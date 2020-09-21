<?php
  error_reporting('E_ALL');
  /*
  //sprach-Umschalter
  require $REX['INCLUDE_PATH'].'/functions/function_rex_languages.inc.php';
  //Seminar-Klasse
  require_once ($REX['INCLUDE_PATH']. '/addons/'. rex_request('page', 'string','') .'/classes/class.seminar.inc.php');
  $seminar_id = rex_request('seminar_id', 'int');
  
  if ($func == '') {
                $orderBy = rex_request("sort", "string", "");
                $query='SELECT '.$REX['TABLE_PREFIX'].'skh3_seminare.seminar_id, titel, seminar_start, seminar_ende, seminar_ort 
                        FROM '.$REX['TABLE_PREFIX'].'skh3_seminare 
                INNER JOIN '.$REX['TABLE_PREFIX'].'skh3_seminare_lok 
                ON '.$REX['TABLE_PREFIX'].'skh3_seminare.seminar_id = '.$REX['TABLE_PREFIX'].'skh3_seminare_lok.seminar_id 
                WHERE '.$REX['TABLE_PREFIX'].'skh3_seminare_lok.clang='.$REX['CUR_CLANG'].' AND seminar_start < date(now())
     '; 
        //echo $query;
    $list = rex_list::factory($query);
                
                if ($orderBy == "") {
                        header('Location: ' . str_replace('&amp;', '&', $list->getUrl(array('sort' => 'seminar_start', 'sorttype' => 'desc'))));
                }
                
                $tdDelete = '<span class="del">del</span>';
                $tdCopy = 'copy';
                //Spalten hinzufügen
                $list->addColumn('Del', $tdDelete, 5, array( '<th>###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' ));
                $list->setColumnParams('Del', array('func' => 'del','seminar_id' => '###seminar_id###'));
                $list->addColumn('Kopie', $tdCopy, 6, array( '<th>###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' ));
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
                $seminar = new seminar($seminar_id);
                $seminar->seminarDelete();
        }
        
        //Seminar kopieren
        if($func == 'duplicate')
        {
                $origSeminar = new seminar($seminar_id); //originales Seminar
                $dupSeminar = new seminar(); //Kopie
                //Stammdaten
                $dupSeminar->setSeminarStart('2022-01-01');
                $dupSeminar->setSeminarEnde('2022-01-01');
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
  */
?>
