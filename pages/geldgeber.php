<?php
        error_reporting('E_ALL');
  //geldgeber-Klasse
  require_once ($REX['INCLUDE_PATH']. '/addons/'. rex_request('page', 'string','') .'/classes/class.geldgeber.inc.php');
        $geldgeber_id = rex_request('geldgeber_id', 'int');
        
        //geldgeber auflisten
  if ($func == '') 
  {
                
          $query='SELECT geldgeber_id, name, webseite
                FROM '.$REX['TABLE_PREFIX'].'skh3_geldgeber';
    $list = rex_list::factory($query);
    
    //Spalte zum Hinzufügen und Editieren
                $thIcon = '<a href="'. $list->getUrl(array('func' => 'add')) .'"><img src="media/user_plus.gif" alt="add" title="add" /></a>';
                //bis redaxo 4.4: $tdIcon = '<a href="'. $list->getUrl(array('func' => 'edit','geldgeber_id' => '###geldgeber_id###')).'"><img src="media/user.gif" alt="edit" title="edit" /></a>';
                $tdIcon = '<img src="media/user.gif" alt="edit" title="edit" />';
    //bis redaxo 4.4: $tdDelete = '<a href="'. $list->getUrl(array('func' => 'del','geldgeber_id' => '###geldgeber_id###')).'">löschen</a>';
    $tdDelete = 'löschen';
    //neue Spalte addColumn(spaltentitel,text der Folgezeilen,position der Spalte, Formatierung)
    //$list->addColumn($thIcon, '<img src="media/metainfo.gif" alt="field" title="field" />', 0, array( '<th class="rex-icon">###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' )); 
    //Spalte zum hinzufügen und editieren
                $list->addColumn($thIcon, $tdIcon, 0, array( '<th class="rex-icon">###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' )); 
                $list->setColumnParams($thIcon, array('func' => 'edit','geldgeber_id' => '###geldgeber_id###'));
                //spalte zum löschen
                $list->addColumn('Löschen', $tdDelete, 4, array( '<th>###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' ));
                $list->setColumnParams('Löschen',array('func' => 'del','geldgeber_id' => '###geldgeber_id###'));
    //id entfernen
                $list->removeColumn('geldgeber_id');
        
                $list->addTableColumnGroup(array(5, '*', '*', 5));
                $list->setColumnLabel('name', 'Geldgeber');
                $list->setColumnLabel('webseite', 'Webseite');     
                //felder sortierbar machen
                $list->setColumnSortable('name');

                $list->show();
  }
  //geldgeber hinzufügen oder ändern
  if ($func == 'add' || $func == 'edit') 
  {
                if($func == 'add') //wenn keine geldgeber ausgewählt wurde
                        $geldgeber = new geldgeber(null);
                //ID für add und 
                if($func == 'edit')
                        $geldgeber = new geldgeber($geldgeber_id);
                //Formular anzeigen
?>
                <div class="rex-addon-output">
        <?php $headline = $func == 'edit' ? 'Geldgeber bearbeiten' : 'neuer Geldgeber'; ?>
                        <h2 class="rex-hl2"><?php echo $headline; echo (' (ID ' . $geldgeber->getgeldgeberID() . ' )'); ?></h2>
<?php
        if(isset($_POST['submit'])) 
        {
                $geldgeber->setName($_POST['name']);
                $geldgeber->setWebseite($_POST['webseite']);
                if($geldgeber->geldgeberSave())
                  echo('Speichern erfolgreich');
                else
                        echo('ein Fehler ist aufgetreten');
        }
        else
        { 
 ?>
                        <div class="skh3-form">
                                <form action="" method="post">
                                                        <label for="name">Name</label><input type="text" class="txt" name="name" id="name" value="<?php echo $geldgeber->getName(); ?>" /><br />
                                                        <label for="webseite">Webseite</label><input type="text" class="txt" name="webseite" id="webseite" value="<?php echo $geldgeber->getWebseite(); ?>" /><br />
                                                        <input type="submit" class="btn" name="submit" value="Speichern" />
                                </form>
                        </div>
                </div>
<?php
                }
        }
                
        //geldgeber löschen
        if($func == 'del')
        {
                $geldgeber = new geldgeber($geldgeber_id);
                $geldgeber->geldgeberDelete();
        }
?>
