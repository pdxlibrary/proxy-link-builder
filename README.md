proxy-link-builder
==================

Simple Proxy Link Builder - Prepends persistent link with proxy prefix string

**PREREQUISITES**

 - jQuery

**USAGE**

Add the input form:

```html

<form id="proxy_link_form">
<blockquote>
<div id="proxy_link_result"></div>
URL: <input id="proxy_link_input" name="link" size="60" type="text" /><input type="submit" value="Create Link!" /></blockquote>
</form>

```

Add this javascript to the page with the input form:

```javascript

<script type="text/javascript">// <![CDATA[
$(document).ready(function() {
 $('#proxy_link_form').submit(function() {
  get_proxy_link();
  return false;
 });
});

function get_proxy_link()
{
  $.getJSON("/api/rest/proxy_link_builder/index.php?format=json&link="+$("#proxy_link_input").val(), function( json ) {
   $("#proxy_link_result").html('<a href="'+json.proxied_link+'" mce_href="'+json.proxied_link+'">'+json.proxied_link+'</a>');
 });
}
// ]]></script>

```

PHP Code used for generating the actual proxy link:

```php

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

```


