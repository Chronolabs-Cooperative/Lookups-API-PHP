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


global $GEOIP_REGION_NAME;

include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class'. DIRECTORY_SEPARATOR . 'domains.php';
include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class'. DIRECTORY_SEPARATOR . 'ip2location.php';
include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class'. DIRECTORY_SEPARATOR . 'timezone.php';
include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class'. DIRECTORY_SEPARATOR . 'geoip.inc.php';
include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class'. DIRECTORY_SEPARATOR . 'geoipcity.inc.php';
include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class'. DIRECTORY_SEPARATOR . 'geoipregionvars.php';

/**
 * xml_encode()
 * Encodes XML with DOMDocument Objectivity
 *
 * @param mixed $mixed					Mixed Data
 * @param object $domElement			DOM Element
 * @param object $DOMDocument			DOM Document Object
 * @return array
 */

if (!function_exists("getHTMLForm")) {
    /**
     * Get the HTML Forms for the API
     *
     * @param unknown_type $mode
     * @param unknown_type $clause
     * @param unknown_type $output
     * @param unknown_type $version
     *
     * @return string
     */
    function getHTMLForm($mode = '', $clause = '', $callback = '', $output = '', $version = 'v2')
    {
        if (empty($clause))
            $clause = substr(sha1($_SERVER['HTTP_USER_AGENT']), mt_rand(0,32), 9);
        
        $form = array();
        if (strpos(" ".API_METHODS, 'ipinfodb'))
        {
            $methods['city'] = 'IPInfoDB City';
            $methods['country'] = 'IPInfoDB Country';
        }
        if (strpos(" ".API_METHODS, 'geoip'))
        {
            if (strpos(" ".API_GEOIP_ENABLED, 'ipv4')||strpos(" ".API_GEOIP_ENABLED, 'ipv6'))
                $methods['geoip'] = 'GeoIP';
            if (strpos(" ".API_GEOIP_ENABLED, 'litecityv6')||strpos(" ".API_GEOIP_ENABLED, 'ipcityv4'))
                $methods['geocity'] = 'GeoIP City';
            if (strpos(" ".API_GEOIP_ENABLED, 'ipnetspeed') && strpos(" ".API_GEOIP_ENABLED, 'ipnetspeed')!=strpos(" ".API_GEOIP_ENABLED, 'ipnetspeedcell'))
                $methods['geonetspeed'] = 'GeoIP Network Speed';
            if (strpos(" ".API_GEOIP_ENABLED, 'ipnetspeedcell'))
                $methods['geonetspeedcell'] = 'GeoIP Cellular Network Speed';
            if (strpos(" ".API_GEOIP_ENABLED, 'iporg'))
                $methods['geoorg'] = 'GeoIP Organisation';
            if (strpos(" ".API_GEOIP_ENABLED, 'ipisp'))
                $methods['geoisp'] = 'GeoIP ISP';;
            if (strpos(" ".API_GEOIP_ENABLED, 'ipregion'))
                $methods['georegion'] = 'GeoIP Regional Data';
        }
        switch ($mode)
        {
            case "lookups":
                $form[] = "<form name=\"" . $clause . "\" method=\"POST\" enctype=\"multipart/form-data\" action=\"" . API_URL . '/v2/post/' .$clause . "/form.api\">";
                $form[] = "\t<table class='lookups-ipaddy-netbios' id='lookups-ipaddy-netbios' style='vertical-align: top !important; min-width: 98%;'>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 420px;'>";
                $form[] = "\t\t\t\t<label for='addresses'>Ipv4, IPv6, Netbios Network Addresses:<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold; float: right;'>*</font><br/>(Seperated by Carriage Return chr(13))</label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td colspan='2' style='padding: 9px'>";
                $form[] = "\t\t\t\t<textarea name='addresses' id='addresses' cols='44' rows='11'></textarea>&nbsp;&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='methods'>Data Methods to Return on Callback:<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold; float: right;'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td colspan='2' style='padding: 9px'>";
                foreach($methods as $method => $label) {
                $form[] = "\t\t\t\t<label for='method-".$method."'>".$label."</label>";
                $form[] = "\t\t\t\t<input type='checkbox' name='method[]' id='method-".$method."' value='".$method."' />&nbsp;&nbsp;";
                }
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='name'>Form Submission Return URL:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px'>";
                $form[] = "\t\t\t\t<input type='textbox' name='return' id='return' disabled='disabled' maxlen='198' size='41' value='" . API_URL . "' /><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='name'>Form Submission Callback URL:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px'>";
                $form[] = "\t\t\t\t<input type='textbox' name='callback' id='callback' disabled='disabled' maxlen='198' size='41' value='" . API_URL . "/v2/callback.api' /><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='name'>Submission Session Identity Hash:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding: 9px'>";
                $form[] = "\t\t\t\t<input type='textbox' name='session' id='session' disabled='disabled' maxlen='198' size='41' value='".(empty($clause)?'':$clause)."' /><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
                $form[] = "\t\t\t\t<input type='hidden' name='return' value='" . API_URL ."'>";
                $form[] = "\t\t\t\t<input type='hidden' name='callback' value='" . (empty($callback)?API_URL.'/v2/callback.api':$callback) ."'>";
                $form[] = "\t\t\t\t<input type='hidden' name='session' value='" . (empty($clause)?'':$clause) ."'>";
                $form[] = "\t\t\t\t<input type='submit' value='Do Lookups Via Callback' name='submit' style='padding:11px; font-size:122%;'>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td colspan='3' style='padding-top: 8px; padding-bottom: 14px; padding-right:35px; text-align: right;'>";
                $form[] = "\t\t\t\t<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold;'>* </font><font  style='color: rgb(10,10,10); font-size: 99%; font-weight: bold'><em style='font-size: 76%'>~ Required Field for Form Submission</em></font>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t</table>";
                $form[] = "</form>";
                break;
        }
        return implode("\n", $form);
    }
}


