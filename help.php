<?php
/**
 * Chronolabs IP Lookup's REST API File
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://labs.coop
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         lookups
 * @since           1.1.2
 * @author          Simon Roberts <meshy@labs.coop>
 * @version         $Id: index.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		api
 * @description		Internet Protocol Address Information API Service REST
 */

	global $domain, $protocol, $business, $entity, $contact, $referee, $peerings, $source;
	
	if (strlen($theip = whitelistGetIP(true))==0)
		$theip = "127.0.0.1";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta property="og:title" content="IP Lookup's API Services"/>
<meta property="og:type" content="api"/>
<meta property="og:image" content="https://icons.ringwould.com.au/trusting/apple-touch-icon-114x114.png"/>
<meta property="og:url" content="<?php echo (isset($_SERVER["HTTPS"])?"https://":"http://").$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>" />
<meta property="og:site_name" content="<?php echo $business; ?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="rating" content="general" />
<meta http-equiv="author" content="wishcraft@users.sourceforge.net" />
<meta http-equiv="copyright" content="<?php echo $business; ?> &copy; <?php echo date("Y"); ?>" />
<meta http-equiv="generator" content="Chronolabs Cooperative (AUS)" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IP Lookup's API || <?php echo $business; ?></title>
<!-- AddThis Smart Layers BEGIN -->
<!-- Go to http://www.addthis.com/get/smart-layers to customize -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50f9a1c208996c1d"></script>
<script type="text/javascript">
  addthis.layers({
	'theme' : 'transparent',
	'share' : {
	  'position' : 'right',
	  'numPreferredServices' : 6
	}, 
	'follow' : {
	  'services' : [
		{'service': 'facebook', 'id': 'Chronolabs'},
		{'service': 'twitter', 'id': 'JohnRingwould'},
		{'service': 'twitter', 'id': 'ChronolabsCoop'},
		{'service': 'twitter', 'id': 'Cipherhouse'},
		{'service': 'twitter', 'id': 'OpenRend'},
	  ]
	},  
	'whatsnext' : {},  
	'recommended' : {
	  'title': 'Recommended for you:'
	} 
  });
</script>
<!-- AddThis Smart Layers END -->
<link rel="stylesheet" href="<?php echo $source; ?>/style.css" type="text/css" />
<link rel="stylesheet" href="https://css.ringwould.com.au/3/gradientee/stylesheet.css" type="text/css" />
<link rel="stylesheet" href="https://css.ringwould.com.au/3/shadowing/styleheet.css" type="text/css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script>
	var icoroite = 9966 * Math.random() + 7755;
	setTimeout(function() {
		jQuery.getJSON( "https://icons.ringwould.com.au/icons/java/<?php echo ($GLOBALS["domain"]=="ringwould.com.au"?"ringwould":"invader"); ?>/random.json", function( data ) {
			$.each(data, function( keyey, value ) {
				$( "#" + keyey ).href = value;
			});
		});
	}, icoroite);
</script>
<?php
	if ((!isset($_SESSION['icon-meta-html']) || empty($_SESSION['icon-meta-html'])) && session_id())
		$_SESSION['icon-meta-html'] = file_get_contents("https://icons.ringwould.com.au/icons/java/".($GLOBALS["domain"]=="ringwould.com.au"?"ringwould":"invader")."/random.html");
	if (isset($_SESSION['icon-meta-html']) && !empty($_SESSION['icon-meta-html']))
		echo $_SESSION['icon-meta-html'];
	else
		echo file_get_contents("https://icons.ringwould.com.au/icons/java/".($GLOBALS["domain"]=="ringwould.com.au"?"ringwould":"invader")."/random.html");
?>
</head>

