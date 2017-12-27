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
 * @copyright       Chronolabs Cooperative http://snails.email
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         lookups
 * @since           1.1.2
 * @author          Simon Roberts <meshy@snails.email>
 * @version         $Id: index.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		api
 * @description		Internet Protocol Address Information API Service REST
 */

	$methods = $typals = $ips = array();
	
	if (strlen($theip = whitelistGetIP(true))==0)
	    $theip = "127.0.0.1";
	
    $ips[$theip] = $theip;
    $ips[strtolower($_SERVER['HTTP_HOST'])] = strtolower($_SERVER['HTTP_HOST']);
    $ips['myself'] = 'myself';
	
    if (strpos(" ".API_GEOIP_ENABLED, ',city'))
	    $methods['city'] = 'This is for a locality country information of the ip <em>%s</em>';
	if (strpos(" ".API_GEOIP_ENABLED, 'country'))
	    $methods['country'] = 'This is for a locality city information of the ip <em>%s</em>';
    if (strpos(" ".API_GEOIP_ENABLED, 'ipv4') && strpos(" ".API_GEOIP_ENABLED, 'ipv6'))
        $methods['geoip'] = 'This retrieves the GeoIP Data for the IP of <em>%s</em>';
    if (strpos(" ".API_GEOIP_ENABLED, 'litecityv6') && strpos(" ".API_GEOIP_ENABLED, 'litecity'))
        $methods['geocity'] = 'This retrieves the GeoIP Cities Data for the ip of <em>%s</em>';
    if (strpos(" ".API_GEOIP_ENABLED, 'ipasnum') && strpos(" ".API_GEOIP_ENABLED, 'ipasnumv6'))
        $methods['geoenums'] = 'This retrieves the GeoIP Enumrations Data for the ip of <em>%s</em>';
    if (strpos(" ".API_GEOIP_ENABLED, 'ipasnum2') && strpos(" ".API_GEOIP_ENABLED, 'ipasnum2v6'))
        $methods['geoenums2'] = 'This retrieves the GeoIP Enumrations (version 2) Data for the ip of <em>%s</em>';
    if (strpos(" ".API_GEOIP_ENABLED, 'ipnetspeed') && strpos(" ".API_GEOIP_ENABLED, 'ipnetspeed')!=strpos(" ".API_GEOIP_ENABLED, 'ipnetspeedcell'))
        $methods['geonetspeed'] = 'This retrieves the GeoIP Network Speed for the ip of <em>%s</em>';
    if (strpos(" ".API_GEOIP_ENABLED, 'ipnetspeedcell'))
        $methods['geonetspeedcell'] = 'This retrieves the GeoIP Network Speed (Cellular) for the ip of <em>%s</em>';
    if (strpos(" ".API_GEOIP_ENABLED, 'iporg'))
        $methods['geoorg'] = 'This retrieves the GeoIP Organisation of ownership for the ip of <em>%s</em>';
    if (strpos(" ".API_GEOIP_ENABLED, 'ipisp'))
        $methods['geoisp'] = 'This retrieves the GeoIP Internet Service Provider data for the ip of <em>%s</em>';;
    if (strpos(" ".API_GEOIP_ENABLED, 'ipdomain'))
        $methods['geodomain'] = 'This retrieves the GeoIP Realms + Domains data for the ip of <em>%s</em>';;
    if (strpos(" ".API_GEOIP_ENABLED, 'ipregion'))
        $methods['georegion'] = 'This retrieves the GeoIP Regional Information for the ip of <em>%s</em>';
	
	$typals['raw'] = 'RAW';
	$typals['json'] = 'JSON';
	$typals['serial'] = 'Serialisation';
	$typals['xml'] = 'XML';
	$typals['html'] = 'HTML';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta property="og:title" content="<?php echo API_VERSION; ?>"/>
<meta property="og:type" content="api<?php echo API_TYPE; ?>"/>
<meta property="og:image" content="<?php echo API_URL; ?>/assets/images/logo_500x500.png"/>
<meta property="og:url" content="<?php echo (isset($_SERVER["HTTPS"])?"https://":"http://").$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>" />
<meta property="og:site_name" content="<?php echo API_VERSION; ?> - <?php echo API_LICENSE_COMPANY; ?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="rating" content="general" />
<meta http-equiv="<?php echo $place['iso2']; ?>thor" content="wishcraft@users.sourceforge.net" />
<meta http-equiv="copyright" content="<?php echo API_LICENSE_COMPANY; ?> &copy; <?php echo date("Y"); ?>" />
<meta http-equiv="generator" content="Chronolabs Cooperative (<?php echo $place['iso3']; ?>)" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo API_VERSION; ?> || <?php echo API_LICENSE_COMPANY; ?></title>
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
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/style.css" type="text/css" />
<!-- Custom Fonts -->
<link href="<?php echo API_URL; ?>/assets/media/Labtop/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Bold Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Superwide Boldish/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Thin/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Unicase/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/LHF Matthews Thin/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Life BT Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Life BT Bold Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite Normal/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/gradients.php" type="text/css" />
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/shadowing.php" type="text/css" />

