 <? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**/
session_start();
if (!isset($_SESSION['CITY'])) {
  

/**/
$ip=$_SERVER['REMOTE_ADDR'];
$url = 'http://ipgeobase.ru:7020/geo?ip='.$ip;
$BASE_URL = "http://query.yahooapis.com/v1/public/yql";
/*Вывод города*/
function curl_load($url)
{ 
  curl_setopt($ch=curl_init(), CURLOPT_URL, $url); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  $response = curl_exec($ch); 
  curl_close($ch); 
  return $response; 
} 


function parse($string)
{
		$data   = array();
		$pa     = array(
      "IP"        => '#<inetnum>(.*)</inetnum>#is',
      "COUNTRY"   => '#<country>(.*)</country>#is',
      "CITY"      => '#<city>(.*)</city>#is',
      "REGION"    => '#<region>(.*)</region>#is',
      "DISTRICT"  => '#<district>(.*)</district>#is',
      "LAT"       => '#<lat>(.*)</lat>#is',
      "LNG"       => '#<lng>(.*)</lng>#is',
      "MESSAGE"   => '#<message>(.*)</message>#is'
    );

		foreach($pa as $key => $pattern)
			if(preg_match($pattern, $string, $out))
				$data[$key] = iconv('windows-1251', 'UTF-8', trim($out[1]));
		return $data;
    
}
$result=parse(curl_load($url));
/*Вывод погоды*/  
$yql_query = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="'.$result["CITY"].'")';
$yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
$session = curl_init($yql_query_url);
curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
$json = curl_exec($session);

$phpObj =  json_decode($json);
if(!$phpObj==NULL)
{
  	$temp = $phpObj->query->results->channel->item->condition->temp;
	if($arParams["DISPLAY_TEMPERATURE_TYPE"] == "C")
	{
		$arResult["TEMPERATURE"] = round(($temp-32)/1.8)."°C";
	}
	else
	{
		$arResult["TEMPERATURE"] = $temp."°F";			
  	}
 

}
/*склонения*/
function to_prepositional($str) 
{   
  $replace = array();
  $replace['2'][] = array('ия','ии');
  $replace['2'][] = array('ия','ии');
  $replace['2'][] = array('ий','ом');
  $replace['2'][] = array('ое','ом');
  $replace['2'][] = array('ая','ой');
  $replace['2'][] = array('ль','ле');
  $replace['1'][] = array('а','е');
  $replace['1'][] = array('о','е');
  $replace['1'][] = array('и','ах');
  $replace['1'][] = array('ы','ах');
  $replace['1'][] = array('ь','и');
  
  foreach ($replace as $length => $replacement) {
    $str_length = mb_strlen($str, 'UTF-8');
    $find = mb_substr($str, $str_length - $length, $str_length, 'UTF-8');
    foreach($replacement as $try) {
      if ( $find == $try[0] ) {
        $str = mb_substr($str, 0, $str_length - $length, 'UTF-8');
        $str .= $try['1'];
        return $str;
      }
    }
  }
  if ($find == 'е') {
    return $str;
  } else {
    return $str.'е';
  }
  
}
$arResult["CITY"] = to_prepositional($result["CITY"]);

  $_SESSION['CITY'] = $arResult["CITY"];
  $_SESSION['TEMPERATURE'] = $arResult["TEMPERATURE"];
} else {
  $arResult['CITY'] = $_SESSION["CITY"];
  $arResult['TEMPERATURE'] = $_SESSION["TEMPERATURE"];
}
$this->IncludeComponentTemplate();
?>