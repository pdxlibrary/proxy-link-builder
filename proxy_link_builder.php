<?php


// Processes $link to create a proxied version.     
$link = urldecode($_GET['link']);    
$link = str_replace("http:/","http://",$link);    
$link = str_replace("http:///","http://",$link);    
$link = str_replace("https:/","https://",$link);    
$link = str_replace("https:///","https://",$link);    

//print("submitted link: $link<br>\n");
// check for "http"
if(strcmp($link,'') && strcmp(substr($link,0,4),'http'))
  $link = "http://".$link;

// encode double quotes
$link = str_replace('"','%22',$link);

// check if link is already proxied (don't double-proxy)    
$link = str_replace("http://stats.lib.pdx.edu/proxy.php?url=","",$link);    
$link = str_replace("http://library.pdx.edu/plink_builder.html?link=","",$link);
if(strcmp($link,''))       
 $proxied_link = "http://stats.lib.pdx.edu/proxy.php?url=".$link;    
else
 $proxied_link = "";  


switch($_GET['format'])
{
	case 'json':
		$result->proxied_link = $proxied_link;
		print(json_encode($result));
		break;
	case 'xml':
		 // output as xml
		$xml = new SimpleXMLElement("<?xml version=\"1.0\" standalone=\"yes\"?><Result></Result>");

		// TODO: 
		// if invalid url
		// $xml->addChild('Error','Invalid URL');

		$xml->addChild('ProxiedLink',htmlspecialchars(html_entity_decode($proxied_link, ENT_QUOTES, 'UTF-8'), ENT_NOQUOTES, "UTF-8"));

		print($xml->asXML());
		break;
	default: print("ERROR: valid output format required. (Example: format=json)\n");
}


exit();


?>
