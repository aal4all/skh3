<?php
  error_reporting('E_ALL');
  $Basedir = dirname(__FILE__);
  //Zugang zum AddOn
  $page = rex_request("page", "string","");
  //Zugang zu einer eventuellen Untermenü-Seite
  $subpage = rex_request("subpage", "string");
  //Unterscheidung, ob wir uns innerhalb des AddOns im Auflistungs- oder Editiermodus befinden
  $func = rex_request("func", "string");
  //$start = rex_request('start','int','');
  //$entry_id = rex_request('entry_id','int','');
  // $mode = rex_request('mode','string','');
    
  //Header inkludieren
  include_once $REX["INCLUDE_PATH"]."/layout/top.php";


  //Unterseiten mit Funktionalitäten
  $subpages = array(
  array("seminare", "Seminare"), //seminare auflisten
  array("seminare_alt", "Alte Seminare"),
  array("personen", "Personen"),
  array("partner", "Partner"),
  array("geldgeber", "Geldgeber"),
  array("seminartyp","Seminartypen"), //Seminartypen  
  );

  rex_title("Seminare", $subpages);


        switch($subpage) {
        case "seminare_alt":
                require $Basedir ."/seminare_alt.inc.php";
                break;
        case "seminartyp":
                require $Basedir ."/seminartyp.inc.php";
                break;
  case "personen":
    require $Basedir ."/personen.inc.php";
    break;
  case "partner":
    require $Basedir ."/partner.inc.php";
    break;
  case "geldgeber":
    require $Basedir ."/geldgeber.inc.php";
    break;
  default:
    require $Basedir ."/seminare.inc.php";
  }
  //footer inkludieren
  include_once $REX["INCLUDE_PATH"]."/layout/bottom.php";
?>
