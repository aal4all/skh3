<?php
	error_reporting('E_ALL');
	echo"Hier kommt das Partnergeraffel");
	/*
  //Partner-Klasse
  require_once ($REX['INCLUDE_PATH']. '/addons/'. rex_request('page', 'string','') .'/classes/class.partner.inc.php');
        $partner_id = rex_request('partner_id', 'int');
        
        //partner auflisten
  if ($func == '') 
  {
                
          $query='SELECT partner_id, name, webseite
                FROM '.$REX['TABLE_PREFIX'].'skh3_partner';
    $list = rex_list::factory($query);
                
    //Spalte zum Hinzufügen und Editieren
                $thIcon = '<a href="'. $list->getUrl(array('func' => 'add')) .'"><img src="media/user_plus.gif" alt="add" title="add" /></a>';
                //bis redaxo 4.4: $tdIcon = '<a href="'. $list->getUrl(array('func' => 'edit','partner_id' => '###partner_id###')).'"><img src="media/user.gif" alt="edit" title="edit" /></a>';
                $tdIcon = '<img src="media/user.gif" alt="edit" title="edit" />';
    //bis Redaxo 4.4: $tdDelete = '<a href="'. $list->getUrl(array('func' => 'del','partner_id' => '###partner_id###')).'">löschen</a>';
    $tdDelete = 'löschen';
    //neue Spalte addColumn(spaltentitel,text der Folgezeilen,position der Spalte, Formatierung)
    //$list->addColumn($thIcon, '<img src="media/metainfo.gif" alt="field" title="field" />', 0, array( '<th class="rex-icon">###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' )); 
                //Spalte zum hinzufügen und Bearbeiten
                $list->addColumn($thIcon, $tdIcon, 0, array( '<th class="rex-icon">###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' )); 
                $list->setColumnParams($thIcon, array('func'=>'edit','partner_id' => '###partner_id###'));
                //spalte zum löschen
                $list->addColumn('Löschen', $tdDelete, 4, array( '<th>###VALUE###</th>', '<td //class="rex-icon">###VALUE###</td>' ));
                $list->setColumnParams('Löschen', array('func' => 'del','partner_id' => '###partner_id###'));
    //id entfernen
                $list->removeColumn('partner_id');
                
                $list->addTableColumnGroup(array(5, '*', '*', 5));
                $list->setColumnLabel('name', 'Partner');
                //$list->setColumnParams('name', array('func' => 'edit', 'partner_id' => '###partner_id###'));
                $list->setColumnLabel('webseite', 'Webseite');     
                //felder sortierbar machen
                $list->setColumnSortable('name');
                $list->show();
  }
  //Partner hinzufügen oder ändern
  if ($func == 'add' || $func == 'edit') 
  {
                if($func == 'add') //wenn keine Partner ausgewählt wurde
                        $partner = new partner(null);
                //ID für add und 
                if($func == 'edit')
                        $partner = new partner($partner_id);
                //Formular anzeigen
?>
                <div class="rex-addon-output">
        <?php $headline = $func == 'edit' ? 'Partner bearbeiten' : 'neuer Partner'; ?>
                        <h2 class="rex-hl2"><?php echo $headline; echo (' (ID ' . $partner->getPartnerID() . ' )'); ?></h2>
<?php
        if(isset($_POST['submit'])) 
        {
                $partner->setName($_POST['name']);
                $partner->setWebseite($_POST['webseite']);
                if($partner->partnerSave())
                  echo('Speichern erfolgreich');
                else
                        echo('ein Fehler ist aufgetreten');
        }
        else
        { 
 ?>
                        <div class="skh3-form">
                                <form action="" method="post">
                                                        <label for="name">Name</label><input type="text" class="txt" name="name" id="name" value="<?php echo $partner->getName(); ?>" /><br />
                                                        <label for="webseite">Webseite</label><input type="text" class="txt" name="webseite" id="webseite" value="<?php echo $partner->getWebseite(); ?>" /><br />
                                                        <input type="submit" class="btn" name="submit" value="Speichern" />
                                </form>
                        </div>
                </div>
<?php
                }
        }
                
        //Partner löschen
        if($func == 'del')
        {
                $partner = new partner($partner_id);
                $partner->partnerDelete();
        }
  */
?>
