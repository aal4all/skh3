<?php

namespace skh3 ;
  
/*
 * validateDate
 * prüft, ob Datumsstring korrekt angegeben wurde
 * 
 * @access  public
 * 
 * @param string $datum
 * @return bool
 */
function validateDate($datum)
{
  if(preg_match("/^20[0-9]{2}-(0[0-9]|1[012])-([012][0-9]|3[01])$/", $datum))
    return true;
  else
    return false;           
}
      
/*
 * validateUrl()
 * Prüft URL auf Richtigkeit
 * 
 * @access public
 * 
 * @param string $url URL
 * @return bool
 */
function validateUrl($url) 
{
  //muss mit http(s):// beginnen: ^(https?):\/\/
  //dann Buchstaben, ziffern, "_""-": ([A-Z0-9_\-]+)
  //"." und wieder Buchstaben oder Ziffern, das ganze ruhig mehrmals: (\.[A-Z0-9_\-]+)+
  //keine Leerzeichen, aber Parameterübergabe erlauben: ([\S,:\/\.\?=A-Z0-9_\-]+)
  //Case insensitive: /i
  if(preg_match("/^(https?):\/\/([A-Z0-9_\-]+)(\.[A-Z0-9_\-]+)+([\S,:\/\.\?=A-Z0-9_\-]+)/i", $url))
    return true;
  else 
    return false;
}

/*
 * validateEmail()
 * prüft Email-Addressen
 * 
 * @access public
 * 
 * @param string $email Email-Adresse
 * @return bool
 */
function validateEmail($email)
{
  if(preg_match("/^[a-z0-9!#\$%&'\*\+\/=\?\^_`\{\|\}~-]+(?:\.[a-z0-9!#\$%&'\*\+\/=\?\^_`\{\|\}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[a-zA-Z]{2}|com|org|net|edu|gov|mil|biz|info|mobi|name|aero|asia|jobs|museum)$/", $email))
    return true;
  else
    return false;
}
        
/*
 * date_mask_de($datum)
 * macht das Datum schön
 * 
 * @param string $datum 
 * @param string $lang, Sprache (de,pl,...)
 * return string
 */
function date_mask($datum, $lang) 
{
  $monate = array //Monate auf Deutsch und Polnisch, erweiterbar
  (
    'jan'=>array('de'=>'Januar','pl'=>'stycznia','en'=>'January'),
    'feb'=>array('de'=>'Februar','pl'=>'lutego','en'=>'February'),
    'mar'=>array('de'=>'M&auml;rz','pl'=>'marca','en'=>'March'),
    'apr'=>array('de'=>'April','pl'=>'kwietnia','en'=>'April'),
    'mai'=>array('de'=>'Mai','pl'=>'maja','en'=>'May'),
    'jun'=>array('de'=>'Juni','pl'=>'czerwca','en'=>'June'),
    'jul'=>array('de'=>'Juli','pl'=>'lipca','en'=>'July'),
    'aug'=>array('de'=>'August','pl'=>'sierpnia','en'=>'August'),
    'sep'=>array('de'=>'September','pl'=>'wrze&#347;nia','en'=>'September'),
    'okt'=>array('de'=>'Oktober','pl'=>'pa&#380;dziernika','en'=>'October'),
    'nov'=>array('de'=>'November','pl'=>'listopada','en'=>'November'),
    'dez'=>array('de'=>'Dezember','pl'=>'grudnia','en'=>'December'),
  );
  $datum_neu = explode("-",$datum);
  switch($datum_neu[1]) {
    case "01":
      $datum_neu[1] = $monate['jan'][$lang];
      break;
    case "02":
      $datum_neu[1] = $monate['feb'][$lang];
      break;
    case "03":
      $datum_neu[1] = $monate['mar'][$lang];
      break;
    case "04":
      $datum_neu[1] = $monate['apr'][$lang];
      break;
    case "05":
      $datum_neu[1] = $monate['mai'][$lang];
      break;
    case "06":
      $datum_neu[1] = $monate['jun'][$lang];
      break;
    case "07":
      $datum_neu[1] = $monate['jul'][$lang];
      break;
    case "08":
      $datum_neu[1] = $monate['aug'][$lang];
      break;
    case "09":
      $datum_neu[1] = $monate['sep'][$lang];
      break;
    case "10":
      $datum_neu[1] = $monate['okt'][$lang];
      break;
    case "11":
      $datum_neu[1] = $monate['nov'][$lang];
      break;
    case "12":
      $datum_neu[1] = $monate['dez'][$lang];
      break;
  }
  if($datum_neu[2] != "00")
    $datum_neu[2] = $datum_neu[2] . ". ";
  else
    $datum_neu[2] = "";
  return($datum_neu);
}
?>



?> 