</head>

<body>
<div class="main">
	<img style="float: right; margin: 11px; width: auto; height: auto; clear: none;" src="<?php echo API_URL; ?>/assets/images/logo_350x350.png" />
    <h1><?php echo API_VERSION; ?> -- <?php echo API_LICENSE_COMPANY; ?></h1>
    <p>This is an API Service for conducting a locational search for a place. It provides the location of the IP address, in reference to country and city as well as a proximate longitude and latitude of the IP Address.</p>
    <p>We use a combination of the GeoIP from <a href="http://www.maxmind.com" target="_blank">http://MaxMind.com</a> as well as the API Available from <a target="_blank" href="http://ipinfodb.com">http://ipinfodb.com</a> as well as our own region and locational database for longitude and latitude.</p>
    <h2>Examples of Calls (Using JSON)</h2>
    <p>There is a couple of calls to the API which I will explain.</p>
    <blockquote>For example if you want a call getting a city information you would :: <a href="<?php echo API_URL; ?>/v2/city/<?php echo $theip; ?>/json.api" target="_blank"><?php echo API_URL; ?>/v2/city/<?php echo $theip; ?>/json.api</a> or in a couple of hours you can use SSL <a href="<?php echo API_URL; ?>/v1/city/<?php echo $theip; ?>/json.api" target="_blank"><?php echo API_URL; ?>/v1/city/<?php echo $theip; ?>/json.api</a> which will return the city details of the IP Address of course there is a country data too which would be the following: <a href="<?php echo API_URL; ?>/v2/country/<?php echo $theip; ?>/json.api" target="_blank"><?php echo API_URL; ?>/v2/country/<?php echo $theip; ?>/json.api</a> or if you want to return either details on some form of netbios address you would do the following for example returning the country or city details of bluehost.com would be as follows: <a href="<?php echo API_URL; ?>/v2/city/bluehost.com/json.api" target="_blank"><?php echo API_URL; ?>/v2/city/bluehost.com/json.api</a>.<br/><br/>Of course there is a way of return from your current IP Address of route you would do the following for country or city information using the keyword <em>myself</em> instead of an IP Address or TLD/Subdomain to query on yourself! <a href="<?php echo API_URL; ?>/v2/country/myself/json.api" target="_blank"><?php echo API_URL; ?>/v2/country/myself/json.api</a> this for example will return your own source IP Address for the API information for country, for the city information you would subsitute <strong>country</strong> for <strong>city</strong>.</blockquote>
    <?php foreach ($typals as $type => $title) { ?>
    <h2><?php echo $title; ?> Document Output</h2>
    <p>This is done with the <em><?php echo $type; ?>.api</em> extension at the end of the url for the method of output with the API!</p>
    <blockquote>
    	<?php foreach($methods as $method => $caption)
    	{
    	    foreach($ips as $ipaddy)
    	    {?>
    	        <font class="help-title-text"><?php echo sprintf($caption, $ipaddy); ?></font><br/>
    	        <font class="help-url-example"><a href="<?php echo API_URL; ?>/v2/<?php echo $method; ?>/<?php echo $ipaddy; ?>/<?php echo $type; ?>.api" target="_blank"><?php echo API_URL; ?>/v2/<?php echo $method; ?>/<?php echo $ipaddy; ?>/<?php echo $type; ?>.api</a></font><br/>
<?php  	    }
    	}?>
    </blockquote>
    <?php } 
    $ua = substr(sha1($_SERVER['HTTP_USER_AGENT']), mt_rand(0,32), 9);
    ?>
    <h2>IP Lookups via Callback API</h2>
    <p>The following form is an example of the submission to the api for using a large number of IP addresses or NetBIOS Addresses seperated by a carriage return to query then pass back via callback from the API; to get this form you would import: <strong><a href='<?php echo API_URL . '/v2/html/' .$ua . "/form.api"; ?>'><?php echo API_URL . '/v2/html/' .$ua . "/form.api"; ?></a></strong></p>
    <blockquote>
    	<?php echo $form = getHTMLForm('lookups', $ua); ?>
    	<pre style="overflow: scroll; height: 520px;">
    		<?php echo htmlspecialchars($form); ?>
    	</pre>
    </blockquote>
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

    <h2>The Author</h2>
    <p>This was developed by Simon Roberts in 2017 and is part of the Chronolabs System and api's.<br/><br/>This is open source which you can download from <a href="https://sourceforge.net/projects/chronolabsapis/">https://sourceforge.net/projects/chronolabsapis/</a> contact the scribe  <a href="mailto:wishcraft@users.sourceforge.net">wishcraft@users.sourceforge.net</a></p></body>
</div>
</html>