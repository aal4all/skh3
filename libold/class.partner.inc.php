<?php
/**
 * Klasse zum bearbeiten von Partnern
 * 
 * @author Falko benthin
 * @link http://www.hochdrei.org
 * @package Redaxo 4.3
 * 
 */

class partner
{
	/**
   * @private integer $partner_id ID eines Partners
   */
	private $partner_id;
	
	/**
   * @private string $name Name einer Partners
   */
	private $name;
	
	/**
   * @private string $webseite einer Partners
   */
	private $webseite;
	
	/**
	* PHP 5 Constructor
	* @access  public
	* 
	* @param   integer   $partner_id
	*/
	function __construct($partner_id = null)
  {
		global $REX;
		if(!is_null($partner_id))
		{
			//echo "Partner ID\n";
			$this->partner_id = $partner_id;
			$sql = rex_sql::factory();
			$sql->setDebug = true;
			//Infos holen, sind in allen Sprachen gleich
			$queryPartner = 'SELECT name, webseite 
   		FROM '.$REX['TABLE_PREFIX'].'skh3_partner
    	WHERE partner_id=' . $this->partner_id;
			$sql->setQuery($queryPartner);
			if($sql->getRow())
			{
				$this->name = htmlspecialchars_decode($sql->getValue('name'),ENT_QUOTES);
				$this->webseite = htmlspecialchars_decode($sql->getValue('webseite'),ENT_QUOTES);
			}
		}
		else
		{
			$this->name = '';
			$this->webseite = '';
		}
  }
  
  //Getter
  public function getPartnerID()
  {
		return $this->partner_id;
	}
  public function getName()
  {
		return $this->name;
	}
	public function getWebseite()
	{
		return $this->webseite;
	}
	//Setter
	public function setPartnerID($partner_id)
	{
		$this->partner_id=$partner_id;
	}
	public function setName($name)
	{
		$this->name = $name;
	}
	public function setWebseite($webseite)
	{
		$this->webseite = $webseite;
	}
	
	//Änderungen speichern
	public function partnerSave()
	{
		global $REX;
		if(empty($this->name))
		{
			echo ('Name darf nicht leer sein');
			return false;
		}
		//Escapezeichen und whitespaces behandeln
		$this->name = htmlspecialchars(trim($this->name),ENT_QUOTES,'UTF-8');
		$this->webseite = htmlspecialchars(trim($this->webseite),ENT_QUOTES,'UTF-8');
		if(!empty($this->webseite))
		{
			if(!validateUrl($this->webseite))
			{
				echo '<br />Fehler: URL muss wie folgt eingegeben werden: http(s)://www.so.de';
				return false;
			}
		}
		$queryPartner = '';
		if(empty($this->partner_id))
		{
			echo('Neuer Eintrag \n');
			$queryPartner = 'INSERT INTO ' . $REX['TABLE_PREFIX'] . 'skh3_partner (name, webseite) VALUES (\'' . $this->name . '\',\'' . $this->webseite . '\');'; 
		}
		else
		{
			echo('Eintrag ändern');
			$queryPartner = 'UPDATE ' . $REX['TABLE_PREFIX'] . 'skh3_partner SET name=\'' . $this->name . '\', webseite=\'' . $this->webseite . '\' WHERE partner_id=' . $this->partner_id . ';'; 
		}
		$sql = rex_sql::factory();
		$sql->setDebug = true;
		if($sql->setQuery($queryPartner))
			echo('Partner erfolgreich gespeichert');
		else
		{
			echo('Fehler');
			return false;
		}
		return true;
	}
	
	//partner löschen
	public function partnerDelete()
	{
		global $REX;
		$sql = rex_sql::factory();
		$sql->setDebug = true;
		$queryDelete = 'DELETE FROM ' . $REX['TABLE_PREFIX'] . 'skh3_partner WHERE partner_id=' . $this->partner_id .';';
		if($sql->setQuery($queryDelete))
				echo('Partner gelöscht');
		else
		{
			echo('Fehler: ' . $sql->getError() . 'Query: ' . $queryDelete);
			return false;
		}
		return true;
	}
}
