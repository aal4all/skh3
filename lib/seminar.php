<?php
/**
 * Klasse zum bearbeiten von Seminaren
 * 
 * @author Falko Benthin
 * @link http://www.hochdrei.org
 * 
 */

namespace skh3 ;
class seminar
{
  /**
   * @private integer $seminar_id ID eines Seminars
   */
  private $seminar_id ;
  /**
   * @private string $seminar_start Beginn eines Seminars
   */
  private $seminar_start ;
  /**
   * @private string $seminar_ende Ende eines Seminars
   */
  private $seminar_ende ;
  /**
   * @private string $seminar_ort Ort des Seminars
   */
  private $seminar_ort ;
  /**
   * @private int $seminar_typ  Typ eines Seminars (Jugendbegegnung, Fortbildung etc)
   */
  private $seminar_typ ;
  /**
   * @private int $seminar_online Status (Online/Offline) eines Seminars
   */
  private $seminar_online ;
  /**
   * @private int $clang Sprach-ID (Array weil mehrsprachig)
   */
  private $clang = array() ;
  /**
   * @private string $titel  Titel des Seminars (Array weil mehrsprachig)
   */
  private $titel = array() ;
  /**
   * @private string $untertitel Untertitel eines Seminars (Array weil mehrsprachig)
   */
  private $untertitel = array() ;
  /**
   * @private string $beschreibung Seminarbeschreibung
   */
  private $beschreibung = array() ;
  /**
   * @private float $Kosten Kosten, für jede Nationalität unterschiedlich
   */
  private $kosten = array() ;
  /**
   * @private integer $waehrung waehrung, kann sich in verschiedenen Ländern unterscheiden
   */
  private $waehrung = array() ;
  /**
   * @private string $zielgruppe Zielgruppe (Array weil mehrsprachig)
   */
  private $zielgruppe = array() ;
  /**
   * @private int $refis ReferentInnen (array, weil es merhere sein können)
   */
  private $refis = array() ;
  /**
   * @private int $leitung Leitung (array, weil es merhere sein können)
   */
  private $leitung = array() ;
  /**
   * @private int $verantwortung Verantwortung (array, weil es merhere sein können)
   */
  private $verantwortung = array() ;
  /**
   * @private int $partner Kooperationspartner (Array, weil mehrere möglich)
   */
  private $partner = array() ;
  /**
   * @private int $geldgeber Foerderer einer Maßnahme (Array, weil mehrere möglich)
   */
  private $geldgeber = array() ;
  
  /**
   * @private int $langCount anzahl der verfügbaren Sprachen
   */
  private $langCount ;
  