<body>
<div class="main">
    <h1>IP Lookup's API Services -- <?php echo $business; ?></h1>
    <p>This is an API Service for conducting a locational search for a place. It provides the location of the IP address, in reference to country and city as well as a proximate longitude and latitude of the IP Address.</p>
    <p>We use a combination of the API Available from <a target="_blank" href="http://ipinfodb.com">http://ipinfodb.com</a> as well as our own region and locational database for longitude and latitude.</p>
    <h2>Examples of Calls (Using JSON)</h2>
    <p>There is a couple of calls to the API which I will explain.</p>
    <blockquote>For example if you want a call getting a city information you would :: <a href="<?php echo $source; ?>v1/city/<?php echo $theip; ?>/json.api" target="_blank"><?php echo $source; ?>v1/city/<?php echo $theip; ?>/json.api</a> or in a couple of hours you can use SSL <a href="https://lookup.labs.coop/v1/city/<?php echo $theip; ?>/json.api" target="_blank">https://lookups.labs.coop/v1/city/<?php echo $theip; ?>/json.api</a> which will return the city details of the IP Address of course there is a country data too which would be the following: <a href="<?php echo $source; ?>v1/country/<?php echo $theip; ?>/json.api" target="_blank"><?php echo $source; ?>v1/country/<?php echo $theip; ?>/json.api</a> or if you want to return either details on some form of netbios address you would do the following for example returning the country or city details of bluehost.com would be as follows: <a href="<?php echo $source; ?>v1/city/bluehost.com/json.api" target="_blank"><?php echo $source; ?>v1/city/bluehost.com/json.api</a>.<br/><br/>Of course there is a way of return from your current IP Address of route you would do the following for country or city information using the keyword <em>myself</em> instead of an IP Address or TLD/Subdomain to query on yourself! <a href="<?php echo $source; ?>v1/country/myself/json.api" target="_blank"><?php echo $source; ?>v1/country/myself/json.api</a> this for example will return your own source IP Address for the API information for country, for the city information you would subsitute <strong>country</strong> for <strong>city</strong>.</blockquote>
    <h2>API Services' Peers</h2>
    <p>This is the services the key is dupicated on when lodged for a multiple recover points and options!</p>
    <blockquote>
    	<ol>
    		<?php foreach($peerings as $domain => $peer) { 
    				if (!strpos($domain, $source)) {
    					?>				<li><a href="mailto:<?php echo $peer['contact']; ?>" target="_blank"><?php echo $peer['business']; ?></a> ~~ <a href="<?php echo $peer['protocol'] . $peer['domain']; ?>" target="_blank"><?php echo $peer['protocol'] . $peer['domain']; ?></a><?php if ($peer['ping']>1.0001) { ?> --- <em>ping <?php echo $peer['ping']; ?>ms</em><?php } else { ?> -- Unabled to Ping! <?php } ?></li>
    					
			<?php }	}?>
    	</ol>
    </blockquote>
    <h2>RAW Document Output</h2>
    <p>This is done with the <em>raw.api</em> extension at the end of the url, you replace the example address with either a place, an country either with no spaces in words or country ISO2 or ISO3 code and a name to search for the place on the api</p>
    <blockquote>
        <font color="#009900">This is for a locality country information of the ip <em>'<?php echo $theip; ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/country/<?php echo $theip; ?>/raw.api" target="_blank"><?php echo $source; ?>v1/country/<?php echo $theip; ?>/raw.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality city information of the ip <em>'<?php echo $theip; ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/city/<?php echo $theip; ?>/raw.api" target="_blank"><?php echo $source; ?>v1/city/<?php echo $theip; ?>/raw.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality country information of the ip of the hostname to the API <em>'<?php echo strtolower($_SERVER['HTTP_HOST']); ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/country/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/raw.api" target="_blank"><?php echo $source; ?>v1/country/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/raw.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality city information of the ip of the hostname to the API <em>'<?php echo strtolower($_SERVER['HTTP_HOST']); ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/city/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/raw.api" target="_blank"><?php echo $source; ?>v1/city/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/raw.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality country information of the ip of the caller to the API <em>'myself'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/country/myself/raw.api" target="_blank"><?php echo $source; ?>v1/country/myself/raw.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality city information of the ip of the caller to the API <em>'myself'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/city/myself/raw.api" target="_blank"><?php echo $source; ?>v1/city/myself/raw.api</a></strong></em><br /><br />
    </blockquote>
    <h2>HTML Document Output</h2>
    <p>This is done with the <em>html.api</em> extension at the end of the url, you replace the address with either a place, an country either with no spaces in words or country ISO2 or ISO3 code and a name to search for the place on the api</p>
    <blockquote>
         <font color="#009900">This is for a locality country information of the ip <em>'<?php echo $theip; ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/country/<?php echo $theip; ?>/html.api" target="_blank"><?php echo $source; ?>v1/country/<?php echo $theip; ?>/html.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality city information of the ip <em>'<?php echo $theip; ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/city/<?php echo $theip; ?>/html.api" target="_blank"><?php echo $source; ?>v1/city/<?php echo $theip; ?>/html.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality country information of the ip of the hostname to the API <em>'<?php echo strtolower($_SERVER['HTTP_HOST']); ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/country/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/html.api" target="_blank"><?php echo $source; ?>v1/country/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/html.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality city information of the ip of the hostname to the API <em>'<?php echo strtolower($_SERVER['HTTP_HOST']); ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/city/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/html.api" target="_blank"><?php echo $source; ?>v1/city/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/html.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality country information of the ip of the caller to the API <em>'myself'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/country/myself/html.api" target="_blank"><?php echo $source; ?>v1/country/myself/html.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality city information of the ip of the caller to the API <em>'myself'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/city/myself/html.api" target="_blank"><?php echo $source; ?>v1/city/myself/html.api</a></strong></em><br /><br />
		</blockquote>
    <h2>Serialisation Document Output</h2>
    <p>This is done with the <em>serial.api</em> extension at the end of the url, you replace the address with either a place, an country either with no spaces in words or country ISO2 or ISO3 code and a name to search for the place on the api</p>
    <blockquote>
         <font color="#009900">This is for a locality country information of the ip <em>'<?php echo $theip; ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/country/<?php echo $theip; ?>/serial.api" target="_blank"><?php echo $source; ?>v1/country/<?php echo $theip; ?>/serial.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality city information of the ip <em>'<?php echo $theip; ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/city/<?php echo $theip; ?>/serial.api" target="_blank"><?php echo $source; ?>v1/city/<?php echo $theip; ?>/serial.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality country information of the ip of the hostname to the API <em>'<?php echo strtolower($_SERVER['HTTP_HOST']); ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/country/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/serial.api" target="_blank"><?php echo $source; ?>v1/country/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/serial.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality city information of the ip of the hostname to the API <em>'<?php echo strtolower($_SERVER['HTTP_HOST']); ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/city/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/serial.api" target="_blank"><?php echo $source; ?>v1/city/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/serial.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality country information of the ip of the caller to the API <em>'myself'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/country/myself/serial.api" target="_blank"><?php echo $source; ?>v1/country/myself/serial.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality city information of the ip of the caller to the API <em>'myself'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/city/myself/serial.api" target="_blank"><?php echo $source; ?>v1/city/myself/serial.api</a></strong></em><br /><br />  </blockquote>
    <h2>JSON Document Output</h2>
    <p>This is done with the <em>json.api</em> extension at the end of the url, you replace the address with either a place, an country either with no spaces in words or country ISO2 or ISO3 code and a name to search for the place on the api</p>
    <blockquote>
                <font color="#009900">This is for a locality country information of the ip <em>'<?php echo $theip; ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/country/<?php echo $theip; ?>/json.api" target="_blank"><?php echo $source; ?>v1/country/<?php echo $theip; ?>/json.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality city information of the ip <em>'<?php echo $theip; ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/city/<?php echo $theip; ?>/json.api" target="_blank"><?php echo $source; ?>v1/city/<?php echo $theip; ?>/json.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality country information of the ip of the hostname to the API <em>'<?php echo strtolower($_SERVER['HTTP_HOST']); ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/country/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/json.api" target="_blank"><?php echo $source; ?>v1/country/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/json.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality city information of the ip of the hostname to the API <em>'<?php echo strtolower($_SERVER['HTTP_HOST']); ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/city/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/json.api" target="_blank"><?php echo $source; ?>v1/city/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/json.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality country information of the ip of the caller to the API <em>'myself'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/country/myself/json.api" target="_blank"><?php echo $source; ?>v1/country/myself/json.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality city information of the ip of the caller to the API <em>'myself'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/city/myself/json.api" target="_blank"><?php echo $source; ?>v1/city/myself/json.api</a></strong></em><br /><br /> </blockquote>
    <h2>XML Document Output</h2>
    <p>This is done with the <em>xml.api</em> extension at the end of the url, you replace the address with either a place, an country either with no spaces in words or country ISO2 or ISO3 code and a name to search for the place on the api</p>
    <blockquote>
        <font color="#009900">This is for a locality country information of the ip <em>'<?php echo $theip; ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/country/<?php echo $theip; ?>/xml.api" target="_blank"><?php echo $source; ?>v1/country/<?php echo $theip; ?>/xml.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality city information of the ip <em>'<?php echo $theip; ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/city/<?php echo $theip; ?>/xml.api" target="_blank"><?php echo $source; ?>v1/city/<?php echo $theip; ?>/xml.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality country information of the ip of the hostname to the API <em>'<?php echo strtolower($_SERVER['HTTP_HOST']); ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/country/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/xml.api" target="_blank"><?php echo $source; ?>v1/country/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/xml.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality city information of the ip of the hostname to the API <em>'<?php echo strtolower($_SERVER['HTTP_HOST']); ?>'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/city/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/xml.api" target="_blank"><?php echo $source; ?>v1/city/<?php echo strtolower($_SERVER['HTTP_HOST']); ?>/xml.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality country information of the ip of the caller to the API <em>'myself'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/country/myself/xml.api" target="_blank"><?php echo $source; ?>v1/country/myself/xml.api</a></strong></em><br /><br />
		<font color="#009900">This is for a locality city information of the ip of the caller to the API <em>'myself'</em></font><br/>
        <em><strong><a href="<?php echo $source; ?>v1/city/myself/xml.api" target="_blank"><?php echo $source; ?>v1/city/myself/xml.api</a></strong></em><br /><br />   </blockquote>
    <h2>PHP Example of getting clients IP Address</h2>
    <p>These is the best example in PHP for getting a client IP address. The function returning the true IP of the client browsing for the API in retrieving a key and generating one!</p>
    <blockquote>
		<pre>
