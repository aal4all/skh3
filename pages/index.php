<?php
  error_reporting('E_ALL');

  $addon = rex_addon::get('skh3');

  
//  $Basedir = dirname(__FILE__);
  //Zugang zum AddOn
//  $page = rex_request("page", "string","");
  //Zugang zu einer eventuellen Untermenü-Seite
//  $subpage = rex_request("subpage", "string");
  //Unterscheidung, ob wir uns innerhalb des AddOns im Auflistungs- oder Editiermodus befinden
//  $func = rex_request("func", "string");
  //$start = rex_request('start','int','');
  //$entry_id = rex_request('entry_id','int','');
  // $mode = rex_request('mode','string','');
    
  //Header inkludieren
//  include_once $REX["INCLUDE_PATH"]."/layout/top.php";


  //Unterseiten mit Funktionalitäten
//  $subpages = array(
//  array("seminare", "Seminare"), //seminare auflisten
//  array("seminare_alt", "Alte Seminare"),
//  array("personen", "Personen"),
//  array("partner", "Partner"),
//  array("geldgeber", "Geldgeber"),
//  array("seminartyp","Seminartypen"), //Seminartypen  
//  );

//  rex_title("Seminare", $subpages);
  echo rex_view::title(Seminare);
  // Die Subpages werden nicht mehr über den "subpage"-Parameter gesteuert, sondern über "page" (getrennt mit einem Slash, z. B. page=demo_addon/config)
  // Die einzelnen Teile des page-Pfades können mit der folgenden Funktion ausgelesen werden.
  $subpage = rex_be_controller::getCurrentPagePart(2);
  // Subpages können über diese Methode eingebunden werden. So ist sichergestellt, dass auch Subpages funktionieren,
  // die von anderen AddOns/Plugins hinzugefügt wurden
  rex_be_controller::includeCurrentPageSubPath();
  
  
  
  
//        switch($subpage) {
//        case "seminare_alt":
//                require $Basedir ."/seminare_alt.inc.php";
//                break;
//        case "seminartyp":
//                require $Basedir ."/seminartyp.inc.php";
//                break;
//  case "personen":
//    require $Basedir ."/personen.inc.php";
//    break;
//  case "partner":
//    require $Basedir ."/partner.inc.php";
//    break;
//  case "geldgeber":
//    require $Basedir ."/geldgeber.inc.php";
//    break;
//  default:
//    require $Basedir ."/seminare.inc.php";
//  }
  //footer inkludieren
//  include_once $REX["INCLUDE_PATH"]."/layout/bottom.php";
?>