/**
 * validateEmail()
 * Validates an Email Address
 *
 * @param string $email
 * @return boolean
 */
function validateEmail($email) {
    if(preg_match("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.([0-9]{1,3})|([a-zA-Z]{2,3})|(aero|coop|info|mobi|asia|museum|name))$", $email)) {
        return true;
    } else {
        return false;
    }
}
/**
 * validateDomain()
 * Validates a Domain Name
 *
 * @param string $domain
 * @return boolean
 */
function validateDomain($domain) {
    if(!preg_match("/^([-a-z0-9]{2,100})\.([a-z\.]{2,8})$/i", $domain)) {
        return false;
    }
    return $domain;
}

/**
 * validateIPv4()
 * Validates and IPv6 Address
 *
 * @param string $ip
 * @return boolean
 */
function validateIPv4($ip) {
    if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE) === FALSE) // returns IP is valid
    {
        return false;
    } else {
        return true;
    }
}

/**
 * validateIPv6()
 * Validates and IPv6 Address
 *
 * @param string $ip
 * @return boolean
 */
function validateIPv6($ip) {
    if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === FALSE) // returns IP is valid
    {
        return false;
    } else {
        return true;
    }
}

if (!function_exists("whitelistGetIP")) {

	/* function whitelistGetIPAddy()
	 *
	* 	provides an associative array of whitelisted IP Addresses
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @return 		array
	*/
	function whitelistGetIPAddy() {
		return array_merge(whitelistGetNetBIOSIP(), file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist.txt'));
	}
}

if (!function_exists("whitelistGetNetBIOSIP")) {

	/* function whitelistGetNetBIOSIP()
	 *
	* 	provides an associative array of whitelisted IP Addresses base on TLD and NetBIOS Addresses
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @return 		array
	*/
	function whitelistGetNetBIOSIP() {
		$ret = array();
		foreach(file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist-domains.txt') as $domain) {
			$ip = gethostbyname($domain);
			$ret[$ip] = $ip;
		}
		return $ret;
	}
}

if (!function_exists("whitelistGetIP")) {

	/* function whitelistGetIP()
	 *
	* 	get the True IPv4/IPv6 address of the client using the API
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @param		$asString	boolean		Whether to return an address or network long integer
	*
	* @return 		mixed
	*/
	function whitelistGetIP($asString = true){
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
}



if (!function_exists("findDetails")) {

	/* function findDetails()
	 *
	 * 	Find the details on an IPv4/IPv6 Address
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 * 
	 * @param		$ip			string		Internet Protocol Address
	 * @param		$mode		string		IPInfoDB API Mode
	 * @param		$format		string		API Output mode (JSON, XML, SERIAL, HTML, RAW)
	 *
	 * @return 		array
	 */
	function findDetails($ip = '127.0.0.1', $mode = "city", $format = 'json')
	{
	    global $GEOIP_REGION_NAME;
	    
	    $methods = array();
	    if (strpos(" ".API_METHODS, 'ipinfodb'))
	    {
	        $methods['city'] = 'city';
	        $methods['country'] = 'country';
	    }
	    if (strpos(" ".API_METHODS, 'geoip'))
	    {
	        if (strpos(" ".API_GEOIP_ENABLED, 'ipv4')||strpos(" ".API_GEOIP_ENABLED, 'ipv6'))
	            $methods['geoip'] = 'geoip';
            if (strpos(" ".API_GEOIP_ENABLED, 'litecityv6')||strpos(" ".API_GEOIP_ENABLED, 'ipcityv4'))
                $methods['geocity'] = 'geocity';
            if (strpos(" ".API_GEOIP_ENABLED, 'ipnetspeed') && strpos(" ".API_GEOIP_ENABLED, 'ipnetspeed')!=strpos(" ".API_GEOIP_ENABLED, 'ipnetspeedcell'))
                $methods['geonetspeed'] = 'geonetspeed';
            if (strpos(" ".API_GEOIP_ENABLED, 'ipnetspeedcell'))
                $methods['geonetspeedcell'] = 'geonetspeedcell';
            if (strpos(" ".API_GEOIP_ENABLED, 'iporg'))
                $methods['geoorg'] = 'geoorg';
            if (strpos(" ".API_GEOIP_ENABLED, 'ipisp'))
                $methods['geoisp'] = 'geoisp';;
            if (strpos(" ".API_GEOIP_ENABLED, 'ipregion'))
                $methods['georegion'] = 'georegion';
	    }
	    
	    if (!in_array($mode, $methods))
	        die("API Method not currently supported!");
	    
        if(!validateIPv4($ip) && !validateIPv6($ip) && validateDomain($ip))
        {
            $ip = gethostbyname($ip);
        }
        
	    $ret = array();
        switch($mode) {
            case "city":
            case "country":
        		$ip2local = new ip2location();
        		switch($mode) {
        			case "city":
        				$ret = $ip2local->getCountry($ip);
        				break;
        			case "country":
        				$ret = $ip2local->getCity($ip);
        				break;
        		}
            case "geoip":
                if (validateIPv4($ip))
                {
                    $gi = geoip_open(API_GEOIP_IPV4, GEOIP_STANDARD);
                    $ret['country']['iso'] = geoip_country_code_by_addr($gi, $ip);
                    $ret['country']['name'] = geoip_country_name_by_addr($gi, $ip);
                    geoip_close($gi);
                }
                if (validateIPv6($ip))
                {
                    $gi = geoip_open(API_GEOIP_IPV6, GEOIP_STANDARD);
                    $ret['country']['iso'] = geoip_country_code_by_addr_v6($gi, $ip);
                    $ret['country']['name'] = geoip_country_name_by_addr_v6($gi, $ip);
                    geoip_close($gi);
                }
                break;
            case "geocity":
                if (validateIPv4($ip))
                {
                    $gi = geoip_open(API_GEOIP_IPCITYV4, GEOIP_STANDARD);
                    $record = GeoIP_record_by_addr($gi, $ip);
                    $ret = (array)$record;
                    $ret['region']['name'] = $GEOIP_REGION_NAME[$record->country_code][$record->region];
                    geoip_close($gi);
                }
                if (validateIPv6($ip))
                {
                    $gi = geoip_open(API_GEOIP_LITECITYV6, GEOIP_STANDARD);
                    $record = GeoIP_record_by_addr_v6($gi, $ip);
                    $ret = (array)$record;
                    $ret['region']['name'] = $GEOIP_REGION_NAME[$record->country_code][$record->region];
                    geoip_close($gi);
                }
                break;
            case "geonetspeed":
                if (validateIPv4($ip))
                {
                    $gi = geoip_open(API_GEOIP_IPNETSPEED, GEOIP_STANDARD);
                    $netspeed = geoip_country_id_by_addr($gi, $ip);
                    if ($netspeed == GEOIP_UNKNOWN_SPEED) {
                        $ret[$ip] = "Unknown";
                    } else {
                        if ($netspeed == GEOIP_DIALUP_SPEED) {
                            $ret[$ip] = "Dailup";
                        } else {
                            if ($netspeed == GEOIP_CABLEDSL_SPEED) {
                                $ret[$ip] = "Cable/DSL";
                            } else {
                                if ($netspeed == GEOIP_CORPORATE_SPEED) {
                                    $ret[$ip] = "Corporate";
                                }
                            }
                        }
                    }
                    geoip_close($gi);
                } elseif (validateIPv6($ip))
                {
                    $ret[$ip] = false;
                }
                break;
            case "geonetspeedcell":
                if (validateIPv4($ip))
                {
                    $gi = geoip_open(API_GEOIP_IPNETSPEEDCELL, GEOIP_STANDARD);
                    $netspeed = geoip_name_by_addr($gi, $ip);
                    $ret[$ip] = $netspeed;
                    geoip_close($gi);
                } elseif (validateIPv6($ip))
                {
                    $ret[$ip] = false;
                }
                break;
            case "geoorg":
                if (validateIPv4($ip))
                {
                    $gi  = geoip_open(API_GEOIP_IPORG, GEOIP_STANDARD);
                    $ret[$ip] = geoip_org_by_addr($giorg, $ip);
                    geoip_close($gi);
                } elseif (validateIPv6($ip))
                {
                    $ret[$ip] = false;
                }
                break;
            case "geoisp":
                if (validateIPv4($ip))
                {
                    $gi  = geoip_open(API_GEOIP_IPISP, GEOIP_STANDARD);
                    $ret[$ip] = geoip_org_by_addr($giorg, $ip);
                    geoip_close($gi);
                } elseif (validateIPv6($ip))
                {
                    $ret[$ip] = false;
                }
                break;
            case "georegion":
                if (validateIPv4($ip))
                {
                    $gi = geoip_open(API_GEOIP_IPREGION, GEOIP_STANDARD);
                    list($countrycode, $region) = geoip_region_by_addr($gi, $ip);
                    $ret['region']['countrycode'] = $countrycode;
                    $ret['region']['region'] = $region;
                    $ret['region']['name'] = $GEOIP_REGION_NAME[$countrycode][$region];
                    geoip_close($gi);
                } elseif (validateIPv6($ip))
                {
                    $ret[$ip] = false;
                }
                break;
        }
        
		switch ($format) {
			case "html":
				$string = '';
				foreach($ret as $key => $values) {
					$string .= $key . ' [';
					$i=0;
					foreach($values as $keyb => $value) {
						$i++;
						$string .= ' { ';
						if (is_array($value)) {
							$string .= $keyb . '::';
							foreach($value as $keyc => $valueb) {
								$string .= (($i>0)?' - ':'') .  $keyc . ': ' . $valueb;
							}
						} else {
							$string .= $keyb . ': ' . $value;
						}
						$string .= ' }';
					}
					$string .= " ]".($format=='raw'?"\n":"<br/>");
				}
				return $string;
				break;
			default:
				return $ret;
		}
						
	}	
}
 

if (!class_exists("XmlDomConstruct")) {
	/**
	 * class XmlDomConstruct
	 *
	 * 	Extends the DOMDocument to implement personal (utility) methods.
	 *
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 */
	class XmlDomConstruct extends DOMDocument {

		/**
		 * Constructs elements and texts from an array or string.
		 * The array can contain an element's name in the index part
		 * and an element's text in the value part.
		 *
		 * It can also creates an xml with the same element tagName on the same
		 * level.
		 *
		 * ex:
		 * <nodes>
		 *   <node>text</node>
		 *   <node>
		 *     <field>hello</field>
		 *     <field>world</field>
		 *   </node>
		 * </nodes>
		 *
		 * Array should then look like:
		 *
		 * Array (
		 *   "nodes" => Array (
		 *     "node" => Array (
		 *       0 => "text"
		 *       1 => Array (
		 *         "field" => Array (
		 *           0 => "hello"
		 *           1 => "world"
		 *         )
		 *       )
		 *     )
		 *   )
		 * )
		 *
		 * @param mixed $mixed An array or string.
		 *
		 * @param DOMElement[optional] $domElement Then element
		 * from where the array will be construct to.
		 *
		 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
		 *
		 */
		public function fromMixed($mixed, DOMElement $domElement = null) {

			$domElement = is_null($domElement) ? $this : $domElement;

			if (is_array($mixed)) {
				foreach( $mixed as $index => $mixedElement ) {

					if ( is_int($index) ) {
						if ( $index == 0 ) {
							$node = $domElement;
						} else {
							$node = $this->createElement($domElement->tagName);
							$domElement->parentNode->appendChild($node);
						}
					}

					else {
						$node = $this->createElement($index);
						$domElement->appendChild($node);
					}

					$this->fromMixed($mixedElement, $node);

				}
			} else {
				$domElement->appendChild($this->createTextNode($mixed));
			}

		}
			
	}
}

?>