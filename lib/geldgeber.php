<?php
/**
 * Klasse zum bearbeiten von Geldgebern
 * 
 * @author Falko Benthin
 * @link http://www.hochdrei.org
 * 
 */
 
namespace skh3 ;
class geldgeber
{
  /**
   * @private integer $geldgeber_id ID eines geldgebers
  */
  private $geldgeber_id ;
	
  /**
  * @private string $name Name einer geldgebers
  */
  private $name ;
	
  /**
   * @private string $website einer geldgebers
   */
  private $website ;
	
  /**
   * Constructor
   * @access  public
   * 
   * @param   integer   $geldgeber_id
   */
  function __construct($geldgeber_id = null)
  {
    if(!is_null($geldgeber_id))
    {
      //echo "geldgeber ID\n" ;
      $this->geldgeber_id = $geldgeber_id ;
      $sql = \rex_sql::factory() ;
      $sql->setDebug = \rex::getProperty('debug') ;
      //Infos holen, sind in allen Sprachen gleich
      $querygeldgeber = 'SELECT name, website FROM ' . \rex::getTablePrefix() . 'skh3_geldgeber WHERE geldgeber_id=' . $this->geldgeber_id ;
      $sql->setQuery($querygeldgeber) ;
      if($sql->getRow())
      {
        $this->name = htmlspecialchars_decode($sql->getValue('name'),ENT_QUOTES) ;
        $this->website = htmlspecialchars_decode($sql->getValue('website'),ENT_QUOTES) ;
      }
    }
    else
    {
      $this->name = '';
      $this->website = '';
    }
  }
  
  //Getter
  public function getgeldgeberID()
  {
    return $this->geldgeber_id ;
  }
  public function getName()
  {
    return $this->name ;
  }
  public function getWebsite()
  {
    return $this->website ;
  }
  //Setter
  public function setgeldgeberID($geldgeber_id)
  {
    $this->geldgeber_id=$geldgeber_id ;
  }
  public function setName($name)
  {
    $this->name = $name ;
  }
  public function setWebsite($website)
  {
    $this->website = $website ;
  }
	
  //Änderungen speichern
  public function geldgeberSave()
  {
    if(empty($this->name))
    {
      echo ('Name darf nicht leer sein') ;
      return false ;
    }
    //Escapezeichen und whitespaces behandeln
    $this->name = htmlspecialchars(trim($this->name),ENT_QUOTES,'UTF-8') ;
    $this->website = htmlspecialchars(trim($this->website),ENT_QUOTES,'UTF-8') ;
    if(!empty($this->website))
    {
      if(!validateUrl($this->website))
      {
        echo '<br />Fehler: URL muss wie folgt eingegeben werden: http(s)://www.so.de' ;
        return false ;
      }
    }
    if(empty($this->geldgeber_id))
    {
      echo('Neuer Eintrag \n') ;
      $querygeldgeber = 'INSERT INTO ' . \rex::getTablePrefix() . 'skh3_geldgeber (name, website) VALUES (\'' . $this->name . '\',\'' . $this->website . '\')' ; 
    }
    else
    {
      echo('Eintrag ändern') ;
      $querygeldgeber = 'UPDATE ' . \rex::getTablePrefix() . 'skh3_geldgeber SET name=\'' . $this->name . '\', website=\'' . $this->website . '\' WHERE geldgeber_id=' . $this->geldgeber_id ;
    }
    $sql = \rex_sql::factory();
    $sql->setDebug = \rex::getProperty('debug') ;
    if($sql->setQuery($querygeldgeber))
      echo('geldgeber erfolgreich gespeichert') ;
    else
    {
      echo('Fehler') ;
      return false ;
    }
    return true ;
  }
	
  //geldgeber löschen
  public function geldgeberDelete()
  {
    $sql = \rex_sql::factory() ;
    $sql->setDebug = \rex::getProperty('debug') ;
    $queryDelete = 'DELETE FROM ' . \rex::getTablePrefix() . 'skh3_geldgeber WHERE geldgeber_id=' . $this->geldgeber_id ;
    if($sql->setQuery($queryDelete))
      echo('geldgeber gelöscht');
    else
    {
      echo('Fehler: ' . $sql->getError() . 'Query: ' . $queryDelete);
      return false;
    }
    return true;
  }
}
