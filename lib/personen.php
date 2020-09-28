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
   * @access  public
   * @param   integer   $person_id
   */
  function __construct($person_id = null, $lang_id = null)
  {
    if(!is_null($person_id))
    {
      //echo "PERSON ID\n";
      $this->person_id = $person_id ;
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
    else
    {
      $this->vorname = '' ;
      $this->name = '' ;
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

  //Setter
  public function setVorname($vorname)
  {
    $this->vorname = $vorname ;
  }
  public function setName($name)
  {
    $this->name = $name ;
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
    $sql = \rex_sql::factory() ;
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
    $sql = \rex_sql::factory() ;
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
	
}
?>