  /**
   * @access  public
   * @param integer $seminar_id
   */
  function __construct($seminar_id = null)
  {
    //Anzahl der verfügbaren Sprachen bestimmen
    $sql = \rex_sql::factory() ;
    $queryClang = 'SELECT id FROM ' . \rex::getTablePrefix() . 'clang;' ;
    if($sql->setQuery($queryClang))
      $this->langCount = $sql->getRows() ;
    else
      echo('<font color="red">Fehler beim Holen der Anzahl der verfügbaren Sprachen</font><br />' . $sql->getError()) ;
    if(!is_null($seminar_id))
    {
      $this->seminar_id = $seminar_id ;
      //für jede Tabelle einzelne Abfrage, keine teuren JOINS
      $sql = \rex_sql::factory() ;
      $sql->setDebug = \rex::getProperty('debug') ;
      //Stammdaten Seminar
      $querySeminar = 'SELECT seminar_start, seminar_ende, seminar_ort, seminar_typ, seminar_online  FROM ' . \rex::getTablePrefix() . 'skh3_seminare  WHERE seminar_id=' . $this->seminar_id ;
      if($sql->setQuery($querySeminar))
      {
        $sql->getRow() ;
        $this->seminar_start = $sql->getValue('seminar_start') ;
        $this->seminar_ende = $sql->getValue('seminar_ende') ;
        $this->seminar_ort = htmlspecialchars_decode($sql->getValue('seminar_ort'),ENT_QUOTES) ;
        $this->seminar_typ = $sql->getValue('seminar_typ') ;
        $this->seminar_online = $sql->getValue('seminar_online') ;
      }
      else
      {
        echo('<font color="red">Fehler beim Holen der Stammdaten</font><br />'.$sql->getError()) ;
      }

      //lokalisierte Inhalte
      $querySemLok = 'SELECT clang, titel, untertitel, beschreibung, kosten, waehrung_id, zielgruppe FROM ' . \rex::getTablePrefix() . 'skh3_seminare_lok  WHERE seminar_id=' . $this->seminar_id ;
      if($sql->setQuery($querySemLok))
      {
        for($i=0; $i<$sql->getRows(); $i++)
        {
          //anstelle von $i wird die clangID für die Arrays verwendet, um sicherzustellen, dass später Zuordnung stimmt 
          $this->clang[$sql->getValue('clang')] = $sql->getValue('clang') ;
          $this->titel[$sql->getValue('clang')] = htmlspecialchars_decode($sql->getValue('titel'),ENT_QUOTES) ;
          $this->untertitel[$sql->getValue('clang')] = htmlspecialchars_decode($sql->getValue('untertitel'),ENT_QUOTES) ;
          $this->beschreibung[$sql->getValue('clang')] = htmlspecialchars_decode($sql->getValue('beschreibung'),ENT_QUOTES) ;
          $this->kosten[$sql->getValue('clang')] = $sql->getValue('kosten') ;
          $this->waehrung[$sql->getValue('clang')] = $sql->getValue('waehrung_id') ;
          $this->zielgruppe[$sql->getValue('clang')] = htmlspecialchars_decode($sql->getValue('zielgruppe'),ENT_QUOTES) ;
          $sql->next() ;
        }
        /*
         * prüfen, ob Anzahl der Ergebnisse mit Anzahl der verfügbaren Sprachen übereinstimmt
         * wenn nicht, prüfen, welche Sprachen erhältlich sind und für andere Defaultwerte und CLANG setzen
         */ 
        if(count($this->clang) < $this->langCount)
        {
          $sql = \rex_sql::factory() ;
          $queryClang = 'SELECT id FROM ' . \rex::getTablePrefix() . 'clang;' ;
          if($sql->setQuery($queryClang))
          {
            for($i = 0; $i < $sql->getRows(); $i++)
            {
              //Wenn langID noch nicht im Array clang, dann hinzufügen und defaultwerte setzen
              if(!in_array($sql->getValue('id'), $this->clang))
              {
                $this->clang[$sql->getValue('id')] = $sql->getValue('id') ;
                $this->titel[$sql->getValue('id')] = '' ;
                $this->untertitel[$sql->getValue('id')] = '' ;
                $this->beschreibung[$sql->getValue('id')] = '' ;
                $this->kosten[$sql->getValue('id')] = 0 ;
                $this->waehrung_id[$sql->getValue('id')] = 1 ;
                $this->zielgruppe[$sql->getValue('id')] = '' ;
              }
              $sql->next() ;
            }
          }
          else
            echo('<font color="red">Fehler beim Holen der Anzahl Sprachen</font><br />' . $sql->getError()) ;
        }
      }
      else
        echo('<font color="red">Fehler beim Holen der lokalisierten Daten</font><br />' . $sql->getError()) ;
      
      //ReferentInnen
      $querySemRefis = 'SELECT person_id FROM ' . \rex::getTablePrefix() . 'skh3_refis WHERE seminar_id=' . $this->seminar_id ;
      if($sql->setQuery($querySemRefis))
      {
        for($i=0; $i<$sql->getRows(); $i++)
        {
          $this->refis[$i] = $sql->getValue('person_id') ;
          $sql->next() ;
        }
      }
      else
        echo('<font color="red">Fehler beim Holen der ReferentInnen</font><br />' . $sql->getError()) ;
      
      //Leitung
      $querySemLeitung = 'SELECT person_id FROM ' . rex::getTablePrefix() . 'skh3_leitung WHERE seminar_id=' . $this->seminar_id ;
      if($sql->setQuery($querySemLeitung))
      {
        for($i=0; $i<$sql->getRows(); $i++)
        {
          $this->leitung[$i] = $sql->getValue('person_id') ;
          $sql->next() ;
        }
      }
      else
        echo('<font color="red">Fehler beim Holen der Leitung</font><br />' . $sql->getError()) ;
      
      //Verantwortung
      $querySemVerantwortung = 'SELECT person_id FROM ' . \rex::getTablePrefix() . 'skh3_verantwortung WHERE seminar_id=' . $this->seminar_id ;
      if($sql->setQuery($querySemVerantwortung))
      {
        for($i=0; $i<$sql->getRows(); $i++)
        {
          $this->verantwortung[$i] = $sql->getValue('person_id') ;
          $sql->next() ;
        }
      }
      else
        echo('<font color="red">Fehler beim Holen der Verantwortlichen</font><br />' . $sql->getError());
      
      //Kooperationspartner
      $querySemPartner = 'SELECT partner_id FROM ' . \rex::getTablePrefix() . 'skh3_koop WHERE seminar_id=' . $this->seminar_id ;
      if($sql->setQuery($querySemPartner))
      {
        for($i=0; $i<$sql->getRows(); $i++)
        {
          $this->partner[$i] = $sql->getValue('partner_id') ;
          $sql->next() ;
        }
      }
      else
        echo('<font color="red">Fehler beim Holen der Kooperationspartner</font><br />'.$sql->getError()) ;
      
      //Geldgeber
      $querySemGeldgeber = 'SELECT geldgeber_id FROM ' . rex::getTablePrefix() . 'skh3_foerdern WHERE seminar_id='.$this->seminar_id ;
      if($sql->setQuery($querySemGeldgeber))
      {
        for($i=0; $i<$sql->getRows(); $i++)
        {
          $this->geldgeber[$i] = $sql->getValue('geldgeber_id') ;
          $sql->next() ;
        }
      }
      else
        echo('<font color="red">Fehler beim Holen der Geldgeber</font><br />' . $sql->getError()) ;
    }
    else
    {
      $this->seminar_id = null ;
      $this->seminar_start = null ;
      $this->seminar_ende = null ;
      $this->seminar_ort = null ;
      $this->seminar_typ = 0 ;
      $this->seminar_online = 'offline' ;
      //Lokalisierungen für alle verfügbaren Sprachen
      $sql = \rex_sql::factory() ;
      $queryClang = 'SELECT id FROM ' . \rex::getTablePrefix() . 'clang;' ;
      if($sql->setQuery($queryClang))
      {
        for($i = 0; $i < $sql->getRows(); $i++)
        {
          $this->clang[$i] = $sql->getValue('id') ;
          $this->titel[$i] = '' ;
          $this->untertitel[$i] = '' ;
          $this->beschreibung[$i] = '' ;
          $this->kosten[$i] = 0 ;
          $this->waehrung_id[$i] = 1 ;
          $this->zielgruppe[$i] = '' ;
          $sql->next() ;
        }
      }
      else
        echo('<font color="red">Fehler beim Holen der Anzahl Sprachen</font><br />' . $sql->getError()) ;
    }
  }

