<?php
/**
 * Klasse zum bearbeiten von Partnern
 * 
 * @author Falko benthin
 * @link http://www.hochdrei.org
 * 
 */

class partner
{
  /**
   * @private integer $partner_id ID eines Partners
   */
  private $partner_id ;

  /**
   * @private string $name Name einer Partners
   */
  private $name ;

  /**
   * @private string $website einer Partners
   */
  private $website ;

  /**
   * @access  public
   *
   * @param   integer   $partner_id
   */
  function __construct($partner_id = null)
  {
    if(!is_null($partner_id))
    {
      //echo "Partner ID\n";
      $this->partner_id = $partner_id ;
      $sql = \rex_sql::factory() ;
      $sql->setDebug = \rex::getProperty('debug') ;
      //Infos holen, sind in allen Sprachen gleich
      $queryPartner = 'SELECT name, website FROM '. \rex::getTablePrefix() . 'skh3_partner WHERE partner_id=' . $this->partner_id ;
      $sql->setQuery($queryPartner) ;
      if($sql->getRow())
      {
        $this->name = htmlspecialchars_decode($sql->getValue('name'),ENT_QUOTES) ;
        $this->website = htmlspecialchars_decode($sql->getValue('website'),ENT_QUOTES) ;
      }
    }
    else
    {
      $this->name = '' ;
      $this->website = '' ;
    }
  }
  
  //Getter
  public function getPartnerID()
  {
    return $this->partner_id ;
  }
  public function getName()
  {
    return $this->name ;
  }
	public function getwebsite()
  {
    return $this->website ;
  }
  //Setter
  public function setPartnerID($partner_id)
  {
    $this->partner_id=$partner_id ;
  }
  public function setName($name)
  {
    $this->name = $name ;
  }
  public function setwebsite($website)
  {
    $this->website = $website ;
  }

  //Änderungen speichern
  public function partnerSave()
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
    $queryPartner = '' ;
    if(empty($this->partner_id))
    {
      echo('Neuer Eintrag \n') ;
      $queryPartner = 'INSERT INTO ' . \rex::getTablePrefix() . 'skh3_partner (name, website) VALUES (\'' . $this->name . '\',\'' . $this->website . '\')' ; 
    }
    else
    {
      echo('Eintrag ändern') ;
      $queryPartner = 'UPDATE ' . \rex::getTablePrefix() . 'skh3_partner SET name=\'' . $this->name . '\', website=\'' . $this->website . '\' WHERE partner_id=' . $this->partner_id ;
    }
    $sql = rex_sql::factory() ;
    $sql->setDebug = \rex::getProperty('debug') ;
    if($sql->setQuery($queryPartner))
      echo('Partner erfolgreich gespeichert') ;
    else
    {
      echo('Fehler') ;
      return false ;
    }
    return true ;
  }

  //partner löschen
  public function partnerDelete()
  {
    $sql = rex_sql::factory() ;
    $sql->setDebug = \rex::getProperty('debug') ;
    $queryDelete = 'DELETE FROM ' . \rex::getTablePrefix() . 'skh3_partner WHERE partner_id=' . $this->partner_id ;
    if($sql->setQuery($queryDelete))
      echo('Partner gelöscht') ;
    else
    {
      echo('Fehler: ' . $sql->getError() . 'Query: ' . $queryDelete) ;
      return false ;
    }
    return true ;
  }
}
