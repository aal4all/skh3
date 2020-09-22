<?php
/**
 * Klasse zum bearbeiten von Seminartypen
 * 
 * @author Falko Benthin
 * @link http://www.hochdrei.org
 * @package Redaxo 5.11
 * 
 */

class seminartyp
{
	/**
   * @private integer $typ_id ID eines Seminartyps
   */
	private $typ_id;
	
	/**
   * @private int $clang Sprach-ID
   */
	private $clang = array();
	
	/**
   * @private string $bezeichnung lesbare Bezeichnung eines Seminartyps
   */
	private $bezeichnung = array();
	
	/**
	* PHP 5 Constructor
	* @access  public
	* 
	* @param integer $typ_id
	*/

	function __construct($typ_id = null)
  {
		global $REX;
		if(!is_null($typ_id))
		{
			$this->typ_id = $typ_id;
			$sql = rex_sql::factory();
			$sql->setDebug = true;
			$querySeminartyp = 'SELECT clang, bezeichnung 
			FROM '.$REX['TABLE_PREFIX'].'skh3_seminartyp_lok
			WHERE typ_id=' . $this->typ_id . ';';
			$sql->setQuery($querySeminartyp);
			for($i=0; $i<$sql->getRows(); $i++)
			{
				$this->clang[$i] = $sql->getValue('clang');
				$this->bezeichnung[$i] = htmlspecialchars_decode($sql->getValue('bezeichnung'),ENT_QUOTES);
				$sql->next();
			}
		}
		else
		{
			$this->typ_id = null;
			//Sprachen aus DB holen
			$sql = rex_sql::factory();
			$queryClang = 'SELECT id FROM ' . $REX['TABLE_PREFIX'] . 'clang;' ;
			$sql->setQuery($queryClang);
			for($i = 0; $i < $sql->getRows(); $i++)
			{
				$this->clang[$i] = $sql->getValue('id');
				$this->bezeichnung[$i] = '';
				$sql->next();
			}
		}
  }

  //Getter
  public function getTypID()
  {
		return $this->typ_id;
	}
	public function getClang($i)
	{
		return $this->clang[$i];
	}
	public function getBezeichnung($i)
	{
		return $this->bezeichnung[$i];
	}
	
	//Setter
	public function setTypId($typ_id)
	{
		$this->typ_id = $typ_id;
	}
	//lokalisierte Beschreibungen
	public function setClang($i, $clang)
	{
		$this->clang[$i] = $clang;
	}
	public function setBezeichnung($i, $bezeichnung)
	{
		$this->bezeichnung[$i] = $bezeichnung;
	}

	//Speichern
	//Durch Arrays bezeichnung und clang latschen und wo Bezeichnung enthalten ist, speichern
	public function seminartypSave()
	{
		global $REX;
		//DB-Objekt
		$sql = rex_sql::factory();
		$sql->setDebug = true;
		if(empty($this->typ_id))
		{
			echo('Neuer Eintrag');
			//höchste Typ-ID aus DB holen 
			$queryMaxID = 'SELECT MAX(typ_id) AS max_id FROM '.$REX['TABLE_PREFIX'].'skh3_seminartyp_lok ;';
			if($sql->setQuery($queryMaxID))
			{
				$sql->getRow();
				$this->typ_id = $sql->getValue('max_id') + 1; //typ_id erhöhen
				echo 'Typ_ID = '. $this->typ_id;
			}
			else
			{
				echo('Fehler beim ermitteln der typ_id<br />'.$sql->getError());
				return false;
			}
			$sql->freeResult(); //Speicher freigeben
			for($i = 0; $i < count($this->clang); $i++)
			{
				echo "For-Schleife Durchlauf $i<br />";
				if(!empty($this->bezeichnung[$i]))
				{
					//Escapezeichen und whitespaces behandeln
					$this->bezeichnung[$i] = htmlspecialchars(trim($this->bezeichnung[$i]),ENT_QUOTES,'UTF-8');
					$querySemTyp = 'INSERT INTO '.$REX['TABLE_PREFIX'].'skh3_seminartyp_lok (typ_id, clang, bezeichnung) VALUES ('.$this->typ_id.','.$this->clang[$i].',\''.$this->bezeichnung[$i].'\');';
					//in DB speichern
					if($sql->setQuery($querySemTyp))
						echo('Seminartyp für Sprache '.$this->clang[$i].' erfolgreich gespeichert');
					else
					{
						echo('Fehler beim Speichern des Seminartyps<br />'.$sql->getError());
						return false;
					}
					$sql->freeResult();
				}
			}
		}
		else
		{
			echo('Eintrag ändern');
			for($i = 0; $i < count($this->clang); $i++)
			{
				if(!empty($this->bezeichnung[$i]))
				{
					//Escapezeichen und whitespaces behandeln
					$this->bezeichnung[$i] = htmlspecialchars(trim($this->bezeichnung[$i]),ENT_QUOTES,'UTF-8');
					$querySemTyp = 'UPDATE '.$REX['TABLE_PREFIX'].'skh3_seminartyp_lok SET bezeichnung=\''.$this->bezeichnung[$i].'\' WHERE typ_id='.$this->typ_id.' AND clang='.$this->clang[$i].';';
					//in DB speichern
					if($sql->setQuery($querySemTyp))
						echo('Seminartyp für Sprache '.$this->clang[$i].' erfolgreich gespeichert');
					else
					{
						echo('Fehler beim Speichern des Seminartyps<br />'.$sql->getError());
						return false;
					}
					$sql->freeResult();
				}
			}
		}
		return true;
	}

	//seminartyp löschen
	public function seminartypDelete()
	{
		global $REX;
		$sql = rex_sql::factory();
		$sql->setDebug = true;
		$queryDelete = 'DELETE FROM ' . $REX['TABLE_PREFIX'] . 'skh3_seminartyp_lok WHERE typ_id=' . $this->typ_id .';';
		if($sql->setQuery($queryDelete))
				echo('Seminartyp gelöscht');
		else
		{
			echo('Fehler: \n' . $sql->getError() . '\nQuery: ' . $queryDelete);
			return false;
		}
		return true;
	}
} 
?>