  //Getter
  public function getSeminarID(){ return $this->seminar_id ; }
  public function getSeminarStart(){ return $this->seminar_start ; }
  public function getSeminarEnde(){ return $this->seminar_ende ; }
  public function getSeminarOrt(){ return $this->seminar_ort ; }
  public function getSeminarTyp(){ return $this->seminar_typ ; }
  public function getStatus(){ return $this->seminar_online ; }
  public function getClang($i){ return $this->clang[$i] ; }
  public function getClangCount(){ return count($this->clang) ; }
  public function getTitel($i){ return $this->titel[$i] ; }
  public function getUntertitel($i){ return $this->untertitel[$i] ; }
  public function getBeschreibung($i){ return $this->beschreibung[$i] ; }
  public function getKosten($i){ return $this->kosten[$i] ; }
  public function getWaehrung($i){ return $this->waehrung[$i] ; }
  public function getZielgruppe($i){ return $this->zielgruppe[$i] ; }
  //Komplete Arrays zurückgeben, z.B. um Seminar zu kopieren
  //lok
  public function getAllClang(){ return $this->clang;}
  public function getAllTitel(){ return $this->titel ; }
  public function getAllUntertitel(){ return $this->untertitel ; }
  public function getAllBeschreibung(){ return $this->beschreibung ; }
  public function getAllKosten(){ return $this->kosten ; }
  public function getAllWaehrung(){ return $this->waehrung ; } //Alle Währungen zurückgeben
  public function getAllZielgruppe(){ return $this->zielgruppe ; }

