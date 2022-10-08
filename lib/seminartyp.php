<?php
/**
 * Klasse zum bearbeiten von Seminartypen
 * 
 * @author Falko Benthin
 * @link http://www.hochdrei.org
 * @package Redaxo 5.11
 * 
 */
 
namespace skh3 ;
class seminartyp

{
	/**
   * @private integer $typ_id ID eines Seminartyps
   */
	private $typ_id ;
	
	/**
   * @private int $lang_id Sprach-ID
   */
	private $lang_id = array() ;
	
	/**
   * @private string $bezeichnung lesbare Bezeichnung eines Seminartyps
   */
	private $bezeichnung = array() ;
	
	/**
	* @access  public
	* 
	* @param integer $typ_id
	*/

	function __construct($typ_id = null)
  {
		if(!is_null($typ_id))
		{
			$this->typ_id = $typ_id ;
			$sql = \rex_sql::factory() ;
			$sql->setDebug = \rex::getProperty('debug') ;
			$querySeminartyp = 'SELECT lang_id, bezeichnung FROM ' . \rex::getTablePrefix() . 'skh3_seminartyp_lok WHERE typ_id=' . $this->typ_id . ';' ;
			$sql->setQuery($querySeminartyp) ;
			for($i=0; $i<$sql->getRows(); $i++)
			{
				$this->lang_id[$i] = $sql->getValue('lang_id') ;
				$this->bezeichnung[$i] = htmlspecialchars_decode($sql->getValue('bezeichnung'),ENT_QUOTES) ;
				$sql->next() ;
			}
		}
		else
		{
			$this->typ_id = null ;
			//Sprachen aus DB holen
			$sql = \rex_sql::factory() ;
			$queryClang = 'SELECT id FROM ' . \rex::getTablePrefix()  . 'clang;' ;
			$sql->setQuery($queryClang) ;
			for($i = 0; $i < $sql->getRows(); $i++)
			{
				$this->lang_id[$i] = $sql->getValue('id') ;
				$this->bezeichnung[$i] = '' ;
				$sql->next() ;
			}
		}
  }
  
  /**
	* @access  public
	* 
	* @return integer typ_id
	* 
	* type_id, lang_id ist primary key. typ_id is not autoinkrement. Must increase manually
	*/
  public function getNewTypID()
  {
		$sql = \rex_sql::factory() ;
		$sql->setDebug = \rex::getProperty('debug') ;
		$queryMaxID = 'SELECT MAX(typ_id) AS max_id FROM ' . \rex::getTablePrefix()  . 'skh3_seminartyp_lok' ;
		$sql->setQuery($queryMaxID) ;
		$sql->getRow() ;
    echo("#### MAX_ID: " . $sql->getValue('max_id')) ;
		if($sql->getValue('max_id') == null) {
		  $this->setTypId(1) ;
    }
		else {
		  $this->setTypId($sql->getValue('max_id')+1) ;
    }
    echo("neue typ_id" . $this->getTypId()) ;
		return true ;
	}

  //Getter
  public function getTypID()
  {
		return $this->typ_id ;
	}
	public function getClang($i)
	{
		return $this->lang_id[$i] ;
	}
	public function getBezeichnung($i)
	{
		return $this->bezeichnung[$i] ;
	}
	
	//Setter
	public function setTypId($typ_id)
	{
		$this->typ_id = $typ_id ;
	}
	//lokalisierte Beschreibungen
	public function setClang($i, $lang_id)
	{
		$this->lang_id[$i] = $lang_id ;
	}
	public function setBezeichnung($i, $bezeichnung)
	{
		$this->bezeichnung[$i] = $bezeichnung ;
	}

	//Speichern
	//Durch Arrays bezeichnung und lang_id latschen und wo Bezeichnung enthalten ist, speichern
	public function seminartypSave()
	{
		//DB-Objekt
		$sql = \rex_sql::factory() ;
		$sql->setDebug = \rex::getProperty('debug') ;
		if(empty($this->typ_id))
		{
			echo('Neuer Eintrag') ;
      echo("Alte Typ_ID: " . $this->getTypId() ) ;
			if($this->getNewTypID())
				echo ' Typ_ID = '. $this->getTypID() ;
			else
			{
				echo('Fehler beim ermitteln der typ_id<br />' . $sql->getError()) ;
				return false ;
			}
		// ab hier neu mit replace
    }
    else
      echo ('Eintrag ändern') ;
    for($i = 0; $i < count($this->lang_id); $i++)
    {
      if(!empty($this->bezeichnung[$i]))
      {
        $this->bezeichnung[$i] = htmlspecialchars(trim($this->bezeichnung[$i]),ENT_QUOTES,'UTF-8') ;
        $querySemTyp = 'REPLACE INTO ' . \rex::getTablePrefix() . 'skh3_seminartyp_lok (typ_id, lang_id, bezeichnung) VALUES (' . $this->typ_id . ',' . $this->lang_id[$i] . ',\'' . $this->bezeichnung[$i] . '\');' ;
        if($sql->setQuery($querySemTyp))
          echo('Seminartyp für Sprache ' . $this->lang_id[$i] . ' erfolgreich gespeichert') ;
        else
        {
          echo('Fehler beim Speichern des Seminartyps<br />' . $sql->getError()) ;
          return false ;
        }
      }
    }
		return true;
	}

	//seminartyp löschen
	public function seminartypDelete()
	{
		$sql = \rex_sql::factory() ;
		$sql->setDebug = \rex::getProperty('debug') ;
		$queryDelete = 'DELETE FROM ' . \rex::getTablePrefix() . 'skh3_seminartyp_lok WHERE typ_id=' . $this->typ_id  ;
		if($sql->setQuery($queryDelete))
				echo('Seminartyp gelöscht') ;
		else
		{
			echo('Fehler: \n' . $sql->getError() . '\nQuery: ' . $queryDelete) ;
			return false ;
		}
		return true ;
	}
} 
?>
