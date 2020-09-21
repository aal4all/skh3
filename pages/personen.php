<?php
	error_reporting('E_ALL');
	echo("Hier kommt das PErsonengeraffel") ;
	/*
        //Sprachen
        //$sprachen_add = '&amp;subpage='. $subpage. '&amp;func='.$func;
  //require $REX['INCLUDE_PATH'].'/functions/function_rex_languages.inc.php';
  //Personen-Klasse
  require_once ($REX['INCLUDE_PATH']. '/addons/'. rex_request('page', 'string','') .'/classes/class.personen.inc.php');
  $person_id = rex_request('person_id', 'int');
  $clang = rex_request('clang', 'int');
  //if(is_null($clang) || !isset($clang))
    //$clang = 0;
    
        //Personen auflisten
  if ($func == '') 
  {
  {
                
          $query='SELECT person_id, vorname, name
                FROM '.$REX['TABLE_PREFIX'].'skh3_personen';
    
                $list = rex_list::factory($query);
    
                $thIcon = '<a href="'. $list->getUrl(array('func' => 'add')) .'"><img src="media/user_plus.gif" alt="add" title="add" /></a>';
                //bis Redaxo 4.4: $tdIcon = '<a href="'. $list->getUrl(array('func' => 'edit','person_id' => '###person_id###')).'"><img src="media/user.gif" alt="edit" title="edit" /></a>';
    //bis Redaxo 4.4: $tdBesch = '<a href="'. $list->getUrl(array('func' => 'desc','person_id' => '###person_id###')).'"><img src="media/document.gif" alt="description" title="description" /></a>';
    //bis Redaxo 4.4: $tdDelete = '<a href="'. $list->getUrl(array('func' => 'del','person_id' => '###person_id###')).'">löschen</a>';
    $tdIcon = '<img src="media/user.gif" alt="edit" title="edit" />';
    $tdBesch = '<img src="media/document.gif" alt="description" title="description" />';
    $tdDelete = 'löschen';
    //neue Spalte addColumn(spaltentitel,text der Folgezeilen,position der Spalte, Formatierung)
    //$list->addColumn($thIcon, '<img src="media/metainfo.gif" alt="field" title="field" />', 0, array( '<th class="rex-icon">###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' )); 
                $list->addColumn($thIcon, $tdIcon, 0, array( '<th class="rex-icon">###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' )); 
                $list->setColumnParams($thIcon, array('func' => 'edit','person_id' => '###person_id###'));
                //Spalte für Beschreibungsicon
                $list->addColumn('Beschr', $tdBesch, 4, array( '<th>###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' ));
                $list->setColumnParams('Beschr',array('func' => 'desc','person_id' => '###person_id###'));
                //spalte zum löschen
                $list->addColumn('Löschen', $tdDelete, 5, array( '<th>###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' ));
                $list->setColumnParams('Löschen', array('func' => 'del','person_id' => '###person_id###'));
    //id entfernen
                $list->removeColumn('person_id');
        
                $list->addTableColumnGroup(array(25, '*', '*', 5, 5));
                $list->setColumnLabel('vorname', 'Vorname'); 
                $list->setColumnLabel('name', 'Name');
    
                //felder sortierbar machen
                $list->setColumnSortable('vorname');
                $list->setColumnSortable('name');

                $list->show();
  }
  //Namen ändern oder hinzufügen
  if ($func == 'add' || $func == 'edit') 
  {             
                if($func == 'add') //wenn keine Person ausgewählt wurde
                        $person = new personen(null, $clang);
                //ID für add und 
                if($func == 'edit')
                        $person = new personen($person_id, $clang);
                //Formular anzeigen
?>
                <div class="rex-addon-output">
        <?php $headline = $func == 'edit' ? 'Person bearbeiten' : 'Neue Person'; ?>
                        <h2 class="rex-hl2"><?php echo $headline; echo (' (ID ' . $person->getPersonID() . ' )'); ?></h2>
<?php
        if(isset($_POST['submit'])) 
        {
                $person->setVorname($_POST['vorname']);
                $person->setName($_POST['name']);
                //$person->setBeschreibung($_POST['beschreibung']);
                if($person->personSave())
                  echo('Speichern erfolgreich');
                else
                        echo('ein Fehler ist aufgetreten');
        }
        else
        { 
 ?>
                        <div class="skh3-form">
                                <form action="" method="post">
                                        <!--<fieldset class="rex-form-col-1">-->
                                                <!--<div class="rex-form-wrapper">-->
                                                        <label for="vorname">Vorname</label><input type="text" class="txt" name="vorname" id="vorname" value="<?php echo $person->getVorname(); ?>" /><br />
                                                        <label for="name">Name</label><input type="text" class="txt" name="name" id="name" value="<?php echo $person->getName(); ?>" /><br />
                                                        <input type="submit" class="btn" name="submit" value="Speichern" />
                                </form>
                        </div>
                </div>
<?php
                }
        }
        //person löschen
        if($func == 'del')
        {
                $person = new personen($person_id, $clang);
                $person->personDelete();
        }
        
        //beschreibungen
        if($func == 'desc')
        {
                $query='SELECT person_id, clang, beschreibung 
                FROM '.$REX['TABLE_PREFIX'].'skh3_personen_lok
                WHERE person_id='.$person_id;
    
                $list = rex_list::factory($query);
    
                $thIcon = '<a href="'. $list->getUrl(array('func' => 'descAdd','person_id' => $person_id)) .'"><img src="media/document_plus.gif" alt="add" title="add" /></a>';
                //bis Redaxo 4.4: $tdIcon = '<a href="'. $list->getUrl(array('func' => 'descEdit','person_id' => '###person_id###', 'clang' => '###clang###')).'"><img src="media/document.gif" alt="edit" title="edit" /></a>';
                //bis Redaxo 4.4: $tdDescDel = '<a href="'. $list->getUrl(array('func' => 'descDel','person_id' => '###person_id###', 'clang' => '###clang###')).'">löschen</a>';
                $tdIcon = '<img src="media/document.gif" alt="edit" title="edit" />';
                $tdDescDel = 'löschen';
                //Beschreibung bearbeiten
                $list->addColumn($thIcon, $tdIcon, 0, array( '<th class="rex-icon">###VALUE###</th>', '<td>###VALUE###</td>' )); 
                $list->setColumnParams($thIcon, array('func' => 'descEdit','person_id' => '###person_id###', 'clang' => '###clang###'));
                //Beschreibung löschen
                $list->addColumn('Löschen', $tdDescDel, 4, array( '<th>###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' ));
                $list->setColumnParams('Löschen', array('func' => 'descDel','person_id' => '###person_id###', 'clang' => '###clang###'));
                //$list->removeColumn('person_id');
                $list->setColumnLabel('clang', 'Sprache'); 
                $list->setColumnLabel('beschreibung', 'Beschreibung');
    
                $list->show();
        }
        //Beschreibungen bearbeiten
        if($func == 'descAdd' || $func == 'descEdit')
        {
                $person = new personen($person_id, $clang);
?>
                <div class="rex-addon-output">
        <?php $headline = $func == 'descEdit' ? 'Personenbeschreibung bearbeiten' : 'Neue Personenbeschreibung'; ?>
                        <h2 class="rex-hl2"><?php echo $headline; echo (' (ID ' . $person->getPersonID() . ' )'); ?></h2>
<?php
                if(isset($_POST['desc_submit'])) 
                {
                
                        $person->setBeschreibung($_POST['beschreibung']);
                        //if(isset($_POST['clang'])) //Sprache setzen für neue einträge
                                //$person->setClang($_POST['clang']);
                        if($person->personBeschreibungSave())
                        echo('Speichern erfolgreich');
                        else
                                echo('ein Fehler ist aufgetreten');
                }
                else
                { 
?>
                        <div class="skh3-form">
                                <form action="" method="post">
                                                <?php
                                                
                                                if($func == 'descAdd') //Wenn neuer Eintrag, Menu für Sprachen anzeigen
                                                {
                                                        echo('<label for="sprache">Sprache</label><select name="clang" id="sprache">');
                                                                echo('<option value="">Sprache wählen</option>');
                                                                //Sprachen aus DB holen
                                                                $sql = rex_sql::factory();
                                                                $queryClang = 'SELECT id, name FROM ' . $REX['TABLE_PREFIX'] . 'clang;' ;
                                                                $sql->setQuery($queryClang);
                                                                for($i = 0; $i < $sql->getRows(); $i++)
                                                                {
                                                                        echo '<option value="' . $sql->getValue('id') .'">' . $sql->getValue('name') .'</option>';
                                                                        $sql->next();
                                                                }
                                                        echo('</select> <br />');
                                                }
                                                ?>
                                                        <label for="beschreibung">Beschreibung</label><textarea class="txt" name="beschreibung" id="beschreibung" cols="50" rows="5"><?php 
                                                        echo $person->getBeschreibung(); 
                                                        ?></textarea><br />
                                                        <input type="submit" class="btn" name="desc_submit" value="Speichern" />
                                </form>
                        </div>
                </div>
<?php
                }
        }
        //Beschreibung löschen
        if($func == 'descDel')
        {
                $person = new personen($person_id, $clang);
                $person->beschreibungDelete();
        }
  */
?>
