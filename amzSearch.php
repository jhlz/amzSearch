<?
/****  amazon search **/
require_once 'res/simplexml.class.php';
// include('res/url_get_contents.php'); //for older php versions

function searchAmazon($country, $artistName, $albumName){
if ($country == "DE"){$country="de";}	
$accessKeyId = ""; // aws id
$secretKey = ""; // aws secret key
$associateTag = ""; // affiliate tag 

if ($albumName == ""){		
		$Keywords = $artistName;	
	}else{
		$Keywords = $artistName." ".$albumName;
	}

$Timestamp = gmdate("Y-m-d\TH:i:s\Z"); 
$ResponseGroup = "ItemAttributes";
$ResponseGroup = str_replace(",", "%2C", $ResponseGroup);
$Keywords = str_replace(" ", "+", $Keywords);
$Keywords = str_replace("&", " ", $Keywords);	

$params = "AWSAccessKeyId=$accessKeyId&
AssociateTag=$associateTag&
Keywords=$Keywords&
Operation=ItemSearch&
SearchIndex=MP3Downloads&
ItemPage=1&
ResponseGroup=$ResponseGroup&
Service=AWSECommerceService&
Timestamp=$Timestamp&
Version=2013-08-01";

$BaseUrl = "http://webservices.amazon.".$country."/onca/xml?";
$params= $BaseUrl . $params;
$params = str_replace("%7E", "~", $params);
$params = preg_replace('/[\r\n\" "]/', '', $params);
$params = preg_replace('/["  "]+/', ' ', $params);
$paramsArray = explode( '?', $params, 2);
$Parameters = explode("&", $paramsArray[1]);
asort($Parameters);
$NewString = implode("&", $Parameters);
$NewString = rawurlencode($NewString);
$NewString = str_replace("~", "%7E", $NewString);
$NewString = str_replace("%3D", "=", $NewString);
$NewString = str_replace("%26", "&", $NewString);
$NewString = str_replace("%FA", "%C3%BA", $NewString); // ú
$NewString = str_replace("%E9", "%C3%A9", $NewString); // é   
$NewString = str_replace("%F6", "%C3%B6", $NewString);  // ö
$NewString = str_replace("%E1", "%C3%A1", $NewString);  // á
$NewString = str_replace("%F3", "%C3%B3", $NewString);  // ó
$NewString = str_replace("%FC", "%C3%BC", $NewString);  // ü
$NewString = str_replace("%E4", "%C3%A4", $NewString);  // ä
$url = parse_url($paramsArray[0]);
$PrependString = "GET\n" .$url['host']. "\n" .$url['path']. "\n" .$NewString;
$Signature= base64_encode(hash_hmac('sha256', $PrependString, $secretKey, true)); 
$Signature = rawurlencode($Signature);
$SignedRequest = $paramsArray[0]. "?" . $NewString . "&Signature=" .$Signature;


$file = $SignedRequest;
$sxml = new simplexml;
$dataa = $sxml->xml_load_file($file);
$resCount = (int) $dataa->Items->TotalResults;
//$amz_err = $dataa->Items->Request->Errors->Error->Code;
$amazon_url = $dataa->Items->MoreSearchResultsUrl;

if ($resCount == 0){$res="empty";}											
elseif ($resCount > 0){$res= array($amazon_url);}	
else {$res="error";}
 
 return $res;

 }