  //pers, Partner, Geldgeber
  public function getAllRefis(){ return $this->refis ; } //gibt Array mit allen Refis zurück
  public function getAllLeitung(){ return $this->leitung ; } //gibt Array mit allen Leitungspersonen zurück
  public function getAllVerantwortung(){ return $this->verantwortung ; }//Array mit allen verantwortlichen
  public function getAllPartner(){ return $this->partner ; } //Array mit allen Partnern
  public function getAllGeldgeber(){ return $this->geldgeber ; } //Array mit allen Geldgebern
  
  //Setter
  public function setSeminarID($seminar_id){$this->seminar_id = $seminar_id ; }
  public function setSeminarStart($start){$this->seminar_start = $start ; }
  public function setSeminarEnde($ende){$this->seminar_ende = $ende ; }
  public function setSeminarOrt($ort){$this->seminar_ort = $ort ; }
  public function setSeminarTyp($typ_id){$this->seminar_typ = $typ_id ; }
  public function setStatus($status){$this->seminar_online = $status ; }
  public function setClang($i, $clang){$this->clang[$i] = $clang ; } 
  public function setTitel($i, $titel){$this->titel[$i] = $titel ; }
  public function setUntertitel($i, $untertitel){$this->untertitel[$i] = $untertitel ; }
  public function setBeschreibung($i, $beschreibung){$this->beschreibung[$i] = $beschreibung ; }
  public function setKosten($i, $kosten){$this->kosten[$i] = $kosten ; }
  public function setWaehrung($i, $waehrung){$this->waehrung[$i] = $waehrung ; }
  public function setZielgruppe($i, $zielgruppe){$this->zielgruppe[$i] = $zielgruppe ; }
  public function setRefi($i, $person_id){$this->refis[$i] = $person_id ; }
  public function setLeitung($i, $person_id){$this->leitung[$i] = $person_id ; }
  public function setVerantwortung($i, $person_id){$this->verantwortung[$i] = $person_id ; }
  public function setPartner($i, $partner){$this->partner[$i] = $partner_id ; }
  public function setGeldgeber($i, $geldgeber){$this->geldgeber[$i] = $geldgeber_id ; }
  //lok
  public function setAllClang($allClang){$this->clang = $allClang ; } 
  public function setAllTitel($allTitel){$this->titel = $allTitel ; }
  public function setAllUntertitel($allUntertitel){$this->untertitel = $allUntertitel ; }
  public function setAllBeschreibung($allBeschreibung){$this->beschreibung = $allBeschreibung ; }
  public function setAllKosten($allKosten){$this->kosten = $allKosten ; }
  public function setAllWaehrung($allWaehrung){$this->waehrung = $allWaehrung ; }
  public function setAllZielgruppe($allZielgruppe){$this->zielgruppe = $allZielgruppe ; }
  //pers, partner, cashcows
  public function setAllRefis($allRefis){$this->refis = $allRefis ; } //alle Refis auf einen Schlag
  public function setAllLeitung($allLeitung){$this->leitung = $allLeitung ; } //komplette Leitung augf einen Schlag
  public function setAllVerantwortung($allVerantwortung){$this->verantwortung = $allVerantwortung ; }//alle Verantwortlichen
  public function setAllPartner($allPartner){$this->partner = $allPartner ; } //alle Partner
  public function setAllGeldgeber($allGeldgeber){$this->geldgeber = $allGeldgeber ; } //alle Geldgeber
  /**
  * seminarSave()
  * @access  public
  * Speichert Seminar, Lokalisierte Informationen, Refis, Leitung, Verantwortliche und Partner
  * 
  * @return bool $result
  */
  public function seminarSave()
  {
    //DB-Objekt
    $result = true ; //Variable für Ergebnis
    $report = '' ;
    $queryStammdaten = '' ; //Query zum Speichern der Stammdaten
    $sql = \rex_sql::factory() ;
    $sql->setDebug = \rex::getProperty('debug') ;
    $queryStartTransaction = 'START TRANSACTION;' ;//Transaktion starten
    if($sql->setQuery($queryStartTransaction))
      $report .= '<br />Transaktion starten' ;
    else
    {
      $report .= '<br /><font color="red">Fehler beim ermitteln der Seminar_ID:</font>' . $sql->getError() ;
      $result = false ;
    }
    
    //Daten speichern
    if(empty($this->seminar_id))
    {
      echo('Neuer Eintrag') ;
      //höchste Typ-ID aus DB holen 
      $queryMaxID = 'SELECT MAX(seminar_id) AS max_id FROM ' . \rex::getTablePrefix() . 'skh3_seminare' ;
      if($sql->setQuery($queryMaxID))
      {
        $sql->getRow() ;
        $this->seminar_id = $sql->getValue('max_id') + 1 ; //seminar_id erhöhen
        $report .= '<br />Seminar_ID = '. $this->seminar_id ;
      }
      else
      {
        $report .= '<br /><font color="red">Fehler beim ermitteln der Seminar_ID:</font> ' . $sql->getError() ;
        $result = false ;
      }
      //Query für Stammdaten
      $queryStammdaten = 'INSERT INTO ' . \rex::getTablePrefix() . 'skh3_seminare (seminar_id, seminar_start,seminar_ende,seminar_ort,seminar_typ) VALUES (' . $this->seminar_id . ',\'' . $this->seminar_start . '\',\'' . $this->seminar_ende . '\',\'' . $this->seminar_ort . '\',' . $this->seminar_typ . ')' ;
    }
    else
    {
      //UPDATE-Query für Stammdaten
      $queryStammdaten = 'UPDATE ' . \rex::getTablePrefix() . 'skh3_seminare SET seminar_start=\'' . $this->seminar_start . '\',seminar_ende=\'' . $this->seminar_ende . '\',seminar_ort=\'' . $this->seminar_ort . '\',seminar_typ=' . $this->seminar_typ . ' WHERE seminar_id = ' . $this->seminar_id ;
      //bisherige Lokalisierungen löschen
      $queryDelOldSemLok = 'DELETE FROM ' . \rex::getTablePrefix() . 'skh3_seminare_lok WHERE seminar_id=' . $this->seminar_id ;
      if($sql->setQuery($queryDelOldSemLok))
      {
        $report .= '<br />bisherige Lokalisierungen entfernt' ;
      }
      else
      {
        $report .= '<br /><font color="red">Fehler: Entfernen bisheriger Lokalisierungen fehlgeschlagen:</font> ' . $sql->getError() ;
        $result = false ;
      }
      
      //bisherige Refis löschen
      $queryDelOldRefis = 'DELETE FROM ' . \rex::getTablePrefix() . 'skh3_refis WHERE seminar_id=' . $this->seminar_id ;
      if($sql->setQuery($queryDelOldRefis))
      {
        $report .= '<br />Bisherige ReferentInnen entfernt' ;
      }
      else
      {
        $report .= '<br /><font color="red">Fehler: Entfernen bisheriger ReferentInnen fehlgeschlagen: </font>' . $sql->getError() ;
        $result = false ;
      }
      
      //Leitung löschen
      $queryDelOldLeitung = 'DELETE FROM ' . \rex::getTablePrefix() . 'skh3_leitung WHERE seminar_id=' . $this->seminar_id ;
      if($sql->setQuery($queryDelOldLeitung))
      {
        $report .= '<br />Bisherige Leitung entfernt' ;
      }
      else
      {
        $report .= '<br /><font color="red">Fehler: Entfernen bisheriger Leitung fehlgeschlagen:</font> ' . $sql->getError() ;
        $result = false ;
      }
            
      //Verantwortliche löschen
      $queryDelOldVerantwortliche = 'DELETE FROM ' . \rex::getTablePrefix() . 'skh3_verantwortung WHERE seminar_id=' . $this->seminar_id ;
      if($sql->setQuery($queryDelOldVerantwortliche))
      {
        $report .= '<br />Bisherige Verantwortliche entfernt' ;
      }
      else
      {
        $report .= '<br /><font color="red">Fehler: Entfernen bisheriger Verantwortlicher fehlgeschlagen:</font> ' . $sql->getError() ;
        $result = false ;
      }
            
      //Partner löschen
      $queryDelOldPartner = 'DELETE FROM ' . \rex::getTablePrefix() . 'skh3_koop WHERE seminar_id=' . $this->seminar_id ;
      if($sql->setQuery($queryDelOldPartner))
      {
        $report .= '<br />Bisherige Partner entfernt' ;
      }
      else
      {
        $report .= '<br /><font color="red">Fehler: Entfernen bisheriger Partner fehlgeschlagen: </font>' . $sql->getError() ;
        $result = false ;
      }
      
      //Geldgeber löschen
      $queryDelOldGeldgeber = 'DELETE FROM ' . \rex::getTablePrefix() . 'skh3_foerdern WHERE seminar_id=' . $this->seminar_id ;
      if($sql->setQuery($queryDelOldGeldgeber))
      {
        $report .= '<br />Bisherige Geldgeber entfernt' ;
      }
      else
      {
        $report .= '<br /><font color="red">Fehler: Entfernen bisheriger Geldgeber fehlgeschlagen: </font>' . $sql->getError() ;
        $result = false ;
      }
    }
      
    //Stammdaten speichern
    //escape-Zeichen, Whitespaces behandeln
    $this->seminar_start = mysql_real_escape_string(trim($this->seminar_start)) ;
    $this->seminar_ende = mysql_real_escape_string(trim($this->seminar_ende)) ;
    $this->seminar_ort = htmlspecialchars(trim($this->seminar_ort),ENT_QUOTES,'UTF-8') ;
    //!!    //Funktionen für regexp: Datum, Email, Webseiten
    //auf leere Pflichtfelder prüfen
    if(empty($this->seminar_start))
    {
      $report .= '<br /><font color="red">Fehler: Datum für Seminarbeginn fehlt</font>' ;
      $result = false ;
    }
    if(empty($this->seminar_ende))
    {
      $report .= '<br /><font color="red">Fehler: Datum für Seminarende fehlt</font>' ;
      $result = false ;
    }
    if(empty($this->seminar_ort))
    {
      $report .= '<br /><font color="red">Fehler: Ort fehlt</font>' ;
      $result = false ;
    }
    
    //prüfen, ob DAtumsangaben richtiges Format haben
    if(!validateDate($this->seminar_start))
    {
      $report .= '<br /><font color="red">Fehler: Seminarbeginn muss Format YYYY-MM-DD haben</font>' ;
      $result = false ;
    }
    if(!validateDate($this->seminar_ende))
    {
      $report .= '<br /><font color="red">Fehler: Seminarende muss Format 20YY-MM-DD haben</font>' ;
      $result = false ;
    }
    //Prüfen, ob Seminarbeginn vor Ende liegt
    if(strcmp($this->seminar_start, $this->seminar_ende) > 0)
    {
      $report .= '<br /><font color="red">Fehler: Seminarende darf nicht vor Beginn liegen</font>' ;
      $result = false ;
    }
    //in DB speichern
    if($sql->setQuery($queryStammdaten))
      $report .= '<br />Stammdaten erfolgreich gespeichert' ;
    else
    {
      $report .= '<br /><font color="red">Fehler beim Speichern der Stammdaten: </font>' . $sql->getError() ;
      $result = false ;
    }

    //Lokalisiertes speichern
    //mindestens eine Sprache muss ausgefüllt werden
    $lokCount = false ;
    for($i = 0; $i < $this->langCount; $i++)
    {
      if(!empty($this->titel[$i]))//Wenn Titel nicht leer, alles speichern
      {
        $lokCount = true ;
        // !!! Prüfen, ob Pflichtfelder Inhalt haben 
        //Escapezeichen und whitespaces behandeln
        $this->titel[$i] = htmlspecialchars(trim($this->titel[$i]),ENT_QUOTES,'UTF-8') ;
        $this->untertitel[$i] = htmlspecialchars(trim($this->untertitel[$i]),ENT_QUOTES,'UTF-8') ;
        $this->beschreibung[$i] = htmlspecialchars(trim($this->beschreibung[$i]),ENT_QUOTES,'UTF-8') ;
        $this->zielgruppe[$i] = htmlspecialchars(trim($this->zielgruppe[$i]),ENT_QUOTES,'UTF-8') ;
        //TN-Beitrag auf Richtigkeit prüfen, muss float und >=0 sein
        if(!empty($this->kosten[$i]) && !preg_match("/^[0-9]{1,7}$/",$this->kosten[$i]))
        {
          $report .= '<br /><font color="red">Fehler: Teilnahmebeitrag für Sprache ' . $this->clang[$i] . ' muss ganze Zahl sein (z.B. 110)</font>' ;
          $result = false ;
        }
        else
          $this->kosten[$i] = intval($this->kosten[$i]) ;
        if(!empty($this->kosten[$i]) && $this->kosten[$i] < 0.0)
        {
          $report .= '<br /><font color="red">Fehler: Teilnahmebeitrag für Sprache ' . $this->clang[$i] . ' fehlt oder ist negativ</font>' ;
          $result = false ;
        }
        //DB-Query        
        $querySemLok = 'INSERT INTO ' . \rex::getTablePrefix() . 'skh3_seminare_lok (seminar_id, clang, titel, untertitel, beschreibung, kosten, waehrung_id, zielgruppe) VALUES (' . $this->seminar_id . ',' . $this->clang[$i] . ',\'' . $this->titel[$i] . '\',\'' . $this->untertitel[$i] . '\',\'' . $this->beschreibung[$i] . '\',' . $this->kosten[$i] . ',' . $this->waehrung[$i] . ',\'' . $this->zielgruppe[$i] . '\')' ;
        //in DB speichern
        if($sql->setQuery($querySemLok))
          $report .= '<br />Lokalisierung für Sprache ' . $this->clang[$i] . ' erfolgreich gespeichert' ;
        else
        {
          $report .= '<br /><font color="red">Fehler beim Speichern der Lokalisierung:</font> ' . $sql->getError() ;
          $report .= '<br />'.$querySemLok ;
          $result = false ;
        }
      }
    }
    //Wenn keine Lokalisierung ausgefüllt wurde
    if(!$lokCount)
    {
      $report .= '<br /><font color="red">Fehler: mindestens eine Lokalisierung muss ausgefüllt werden</font>' ;
      $result = false ;
    }

    //Refis speichern
    for($i = 0; $i < count($this->refis); $i++)
    {
      //refi-query
      $querySemRefis = 'INSERT INTO ' . \rex::getTablePrefix() . 'skh3_refis (seminar_id, person_id) VALUES (' . $this->seminar_id . ',' . $this->refis[$i] . ')' ;
      //in DB speichern
      if($sql->setQuery($querySemRefis))
        $report .= '<br />ReferentInnen erfolgreich gespeichert' ;
      else
      {
        $report .= '<br /><font color="red">Fehler beim Speichern der ReferentInnen: </font>' . $sql->getError() ;
        $result = false ;
      }
    }
      
    //Leitung speichern
    for($i = 0; $i < count($this->leitung); $i++)
    {
      //leitungs-query
      $querySemLeitung = 'INSERT INTO ' . \rex::getTablePrefix() . 'skh3_leitung (seminar_id, person_id) VALUES (' . $this->seminar_id . ',' . $this->leitung[$i] . ')' ;
      //in DB speichern
      if($sql->setQuery($querySemLeitung))
        $report .= '<br />Leitungsteam erfolgreich gespeichert' ;
      else
      {
        $report .= '<br /><font color="red">Fehler beim Speichern des Leitungsteams:</font> ' . $sql->getError() ;
        $result = false ;
      }
    }
      
    //Verantwortliche speichern
    for($i = 0; $i < count($this->verantwortung); $i++)
    {
      //verntwortungs-query
      $querySemVerantwortung = 'INSERT INTO ' . \rex::getTablePrefix() . 'skh3_verantwortung (seminar_id, person_id) VALUES (' . $this->seminar_id . ',' . $this->verantwortung[$i] . ')' ;
      //in DB speichern
      if($sql->setQuery($querySemVerantwortung))
        $report .= '<br />Verantwortliche erfolgreich gespeichert' ;
      else
      {
        $report .= '<br />Fehler beim Speichern der Verantwortlichen: ' . $sql->getError() ;
        $result = false ;
      }
    }
      
    //Partner speichern
    for($i = 0; $i < count($this->partner); $i++)
    {
      //partner-query
      $querySemPartner = 'INSERT INTO ' . \rex::getTablePrefix() . 'skh3_koop (seminar_id, partner_id) VALUES (' . $this->seminar_id . ',' . $this->partner[$i] . ')' ;
      //in DB speichern
      if($sql->setQuery($querySemPartner))
        $report .= '<br />Partner erfolgreich gespeichert' ;
      else
      {
        $report .= '<br /><font color="red">Fehler beim Speichern der Partner:</font> ' . $sql->getError() ;
        $result = false ;
      }
    }
    
    //Geldgeber speichern
    for($i = 0; $i < count($this->geldgeber); $i++)
    {
      //Cashcow-query
      $querySemGeldgeber = 'INSERT INTO ' . \rex::getTablePrefix() . 'skh3_foerdern (seminar_id, geldgeber_id) VALUES (' . $this->seminar_id . ',' . $this->geldgeber[$i] . ')' ;
      //in DB speichern
      if($sql->setQuery($querySemGeldgeber))
        $report .= '<br />Cashcows erfolgreich gespeichert' ;
      else
      {
        $report .= '<br /><font color="red">Fehler beim Speichern der Cashcows:</font> ' . $sql->getError() ;
        $result = false ;
      }
    }
    
    //Transaktion zum Abschluss bringen
    if($result)
    {
      $queryEndTransaction = 'COMMIT;' ;//Transaktion erfolgreich
      $report .= '<br />Transaktion erfolgreich' ;
    }
    else
    {
      $queryEndTransaction = 'ROLLBACK;' ;//Transaktion gescheitert
      $report .= '<br /><font color="red">Transaktion gescheitert:<font color="red"> ' . $sql->getError() ;
      $report .= '<br /><br /><font color="red"><strong>Bitte nutzern Sie den Zurück-Button des Browsers</strong><font color="red"> <br /><br />' ;
    }
    if($sql->setQuery($queryEndTransaction))
      $report .= '<br />auf PHP-Ebene alles gut';
    else
    {
      $report .= '<br />PHP-Ebene knirsch bumm: ' . $sql->getError() ;
      $report .= '<br /><br /><font color="red"><strong>Admin oder Entwickler informieren, Kopie der Fehlermeldung nicht vergessen</strong><font color="red"> <br /><br />' ;
      $result = false ;
    }
    echo $report ;
    return $result ;
  }

