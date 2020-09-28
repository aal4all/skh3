<?php
/**
 * Klasse zum bearbeiten von ReferentInnen
 * 
 * @author Falko benthin
 * @link http://www.hochdrei.org
 * 
 */

namespace skh3 ;
class personen
{
  /**
   * @private integer $person_id ID einer Person
   */
  private $person_id ;

  /**
   * @private string $vorname Vorname einer Person
   */
  private $vorname ;

  /**
   * @private string $name Nachname einer Person
   */
  private $name ;

  /**
   * @private integer $lang_id Sprach_ID
   */
  private $lang_id ;

  /**
   * @private string $beschreibung Kurzbeschreibung einer Person
   */
  private $beschreibung ;
	
  /**
   * @access  public
   * @param   integer   $person_id
   */
  function __construct($person_id = null, $lang_id = null)
  {
    if(!is_null($person_id))
    {
      //echo "PERSON ID\n";
      $this->person_id = $person_id ;
      $this->lang_id = $lang_id ;
      $sql = \rex_sql::factory() ;
      $sql->setDebug = \rex::getProperty('debug') ;
      //Vornamen und Namen holen, sind in allen Sprachen gleich
      $queryName = 'SELECT vorname, name FROM ' . \rex::getTablePrefix() . 'skh3_personen	WHERE person_id=' . $this->person_id ;
      $sql->setQuery($queryName) ;
      if($sql->getRow())
      {
        $this->vorname = htmlspecialchars_decode($sql->getValue('vorname'),ENT_QUOTES) ;
        $this->name = htmlspecialchars_decode($sql->getValue('name'),ENT_QUOTES) ;
      }
      //holt beschreibung in jeweiliger Sprache
      $queryBeschreibung = 'SELECT beschreibung FROM ' . \rex::getTablePrefix() . 'skh3_personen_lok WHERE person_id=' . $this->person_id . ' AND lang_id=' . $this->lang_id ;
      unset($sql) ;
      $sql = \rex_sql::factory() ;
      $sql->setDebug = \rex::getProperty('debug') ;
      $sql->setQuery($queryBeschreibung) ;
      if($sql->getRow())
        $this->beschreibung = htmlspecialchars_decode($sql->getValue('beschreibung'),ENT_QUOTES) ;
    }
    else
    {
      $this->vorname = '' ;
      $this->name = '' ;
      $this->lang_id = $lang_id ;
      $this->beschreibung = '' ;
    }
  }

  //Getter
  public function getPersonID()
  {
  return $this->person_id ;
	}
  public function getVorname()
  {
    return $this->vorname ;
  }
  public function getName()
  {
    return $this->name ;
  }
  public function getClang()
  {
    return $this->lang_id ;
  }
  public function getBeschreibung()
  {
    return $this->beschreibung;
  }

  //Setter
  public function setVorname($vorname)
  {
    $this->vorname = $vorname ;
  }
  public function setName($name)
  {
    $this->name = $name ;
  }
  //lokalisierte Beschreibungen
  public function setClang($lang_id)
  {
    $this->lang_id = $lang_id ;
  }
  public function setBeschreibung($beschreibung)
  {
    $this->beschreibung = $beschreibung ;
  }

  //Speichern
  public function PersonSave()
  {
    if(empty($this->vorname) || empty($this->name) )
    {
      echo ('Vorname und Name dürfen nicht leer sein') ;
      return false ;
    }
    //Escapezeichen und whitespaces behandeln
    $this->vorname = htmlspecialchars(trim($this->vorname),ENT_QUOTES,'UTF-8') ;
    $this->name= htmlspecialchars(trim($this->name),ENT_QUOTES,'UTF-8') ;
    if(empty($this->person_id))
    {
      echo('Neuer Eintrag') ;
      $queryName = 'INSERT INTO ' . \rex::getTablePrefix() . 'skh3_personen (vorname, name) VALUES (\'' . $this->vorname . '\',\'' . $this->name . '\')' ;
    }
    else
    {
      echo('Eintrag ändern') ;
      $queryName = 'UPDATE ' . \rex::getTablePrefix() . 'skh3_personen SET vorname=\'' . $this->vorname . '\', name=\'' . $this->name . '\' WHERE person_id=' . $this->person_id ;
    }
    $sql = rex_sql::factory() ;
    $sql->setDebug = \rex::getProperty('debug') ;
    if($sql->setQuery($queryName))
      echo('Person erfolgreich gespeichert') ;
    else
    {
      echo('Fehler') ;
      return false ;
    }
    return true ;
  }

  //person löschen
  public function personDelete()
  {
    $sql = rex_sql::factory() ;
    $sql->setDebug = \rex::getProperty('debug') ;
    $queryDelete = 'DELETE FROM ' . \rex::getTablePrefix() . 'skh3_personen WHERE person_id=' . $this->person_id ;
    if($sql->setQuery($queryDelete))
      echo('Person gelöscht') ;
    else
    {
      echo('Fehler: \n' . $sql->getError() . '\nQuery: ' . $queryDelete) ;
      return false ;
    }
    return true ;
  }
	
  //Speichert Beschreibung zu einer Person
  public function personBeschreibungSave()
  {
    if(empty($this->beschreibung))
    {
      echo ('<br />Beschreibung darf nicht leer sein') ;
      return false ;
    }
    $this->beschreibung = htmlspecialchars(trim($this->beschreibung),ENT_QUOTES,'UTF-8') ;
    // DAtenbank
    $sql = \rex_sql::factory() ;
    //prüfen, ob bereits ein Tabelleneintrag existiert -> insert oder update
    $queryCount = 'SELECT COUNT(*) AS anzahl FROM ' . \rex::getTablePrefix() . 'skh3_personen_lok WHERE person_id = ' . $this->person_id . ' AND langid = ' . $this->langid ;
    //Query absetzen
    $sql->setQuery($queryCount) ;
    //Ergernis prüfen
    if($sql->getValue('anzahl') != 1) //INSERT
    {
      //Namen und Vornamen eintragen
      echo('Neuer Eintrag \n') ;
      $queryBeschr = 'INSERT INTO ' . \rex::getTablePrefix() . 'skh3_personen_lok (person_id, lang_id, beschreibung) VALUES (' . $this->person_id . ', ' . $this->lang_id . ', \'' . $this->beschreibung . '\')' ;
    }
    else
    {
      echo('ändern \n') ;
      $queryBeschr = 'UPDATE ' . \rex::getTablePrefix() . 'skh3_personen_lok SET beschreibung=\'' . $this->beschreibung . '\' WHERE person_id=' . $this->person_id . ' AND lang_id=' . $this->lang_id ;
    }
    if($sql->setQuery($queryBeschr))
      echo('<br />Beschreibung erfolgreich gespeichert') ;
    else
    {
      echo('<br />Fehler ' .$sql->getError() ) ;
      return false ;
    }
    return true ;
  }
	
  //beschreibung löschen
  public function beschreibungDelete()
  {
    $sql = rex_sql::factory() ;
    $sql->setDebug = \rex::getProperty('debug') ;
    $queryDelete = 'DELETE FROM ' . \rex::getTablePrefix() . 'skh3_personen_lok WHERE person_id=' . $this->person_id .' AND lang_id=' . $this->lang_id ;
    if($sql->setQuery($queryDelete))
      echo('<br />Beschreibung gelöscht') ;
    else
    {
      echo('<br />Fehler: ' . $sql->getError() . ' Query: ' . $queryDelete) ;
      return false ;
    }
    return true ;
  }
}
?>


