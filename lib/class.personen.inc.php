<?php
/**
 * Klasse zum bearbeiten von ReferentInnen
 * 
 * @author Falko benthin
 * @link http://www.hochdrei.org
 * @package Redaxo 4.3
 * 
 */

class personen
{
	/**
   * @private integer $person_id ID einer Person
   */
	private $person_id;
	
	/**
   * @private string $vorname Vorname einer Person
   */
	private $vorname;
	
	/**
   * @private string $name Nachname einer Person
   */
	private $name;
	
	/**
   * @private integer $clang Sprach_ID
   */
	private $clang;
	
	/**
   * @private string $beschreibung Kurzbeschreibung einer Person
   */
	private $beschreibung;
	
	/**
	* PHP 5 Constructor
	* @access  public
	* 
	* @param   integer   $person_id
	*/
	function __construct($person_id = null, $clang = null)
  {
		global $REX;
		if(!is_null($person_id))
		{
			//echo "PERSON ID\n";
			$this->person_id = $person_id;
			$this->clang = $clang;
			$sql = rex_sql::factory();
			$sql->setDebug = true;
			//Vornamen und Namen holen, sind in allen Sprachen gleich
			$queryName = 'SELECT vorname, name 
   		FROM '.$REX['TABLE_PREFIX'].'skh3_personen
    	WHERE person_id=' . $this->person_id;
			$sql->setQuery($queryName);
			if($sql->getRow())
			{
				$this->vorname = htmlspecialchars_decode($sql->getValue('vorname'),ENT_QUOTES);
				$this->name = htmlspecialchars_decode($sql->getValue('name'),ENT_QUOTES);
			}
			//holt beschreibung in jeweiliger Sprache
			$queryBeschreibung = 'SELECT beschreibung 
			FROM '.$REX['TABLE_PREFIX'].'skh3_personen_lok
			WHERE person_id=' . $this->person_id . ' AND clang=' . $this->clang;
			unset($sql);
			$sql = rex_sql::factory();
			$sql->setDebug = true;
			$sql->setQuery($queryBeschreibung);
			if($sql->getRow())
				$this->beschreibung = htmlspecialchars_decode($sql->getValue('beschreibung'),ENT_QUOTES);
		}
		else
		{
			$this->vorname = '';
			$this->name = '';
			$this->clang = $clang;
			$this->beschreibung = '';
		}
  }
  
  //Getter
  public function getPersonID()
  {
		return $this->person_id;
	}
	public function getVorname()
	{
		return $this->vorname;
	}
	public function getName()
	{
		return $this->name;
	}
	public function getClang()
	{
		return $this->clang;
	}
	public function getBeschreibung()
	{
		return $this->beschreibung;
	}
	
	//Setter
	public function setVorname($vorname)
	{
		$this->vorname = $vorname;
	}
	public function setName($name)
	{
		$this->name = $name;
	}
	//lokalisierte Beschreibungen
	public function setClang($clang)
	{
		$this->clang = $clang;
	}
	public function setBeschreibung($beschreibung)
	{
		$this->beschreibung = $beschreibung;
	}

	//Speichern
	public function PersonSave()
	{
		global $REX;
		if(empty($this->vorname) || empty($this->name) )
		{
			echo ('Vorname und Name dürfen nicht leer sein');
			return false;
		}
		//Escapezeichen und whitespaces behandeln
		$this->vorname = htmlspecialchars(trim($this->vorname),ENT_QUOTES,'UTF-8');
		$this->name= htmlspecialchars(trim($this->name),ENT_QUOTES,'UTF-8');
		if(empty($this->person_id))
		{
			echo('Neuer Eintrag');
			$queryName = 'INSERT INTO ' . $REX['TABLE_PREFIX'] . 'skh3_personen (vorname, name) VALUES (\'' . $this->vorname . '\',\'' . $this->name . '\');'; 
		}
		else
		{
			echo('Eintrag ändern');
			$queryName = 'UPDATE ' . $REX['TABLE_PREFIX'] . 'skh3_personen SET vorname=\'' . $this->vorname . '\', name=\'' . $this->name . '\' WHERE person_id=' . $this->person_id . ';'; 
		}
		$sql = rex_sql::factory();
		$sql->setDebug = true;
		if($sql->setQuery($queryName))
			echo('Person erfolgreich gespeichert');
		else
		{
			echo('Fehler');
			return false;
		}
		return true;
	}
	
	//person löschen
	public function personDelete()
	{
		global $REX;
		$sql = rex_sql::factory();
		$sql->setDebug = true;
		$queryDelete = 'DELETE FROM ' . $REX['TABLE_PREFIX'] . 'skh3_personen WHERE person_id=' . $this->person_id .';';
		if($sql->setQuery($queryDelete))
				echo('Person gelöscht');
		else
		{
			echo('Fehler: \n' . $sql->getError() . '\nQuery: ' . $queryDelete);
			return false;
		}
		return true;
	}
	
	
	//Speichert Beschreibung zu einer Person
	public function personBeschreibungSave()
	{
		global $REX;
		if(empty($this->beschreibung))
		{
			echo ('<br />Beschreibung darf nicht leer sein');
			return false;
		}
		$this->beschreibung = htmlspecialchars(trim($this->beschreibung),ENT_QUOTES,'UTF-8');
		//DAtenbank
		$sql = rex_sql::factory();
		
		//prüfen, ob bereits ein Tabelleneintrag existiert -> insert oder update
		$queryCount = 'SELECT COUNT(*) AS anzahl FROM ' . $REX['TABLE_PREFIX'] . 'skh3_personen_lok WHERE person_id = ' . $this->person_id . ' AND clang = ' . $this->clang . ';';
		//Query absetzen
		$sql->setQuery($queryCount);
		//Ergernis prüfen
		if($sql->getValue('anzahl') != 1) //INSERT
		{
			//Namen und Vornamen eintragen
			echo('Neuer Eintrag \n');
			$queryBeschr = 'INSERT INTO ' . $REX['TABLE_PREFIX'] . 'skh3_personen_lok (person_id, clang, beschreibung) VALUES (' . $this->person_id . ', ' . $this->clang . ', \'' . $this->beschreibung . '\');'; 
		}
		else
		{
			echo('ändern \n');
			$queryBeschr = 'UPDATE ' . $REX['TABLE_PREFIX'] . 'skh3_personen_lok SET beschreibung=\'' . $this->beschreibung . '\' WHERE person_id=' . $this->person_id . ' AND clang=' . $this->clang . ';'; 
		}
		if($sql->setQuery($queryBeschr))
			echo('<br />Beschreibung erfolgreich gespeichert');
		else
		{
			echo('<br />Fehler ' .$sql->getError() );
			return false;
		}
		return true;
	}
	
	//beschreibung löschen
	public function beschreibungDelete()
	{
		global $REX;
		$sql = rex_sql::factory();
		$sql->setDebug = true;
		$queryDelete = 'DELETE FROM ' . $REX['TABLE_PREFIX'] . 'skh3_personen_lok WHERE person_id=' . $this->person_id .' AND clang=' . $this->clang . ';';
		if($sql->setQuery($queryDelete))
				echo('<br />Beschreibung gelöscht');
		else
		{
			echo('<br />Fehler: ' . $sql->getError() . ' Query: ' . $queryDelete);
			return false;
		}
		return true;
	}
}
?>