  /**
  * seminarChangeStatus()
  * @access  public
  * Ändert Status eines Seminars
  * 
  * @return bool
  */
  public function seminarChangeStatus()
  {
    //DB-Geraschel
    $sql = \rex_sql::factory();
    $sql->setDebug = \rex::getProperty('debug') ;
    //alten Status prüfen und ggf. ändern
    if($this->seminar_online == 'offline')
      $this->seminar_online = 'online' ;
    else
      $this->seminar_online = 'offline' ;
    //DB-Update-Query
    $queryStatus = 'UPDATE ' . \rex::getTablePrefix() . 'skh3_seminare SET seminar_online=\'' . $this->seminar_online . '\' WHERE seminar_id=' . $this->seminar_id ;
    if($sql->setQuery($queryStatus))
        echo('Seminarstatus geändert') ;
    else
    {
      echo('Fehler: \n' . $sql->getError() . '\nQuery: ' . $queryStatus) ;
      return false ;
    }
    return true ;
  }
  
  /**
  * seminarDelete()
  * @access  public
  * Löscht Seminar, lokalisierte Informationen, Refis, Leitung, Verantwortliche und Partner
  * 
  * @return bool
  */
  public function seminarDelete()
  {
    $sql = \rex_sql::factory();
    $sql->setDebug = \rex::getProperty('debug') ;
    $queryDelete = 'DELETE FROM ' . \rex::getTablePrefix() . 'skh3_seminare WHERE seminar_id=' . $this->seminar_id ;
    if($sql->setQuery($queryDelete))
        echo('Seminar gelöscht') ;
    else
    {
      echo('Fehler: \n' . $sql->getError() . '\nQuery: ' . $queryDelete) ;
      return false ;
    }
    return true ;
  }
}
?>
