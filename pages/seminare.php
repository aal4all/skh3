<?php
  error_reporting('E_ALL');
  //sprach-Umschalter
  require $REX['INCLUDE_PATH'].'/functions/function_rex_languages.inc.php';
  //Seminar-Klasse
  require_once ($REX['INCLUDE_PATH']. '/addons/'. rex_request('page', 'string','') .'/classes/class.seminar.inc.php');
  $seminar_id = rex_request('seminar_id', 'int');
  
  //Seminare auflisten (aktuelle und zukünftige)
  if ($func == '') 
  {
                $query='SELECT '.$REX['TABLE_PREFIX'].'skh3_seminare.seminar_id, titel, seminar_start, seminar_ende, seminar_ort, seminar_online 
                        FROM '.$REX['TABLE_PREFIX'].'skh3_seminare 
                INNER JOIN '.$REX['TABLE_PREFIX'].'skh3_seminare_lok 
                ON '.$REX['TABLE_PREFIX'].'skh3_seminare.seminar_id = '.$REX['TABLE_PREFIX'].'skh3_seminare_lok.seminar_id 
                WHERE '.$REX['TABLE_PREFIX'].'skh3_seminare_lok.clang='.$REX['CUR_CLANG'].' AND seminar_start >= date(now())';
                //ORDER BY seminar_start'; //WHERE-Bedingung Seminarstart: seminar_start >= date(now()) AND
    
    $orderBy = rex_request("sort", "string", "");
                
                //$list = rex_list::factory($query);
                $list = new rex_list($query);
    
    if ($orderBy == "") {
                        header('Location: ' . str_replace('&amp;', '&', $list->getUrl(array('sort' => 'seminar_start', 'sorttype' => 'asc'))));
                }


    //Hinzufügen und Ändern
                $thAEIcon = '<a href="'. $list->getUrl(array('func' => 'add')) .'"><img src="media/document_plus.gif" alt="add" title="add" /></a>';
                //bis Redaxo 4.4: $tdAEIcon = '<a href="'. $list->getUrl(array('func' => 'edit','seminar_id' => '###id###')).'"><img src="media/document.gif" alt="edit" title="edit" /></a>';
                //Löschen
    //bis Redaxo 4.4: $tdDelete = '<a href="'. $list->getUrl(array('func' => 'del','seminar_id' => '###seminar_id###')).'" class="del">del</a>';
    //bis Redaxo 4.4: $tdCopy = '<a href="'. $list->getUrl(array('func' => 'duplicate','seminar_id' => '###seminar_id###')).'">copy</a>';
                $tdAEIcon = '<img src="media/document.gif" alt="edit" title="edit" />';
                $tdDelete = '<span class="del">del</span>';
                $tdCopy = 'copy';
                //Spalten hinzufügen
                $list->addColumn($thAEIcon, $tdAEIcon, 0, array( '<th class="rex-icon">###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' )); 
                $list->setColumnParams($thAEIcon, array('func' => 'edit','seminar_id' => '###seminar_id###'));
                $list->addColumn('Del', $tdDelete, 6, array( '<th>###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' ));
                $list->setColumnParams('Del', array('func' => 'del','seminar_id' => '###seminar_id###'));
                $list->addColumn('Kopie', $tdCopy, 7, array( '<th>###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' ));
                $list->setColumnParams('Kopie', array('func' => 'duplicate','seminar_id' => '###seminar_id###'));
    
    //id entfernen
                $list->removeColumn('seminar_id');
        
                $list->addTableColumnGroup(array(20, '*', 70, 70, 90, 40,40));
                $list->setColumnLabel('titel', 'Seminar'); 
                $list->setColumnLabel('seminar_start', 'Beginn');
                $list->setColumnLabel('seminar_ende', 'Ende');
                $list->setColumnLabel('seminar_ort', 'Ort');
                $list->setColumnLabel('seminar_online', 'Status');
    
                //felder sortierbar machen
                $list->setColumnSortable('titel');
                $list->setColumnSortable('seminar_start');
                $list->setColumnSortable('seminar_ort');
                $list->setColumnSortable('seminar_online');
                //seminar bearbeiten
                $list->setColumnParams('titel', array('func' => 'edit', 'seminar_id' => '###seminar_id###'));
                //Status ändern
                $list->setColumnParams('seminar_online', array('func' => 'status', 'seminar_id' => '###seminar_id###'));
                $list->show();
  }
   //Seminar ändern oder hinzufügen
  if ($func == 'add' || $func == 'edit') 
  {             
                if($func == 'add') //wenn kein Seminar ausgewählt wurde
                        $seminar = new seminar(null);
                if($func == 'edit')
                        $seminar = new seminar($seminar_id);
                //Formular anzeigen
?>
                <div class="rex-addon-output">
        <?php $headline = $func == 'edit' ? 'Seminar bearbeiten' : 'neues Seminar'; ?>
                        <h2 class="rex-hl2"><?php echo $headline; echo ('(ID ' . $seminar->getSeminarID() . ' )'); ?></h2>
<?php
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
 ?>
                        <div class="skh3-form">
                                <form action="" method="post">
                                        <h3>Stammdaten</h3>
                                        <label for="seminar_start">Beginn (jjjj-mm-tt):</label><input type="text" class="datepicker txt" name="seminar_start" id="seminar_start" value="<?php echo $seminar->getSeminarStart(); ?>" /><br />
                                        <label for="seminar_ende">Ende (jjjj-mm-tt):</label><input type="text" class="datepicker txt" name="seminar_ende" id="seminar_ende" value="<?php echo $seminar->getSeminarEnde(); ?>" /><br />
                                        <label for="seminar_ort">Ort:</label><input type="text" class="txt" name="seminar_ort" id="seminar_ort" value="<?php echo $seminar->getSeminarOrt(); ?>" /><br />
                                        <label for="seminar_typ">Seminartyp:</label><select name="seminar_typ" id="seminar_typ">
                                          <option value="">Seminartyp wählen</option>
                                        <?php
                                                //Typen aus DB holen
                                                $querySemTyp = 'SELECT typ_id, bezeichnung FROM '.$REX['TABLE_PREFIX'].'skh3_seminartyp_lok WHERE clang=0;' ;
                                                $sql->setQuery($querySemTyp);
                                                for($i = 0; $i < $sql->getRows(); $i++)
                                                {
                                                        if($sql->getValue('typ_id')  == $seminar->getSeminarTyp())
                                                                $selected = ' selected="selected"';
                                                        else
                                                                $selected = '';
                                                        echo '<option value="' . $sql->getValue('typ_id') .'" '.$selected.'>' . $sql->getValue('bezeichnung') .'</option>';
                                                        $sql->next();
                                                }
                                                $sql->freeResult(); //Speicher für DB-Result freigeben
                                        ?>
                                        </select> <br />
                                        <h3>Personal und Partner</h3>
                                        <label for="refis">ReferentInnen:</label><select name="refis[]" id="refis" multiple="multiple" size="8">
                                        <?php
                                                $queryRefis = 'SELECT person_id,vorname,name FROM '.$REX['TABLE_PREFIX'].'skh3_personen ORDER BY name,vorname;' ;
                                                $sql->setQuery($queryRefis);
                                                for($i = 0; $i < $sql->getRows(); $i++)
                                                {
                                                        if(in_array($sql->getValue('person_id'), $seminar->getAllRefis()))
                                                                $selected = ' selected="selected"';
                                                        else
                                                                $selected = '';
                                                        echo '<option value="' . $sql->getValue('person_id') .'" '.$selected.'>'.$sql->getValue('name').', '.$sql->getValue('vorname').'</option>';
                                                        $sql->next();
                                                }
                                                $sql->freeResult(); //Speicher für DB-Result freigeben
                                        ?>
                                        </select> <br />
                                        
                                        <label for="leitung">Leitung:</label><select name="leitung[]" id="leitung" multiple="multiple" size="8">
                                        <?php
                                                $queryLeitung = 'SELECT person_id,vorname,name FROM '.$REX['TABLE_PREFIX'].'skh3_personen ORDER BY name,vorname;' ;
                                                $sql->setQuery($queryLeitung);
                                                for($i = 0; $i < $sql->getRows(); $i++)
                                                {
                                                        if(in_array($sql->getValue('person_id'), $seminar->getAllLeitung()))
                                                                $selected = ' selected="selected"';
                                                        else
                                                                $selected = '';
                                                        echo '<option value="' . $sql->getValue('person_id') .'" '.$selected.'>'.$sql->getValue('name').', '.$sql->getValue('vorname').'</option>';
                                                        $sql->next();
                                                }
                                                $sql->freeResult(); //Speicher für DB-Result freigeben
                                        ?>
                                        </select> <br />
                                        
                                        <label for="verantwortung">Verantwortung:</label><select name="verantwortung[]" id="verantwortung" multiple="multiple" size="8">
                                        <?php
                                                $queryVerantw = 'SELECT person_id,vorname,name FROM '.$REX['TABLE_PREFIX'].'skh3_personen ORDER BY name,vorname;' ;
                                                $sql->setQuery($queryVerantw);
                                                for($i = 0; $i < $sql->getRows(); $i++)
                                                {
                                                        if(in_array($sql->getValue('person_id'), $seminar->getAllVerantwortung()))
                                                                $selected = ' selected="selected"';
                                                        else
                                                                $selected = '';
                                                        echo '<option value="' . $sql->getValue('person_id') .'" '.$selected.'>'.$sql->getValue('name').', '.$sql->getValue('vorname').'</option>';
                                                        $sql->next();
                                                }
                                                $sql->freeResult(); //Speicher für DB-Result freigeben
                                        ?>

</select> <br />
                                        
                                        <label for="partner">Partner:</label><select name="partner[]" id="partner" multiple="multiple" size="3">
                                        <?php
                                                $queryPartner = 'SELECT partner_id,name FROM '.$REX['TABLE_PREFIX'].'skh3_partner ORDER BY name;' ;
                                                $sql->setQuery($queryPartner);
                                                for($i = 0; $i < $sql->getRows(); $i++)
                                                {
                                                        if(in_array($sql->getValue('partner_id'), $seminar->getAllPartner()))
                                                                $selected = ' selected="selected"';
                                                        else
                                                                $selected = '';
                                                        echo '<option value="' . $sql->getValue('partner_id') .'" '.$selected.'>'.$sql->getValue('name').'</option>';
                                                        $sql->next();
                                                }
                                                $sql->freeResult(); //Speicher für DB-Result freigeben
                                        ?>
                                        </select> <br />
                                        
                                        <label for="geldgeber">Cashcows:</label><select name="geldgeber[]" id="geldgeber" multiple="multiple" size="3">
                                        <?php
                                                $queryGeldgeber = 'SELECT geldgeber_id,name FROM '.$REX['TABLE_PREFIX'].'skh3_geldgeber ORDER BY name;' ;
                                                $sql->setQuery($queryGeldgeber);
                                                for($i = 0; $i < $sql->getRows(); $i++)
                                                {
                                                        if(in_array($sql->getValue('geldgeber_id'), $seminar->getAllGeldgeber()))
                                                                $selected = ' selected="selected"';
                                                        else
                                                                $selected = '';
                                                        echo '<option value="' . $sql->getValue('geldgeber_id') .'" '.$selected.'>'.$sql->getValue('name').'</option>';
                                                        $sql->next();
                                                }
                                                $sql->freeResult(); //Speicher für DB-Result freigeben
                                        ?>
                                        </select> <br />
                                        
                                        <h3>Lokalisierungen</h3>
                                        <?php
                                                //Währungen in Array lesen
                                                $queryWaehrung = 'SELECT waehrung_id,bezeichnung,kurzform FROM '.$REX['TABLE_PREFIX'].'skh3_waehrung ORDER BY waehrung_id;' ;
                                                $waehrungen = $sql->getArray($queryWaehrung);
                                                $sql->freeResult(); 
                                                //Sprachen aus DB holen                                 
                                                $queryClang = 'SELECT id, name FROM ' . $REX['TABLE_PREFIX'] . 'clang;' ;
                                                $sql->setQuery($queryClang);
                                                for($i = 0; $i < $sql->getRows(); $i++)
                                                {
                                                        $lang = $sql->getValue('name');
                                                        echo '<h3>' . $lang .'</h3>';
                                                        echo '<label for="titel">Titel ('.$lang.'):</label><input type="text" class="txt" name="titel[]" id="titel" value="'. $seminar->getTitel($i).'" /><br />';
                                                        echo '<label for="untertitel">Untertitel ('.$lang.'):</label><input type="text" class="txt" name="untertitel[]" id="untertitel" value="'. $seminar->getUntertitel($i).'" /><br />';
                                                        echo '<label for="beschreibung">Beschreibung ('.$lang.'):</label><textarea class="txt" name="beschreibung[]" id="beschreibung"  cols="50" rows="15">'.$seminar->getBeschreibung($i).'</textarea><br />';
                                                        echo '<label for="kosten">Teilnahmebeitrag ('.$lang.'):</label><input type="text" class="txt" name="kosten[]" id="kosten" value="'. $seminar->getKosten($i).'" /><br />';
                                                        //Währungen holen und in SELECT-Feld packen
                                                        echo '<label for="waehrung">Waehrung:</label><select name="waehrung[]" id="waehrung" >';
                                                        for($j = 0; $j < count($waehrungen); $j++)
                                                        {
                                                                if($waehrungen[$j][waehrung_id] == $seminar->getWaehrung($i))
                                                                        $selected = ' selected="selected"';
                                                                else
                                                                        $selected = '';
                                                                echo '<option value="' . $waehrungen[$j][waehrung_id] .'" '.$selected.'>'.$waehrungen[$j][bezeichnung].' ('.$waehrungen[$j][kurzform].')</option>';
                                                        }
                                                        echo '</select> <br />';
                                                        //Ende währungen
                                                        echo '<label for="zielgruppe">Zielgruppe ('.$lang.'):</label><input type="text" class="txt" name="zielgruppe[]" id="zielgruppe" value="'. $seminar->getZielgruppe($i).'" /><br />';
                                                        $sql->next();
                                                }
                                        ?>
                                        <input type="submit" class="btn" name="submit" value="Speichern" />
                                </form>
                        </div>
                        <script type="text/javascript"> 
                                jQuery(document).ready(function()
                                { 
                                        jQuery(".datepicker").datepicker(
                                        { 
                                                clickInput:true, 
                                                inline:true, 
                                                dateFormat: "yy-mm-dd", 
                                                autoSize: true 
                                        }); 
                                }); 
                        </script>
                </div>
<?php
                }
        }

        //Seminar löschen
        if($func == 'del')
        {
                $seminar = new seminar($seminar_id);
                $seminar->seminarDelete();
        }       

        //Status ändern
        if($func == 'status')
        {
                $seminar = new seminar($seminar_id);
                $seminar->seminarChangeStatus();
        }
        
        //Seminar kopieren
        if($func == 'duplicate')
        {
                $origSeminar = new seminar($seminar_id); //originales Seminar
                $dupSeminar = new seminar(); //Kopie
                //Stammdaten
                $dupSeminar->setSeminarStart('2020-01-01');
                $dupSeminar->setSeminarEnde('2020-01-01');
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