/**
 * Get client IP
 *
 * Adapted from PMA_getIp() [phpmyadmin project]
 *
 * @param bool $asString requiring integer or dotted string
 * @return mixed string or integer value for the IP
 */
function getIP($asString = true)
{
	// Gets the proxy ip sent by the user
	$proxy_ip = '';
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else
		if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
		} else
			if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
				$proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
			} else
				if (!empty($_SERVER['HTTP_FORWARDED'])) {
					$proxy_ip = $_SERVER['HTTP_FORWARDED'];
				} else
					if (!empty($_SERVER['HTTP_VIA'])) {
						$proxy_ip = $_SERVER['HTTP_VIA'];
					} else
						if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
							$proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
						} else
							if (!empty($_SERVER['HTTP_COMING_FROM'])) {
								$proxy_ip = $_SERVER['HTTP_COMING_FROM'];
							}
	if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0)  {
		$the_IP = $regs[0];
	} else {
		$the_IP = $_SERVER['REMOTE_ADDR'];
	}
	$the_IP = ($asString) ? $the_IP : ip2long($the_IP);
	return $the_IP;
}
		</pre>
	</blockquote>
     <?php if (file_exists(API_FILE_IO_FOOTER) {
    	readfile(API_FILE_IO_FOOTER);
    }?>	
    <?php if (!in_array(whitelistGetIP(true), whitelistGetIPAddy())) { ?>
    <h2>Limits</h2>
    <p>There is a limit of <?php echo MAXIMUM_QUERIES; ?> queries per hour. You can add yourself to the whitelist by using the following form API <a href="http://whitelist.<?php echo domain; ?>/">Whitelisting form (whitelist.<?php echo domain; ?>)</a>. This is only so this service isn't abused!!</p>
    <?php } ?>
    <h2>The Author</h2>
    <p>This was developed by Simon Roberts in 2013 and is part of the Chronolabs System and api's.<br/><br/>This is open source which you can download from <a href="https://sourceforge.net/projects/chronolabsapis/">https://sourceforge.net/projects/chronolabsapis/</a> contact the scribe  <a href="mailto:wishcraft@users.sourceforge.net">wishcraft@users.sourceforge.net</a></p></body>
</div>
</html>
<?php 
