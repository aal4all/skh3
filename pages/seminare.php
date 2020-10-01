<?php
  error_reporting('E_ALL');
  $func = rex_request('func', 'string') ;
  $seminar_id = rex_request('seminar_id', 'int') ;
  echo("hier kommt das Seminargeraffel") ;
  

  //Seminare auflisten (aktuelle und zukünftige)
  if ($func == '') 
  {
    $query='SELECT ' . rex::getTablePrefix() . 'skh3_seminare.seminar_id, titel, seminar_start, seminar_ende, seminar_ort, seminar_online 
      FROM ' . rex::getTablePrefix() . 'skh3_seminare 
      INNER JOIN ' .  rex::getTablePrefix() . 'skh3_seminare_lok 
      ON ' . rex::getTablePrefix() . 'skh3_seminare.seminar_id = ' . rex::getTablePrefix() . 'skh3_seminare_lok.seminar_id 
      WHERE ' . rex::getTablePrefix() . 'skh3_seminare_lok.clang=' . rex_clang::getCurrentId() . ' AND seminar_start >= date(now())' ;
      //ORDER BY seminar_start'; //WHERE-Bedingung Seminarstart: seminar_start >= date(now()) AND
    $orderBy = rex_request("sort", "string", "") ;
    $list = rex_list::factory($query) ;
    //$list = new rex_list($query);
    if ($orderBy == "") 
    {
      header('Location: ' . str_replace('&amp;', '&', $list->getUrl(array('sort' => 'seminar_start', 'sorttype' => 'asc')))) ;
    }

    //Hinzufügen und Ändern
    $thAEIcon = '<a href="' . $list->getUrl(['func' => 'add']) . '" title="' . $this->i18n('column_hashtag') . ' ' . rex_i18n::msg('add') . '"><i class="rex-icon rex-icon-add-action"></i></a>' ;
    $tdAEIcon = '<i class="rex-icon fa-file-text-o"></i>' ;
    // »hinzufügen«
    $list->addColumn($thAEIcon, $tdAEIcon, 0, array( '<th class="rex-icon">###VALUE###</th>', '<td class="rex-icon">###VALUE###</td>' )) ;
    $list->setColumnParams($thAEIcon, array('func' => 'edit','seminar_id' => '###seminar_id###')) ;
    // »löschen«
    $tdDelete = '<i class="rex-icon rex-icon-package-delete"></i>' ;
    $list->addColumn('Del', $tdDelete, 6); // array( '<th>###VALUE###</th>', '<td class="rex-icon">###VALUE###</td>' )) ;
    $list->setColumnParams('Del', array('func' => 'del','seminar_id' => '###seminar_id###')) ;
    // »copy«
    $tdCopy = 'copy' ;
    $list->addColumn('Kopie', $tdCopy, 7, array( '<th>###VALUE###</th>', '<td class="rex-icon">###VALUE###</td>' )) ;
    $list->setColumnParams('Kopie', array('func' => 'duplicate','seminar_id' => '###seminar_id###')) ;
    //id entfernen
    $list->removeColumn('seminar_id') ;
    $list->addTableColumnGroup(array(30, '*', 70, 70, 90, 60, 60, 60)) ;
    $list->setColumnLabel('titel', 'Seminar') ; 
    $list->setColumnLabel('seminar_start', 'Beginn') ;
    $list->setColumnLabel('seminar_ende', 'Ende') ;
    $list->setColumnLabel('seminar_ort', 'Ort') ;
    $list->setColumnLabel('seminar_online', 'Status') ;
    //felder sortierbar machen
    $list->setColumnSortable('titel') ;
    $list->setColumnSortable('seminar_start') ;
    $list->setColumnSortable('seminar_ort') ;
    $list->setColumnSortable('seminar_online') ;
    //seminar bearbeiten
    $list->setColumnParams('titel', array('func' => 'edit', 'seminar_id' => '###seminar_id###')) ;
    //Status ändern
    $list->setColumnParams('seminar_online', array('func' => 'status', 'seminar_id' => '###seminar_id###')) ;
    $list->show();
  }

  //Seminar ändern oder hinzufügen
  if ($func == 'add' || $func == 'edit') 
  { 
		$seminar = rex_request('seminar_id', 'int') ;
		if($func == 'add') //wenn kein Seminar ausgewählt wurde
      $seminar = new skh3\seminar(null) ;
    if($func == 'edit')
      $seminar = new skh3\seminar($seminar_id) ;
    //Formular anzeigen
    // altes Form
    echo('<div class="rex-addon-output">') ;
    $headline = $func == 'edit' ? 'Seminar bearbeiten' : 'neues Seminar' ;
    echo(' <h2 class="rex-hl2">' . $headline . '(ID ' . $seminar->getSeminarID() . ' )</h2>') ;
    if(isset($_POST['submit'])) 
    {
      echo('Submit gedrückt');
      $seminar->setSeminarStart($_POST['seminar_start']);
      $seminar->setSeminarEnde($_POST['seminar_ende']);
      $seminar->setSeminarOrt($_POST['seminar_ort']);
      $seminar->setSeminarTyp($_POST['seminar_typ']);
      $seminar->setAllRefis($_POST['refis']);
      $seminar->setAllLeitung($_POST['leitung']);
      $seminar->setAllVerantwortung($_POST['verantwortung']);
      $seminar->setAllPartner($_POST['partner']);
      $seminar->setAllGeldgeber($_POST['geldgeber']);
      foreach($_POST['clang'] as $key => $value)
      {
        $seminar->setClang($key, $value);
      }
      foreach($_POST['titel'] as $key => $value)
      {
        $seminar->setTitel($key, $value);
      }
      foreach($_POST['untertitel'] as $key => $value)
      {
        $seminar->setUntertitel($key, $value);
      }
      foreach($_POST['beschreibung'] as $key => $value)
      {
        $seminar->setBeschreibung($key, $value);
      }
      foreach($_POST['kosten'] as $key => $value)
      {
        $seminar->setKosten($key, $value);
      }
      foreach($_POST['waehrung'] as $key => $value)
      {
        $seminar->setWaehrung($key, $value);
      }
      foreach($_POST['zielgruppe'] as $key => $value)
      {
        $seminar->setZielgruppe($key, $value);
      }
                
      //Alle Werte ausgeben und dann Speicherfunktion kritzeln
      if($seminar->seminarSave())
        echo('Speichern erfolgreich');
      else
        echo('<font color="red">ein Fehler ist aufgetreten</font>');
    }
    else
    { 
      //fuer Datenbankabfragen
      $sql = rex_sql::factory();
      echo('<div class="skh3-form">') ;
      echo('<form action="" method="post" name="seminarform">') ;
      echo('<h3>Stammdaten</h3>') ;
      echo('<label for="seminar_start">Beginn (jjjj-mm-tt):</label><input type="text" class="datepicker txt" name="seminar_start" id="seminar_start" value="' . $seminar->getSeminarStart() .'" /><br /><br />') ;
      echo('<label for="seminar_ende">Ende (jjjj-mm-tt):</label><input type="text" class="datepicker txt" name="seminar_ende" id="seminar_ende" value="' . $seminar->getSeminarEnde() . '" /><br /><br />') ;
      echo('<label for="seminar_ort">Ort:</label><input type="text" class="txt" name="seminar_ort" id="seminar_ort" value="' . $seminar->getSeminarOrt() . '" /><br /><br />') ;
      echo('<label for="seminar_typ">Seminartyp:</label><select name="seminar_typ" id="seminar_typ">') ;
      echo('<option value="">Seminartyp wählen</option>') ;
      $querySemTyp = 'SELECT typ_id, bezeichnung FROM ' .  rex::getTablePrefix() . 'skh3_seminartyp_lok WHERE lang_id=0' ;
      $sql->setQuery($querySemTyp) ;
      for($i = 0; $i < $sql->getRows(); $i++)
      {
        if($sql->getValue('typ_id')  == $seminar->getSeminarTyp())
          $selected = ' selected="selected"' ;
        else
          $selected = '' ;
        echo('<option value="' . $sql->getValue('typ_id') .'" '.$selected.'>' . $sql->getValue('bezeichnung') .'</option>') ;
        $sql->next();
      }
      echo('</select> <br />') ;
      echo('<h3>Personal und Partner</h3>') ;
      echo('<label for="refis">ReferentInnen:</label><select name="refis[]" id="refis" multiple="multiple" size="8">') ;
      $queryRefis = 'SELECT person_id,vorname,name FROM ' .  rex::getTablePrefix() . 'skh3_personen ORDER BY name,vorname;' ;
      $sql->setQuery($queryRefis) ;
      for($i = 0; $i < $sql->getRows(); $i++)
      {
        if(in_array($sql->getValue('person_id'), $seminar->getAllRefis()))
          $selected = ' selected="selected"';
        else
          $selected = '' ;
        echo('<option value="' . $sql->getValue('person_id') .'" '.$selected.'>'.$sql->getValue('name').', '.$sql->getValue('vorname').'</option>') ;
        $sql->next() ;
      }
      echo('</select> <br />') ;
      echo('<label for="leitung">Leitung:</label><select name="leitung[]" id="leitung" multiple="multiple" size="8">') ;
      $queryLeitung = 'SELECT person_id,vorname,name FROM ' .  rex::getTablePrefix() . 'skh3_personen ORDER BY name,vorname;' ;
      $sql->setQuery($queryLeitung) ;
      for($i = 0; $i < $sql->getRows(); $i++)
      {
        if(in_array($sql->getValue('person_id'), $seminar->getAllLeitung()))
          $selected = ' selected="selected"' ;
        else
          $selected = '' ;
        echo('<option value="' . $sql->getValue('person_id') .'" '.$selected.'>'.$sql->getValue('name').', '.$sql->getValue('vorname').'</option>') ;
        $sql->next();
      }
      echo('</select> <br />') ;
      echo('<label for="verantwortung">Verantwortung:</label><select name="verantwortung[]" id="verantwortung" multiple="multiple" size="8">') ;
      $queryVerantw = 'SELECT person_id,vorname,name FROM ' .  rex::getTablePrefix() . 'skh3_personen ORDER BY name,vorname;' ;
      $sql->setQuery($queryVerantw) ;
      for($i = 0; $i < $sql->getRows(); $i++)
      {
        if(in_array($sql->getValue('person_id'), $seminar->getAllVerantwortung()))
          $selected = ' selected="selected"' ;
        else
          $selected = '' ;
        echo('<option value="' . $sql->getValue('person_id') .'" '.$selected.'>'.$sql->getValue('name').', '.$sql->getValue('vorname').'</option>') ;
        $sql->next() ;
      }
      echo('</select> <br />') ;
      echo('<label for="partner">Partner:</label><select name="partner[]" id="partner" multiple="multiple" size="3">') ;
      $queryPartner = 'SELECT partner_id,name FROM ' .  rex::getTablePrefix() . 'skh3_partner ORDER BY name;' ;
      $sql->setQuery($queryPartner) ;
      for($i = 0; $i < $sql->getRows(); $i++)
      {
        if(in_array($sql->getValue('partner_id'), $seminar->getAllPartner()))
          $selected = ' selected="selected"' ;
        else
          $selected = '' ;
        echo('<option value="' . $sql->getValue('partner_id') .'" ' . $selected . '>' . $sql->getValue('name') . '</option>') ;
        $sql->next();
      }
      echo('</select> <br />') ;
      echo('<label for="geldgeber">Cashcows:</label><select name="geldgeber[]" id="geldgeber" multiple="multiple" size="3">') ;
      $queryGeldgeber = 'SELECT geldgeber_id,name FROM ' .  rex::getTablePrefix() . 'skh3_geldgeber ORDER BY name;' ;
      $sql->setQuery($queryGeldgeber) ;
      for($i = 0; $i < $sql->getRows(); $i++)
      {
        if(in_array($sql->getValue('geldgeber_id'), $seminar->getAllGeldgeber()))
          $selected = ' selected="selected"' ;
        else
          $selected = '' ;
        echo('<option value="' . $sql->getValue('geldgeber_id') .'" '.$selected.'>'.$sql->getValue('name').'</option>') ;
        $sql->next();
      }
      echo('</select> <br />') ;
      echo('<h3>Lokalisierungen</h3>') ;
      //Währungen in Array lesen
      $queryWaehrung = 'SELECT waehrung_id,bezeichnung,kurzform FROM ' .  rex::getTablePrefix() . 'skh3_waehrung ORDER BY waehrung_id;' ;
      $waehrungen = $sql->getArray($queryWaehrung) ;
      //Sprachen aus DB holen                                 
      $queryClang = 'SELECT id, name FROM ' .  rex::getTablePrefix() . 'clang;' ;
      $sql->setQuery($queryClang);
      for($i = 0; $i < $sql->getRows(); $i++)
      {
        $lang = $sql->getValue('name') ;
        $lang_id = $sql->getValue('id') ;
        echo('<h3>' . $lang .'</h3>') ;
        echo('<input type="hidden" name="clang[]" id="lang"  value="' . $lang_id . '"><br /><br />') ;
        echo('<label for="titel">Titel ('.$lang.'):</label><input type="text" class="txt" name="titel[]" id="titel" value="'. $seminar->getTitel($lang_id).'" /><br /><br />') ;
        echo('<label for="untertitel">Untertitel ('.$lang.'):</label><input type="text" class="txt" name="untertitel[]" id="untertitel" value="'. $seminar->getUntertitel($lang_id).'" /><br /><br />') ;
        echo('<label for="beschreibung">Beschreibung ('.$lang.'):</label><textarea class="txt" name="beschreibung[]" id="beschreibung"  cols="50" rows="15">'.$seminar->getBeschreibung($lang_id).'</textarea><br /><br />') ;
        echo('<label for="kosten">Teilnahmebeitrag ('.$lang.'):</label><input type="text" class="txt" name="kosten[]" id="kosten" value="'. $seminar->getKosten($lang_id).'" /><br /><br /><br />') ;
        //Währungen holen und in SELECT-Feld packen
        echo('<label for="waehrung">Waehrung:</label><select name="waehrung[]" id="waehrung" >') ;
        for($j = 0; $j < count($waehrungen); $j++)
        {
          if($waehrungen[$j][waehrung_id] == $seminar->getWaehrung($lang_id))
            $selected = ' selected="selected"';
          else
            $selected = '';
          echo('<option value="' . $waehrungen[$j][waehrung_id] .'" '.$selected.'>'.$waehrungen[$j][bezeichnung].' ('.$waehrungen[$j][kurzform].')</option>') ;
        }
        echo('</select> <br /><br />') ;
        //Ende währungen
        echo('<label for="zielgruppe">Zielgruppe ('.$lang.'):</label><input type="text" class="txt" name="zielgruppe[]" id="zielgruppe" value="'. $seminar->getZielgruppe($lang_id).'" /><br /><br />') ;
        $sql->next();
      }
      echo('<input type="submit" class="btn" name="submit" value="Speichern" />') ;
      echo('</form></div>' );
      echo('<script type="text/javascript">$( function() {$( ".datepicker" ).datepicker({clickInput:true, inline:true, dateFormat: "yy-mm-dd", autoSize: true });});</script></div>') ;
    }
  }

  //Seminar löschen
  if($func == 'del')
  {
    $seminar = new skh3\seminar($seminar_id) ;
    $seminar->seminarDelete() ;
  }       

  //Status ändern
  if($func == 'status')
  {
    $seminar = new skh3\seminar($seminar_id) ;
    $seminar->seminarChangeStatus() ;
  }
        
  //Seminar kopieren
  if($func == 'duplicate')
  {
    $origSeminar = new skh3\seminar($seminar_id) ; //originales Seminar
    $dupSeminar = new skh3\seminar() ; //Kopie
    //Stammdaten
    $dupSeminar->setSeminarStart('2028-01-01') ;
    $dupSeminar->setSeminarEnde('2028-01-01') ;
    $dupSeminar->setSeminarOrt($origSeminar->getSeminarOrt()) ;
    $dupSeminar->setSeminarTyp($origSeminar->getSeminarTyp()) ;
    //lok
    $dupSeminar->setAllTitel($origSeminar->getAllTitel()) ;
    $dupSeminar->setAllUntertitel($origSeminar->getAllUntertitel()) ;
    $dupSeminar->setAllBeschreibung($origSeminar->getAllBeschreibung()) ;
    $dupSeminar->setAllKosten($origSeminar->getAllKosten()) ;
    $dupSeminar->setAllWaehrung($origSeminar->getAllWaehrung()) ;
    $dupSeminar->setAllZielgruppe($origSeminar->getAllZielgruppe()) ;
    //pers, Partner, Cashcows
    $dupSeminar->setAllRefis($origSeminar->getAllRefis()) ;
    $dupSeminar->setAllLeitung($origSeminar->getAllLeitung()) ;
    $dupSeminar->setAllVerantwortung($origSeminar->getAllVerantwortung()) ;
    $dupSeminar->setAllPartner($origSeminar->getAllPartner()) ;
    $dupSeminar->setAllGeldgeber($origSeminar->getAllGeldgeber()) ;
    //Speichern
    if($dupSeminar->seminarSave())
      echo('Kopieren erfolgreich') ;
    else
      echo('<font color="red">ein Fehler ist aufgetreten</font>') ;
  }
?>
