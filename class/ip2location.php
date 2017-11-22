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
 * @subpackage		ipinfodb
 * @description		Internet Protocol Address Information API Service REST
 */

if (!class_exists("ip2location")) {
	
	/**
	 * IPInfoDB.com API Abstraction Class
	 *
	 * @author     Simon Roberts <meshy@labs.coop>
	 * @package    lookups
	 * @subpackage netbios
	 */
	final class ip2location {
		/*
		 * Errors Occured Array
		 */
		protected $errors = array();
		
		/*
		 * Service URI
		 */
		protected $service = 'api.ipinfodb.com';
		
		/*
		 * Version of array
		*/
		protected $version = 'v3';
	
		/*
		 * API Key
		 */
		protected $apiKey = API_IPINFODB_KEY;
	
		/*  
		 * function __construct()
		 * 
		 * 	API Info DB __constructor
		 *  
		 */
		public function __construct(){}
	
		/* 
		 * function __destruct()
		 * 
		 * @summary		API Info DB __constructor
		 *  
		 */
		public function __destruct(){}
	
		/* 
		 * function setKey()
		 * 
		 * 	Sets API Key 
		 * 
		 * @author		Simon Roberts (Chronolabs) simon@labs.coop
		 *  
		 * @param 		$key	string		API Key from ipinfodb.com
		 * 
		 * @return string
		 */
		public function setKey($key) {
			if(!empty($key)) $this->apiKey = $key;
		}
	
		/* 
		 * function getError()
		 * 
		 * 	Return API Errors
		 * 
		 * @author		Simon Roberts (Chronolabs) simon@labs.coop
		 *  
		 * @return 		string
		 */
		public function getError() {
			return implode("\n", $this->errors);
		}
	
		/* 
		 * function getCountry
		 * 
		 * @summary	API Country Responder
		 *  
		 * @param  	$host	string		Host of API
		 *  
		 * @return array
		 */
		public function getCountry($host) {
			return $this->getResult($host, 'ip-country');
		}
	
		/* 
		 * function getCity()
		 * 
		 * @summary	API City Responder
		 *  
		 * @param 	$host	string 	Host of API
		 * 
		 * @return array
		 */
		 public function getCity($host) {
			return $this->getResult($host, 'ip-city');
		}
	
		/*  
		 * function getResult
		 * 
		 * @summary	API Repsonse Stripper
		 *  		 *  
		 * @param 	$host	string	Host of API
		 * @param 	$name	string	Name Data for Query
		 * 
		 * @return array
		 */
		private function getResult($host, $name) {
			if ($host==='myself') {
				$ip = $this->getIP(true);
			} else
				$ip = @gethostbyname($host);
	
			if(preg_match('/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/', $ip)){
				$xml = @$this->getURL('http://' . $this->service . '/' . $this->version . '/' . $name . '/', array('key' => $this->apiKey, 'ip'=> $ip, 'format'=>'xml'), 'GET');
	
				try{
					$response = @new SimpleXMLElement($xml);
	
					foreach($response as $field=>$value){
						$result[(string)$field] = (string)$value;
					}
					$ret = array();
					unset($result['statusCode']);
					unset($result['statusMessage']);
					foreach($result as $key => $value) {
						switch ($key) {
							case "ipAddress":
								$ret['ip'] = $value;
								break;
							case "countryCode":
								$ret['country']['iso'] = $value;
								break;
							case "countryName":
								$ret['country']['name'] = ucwords(strtolower($value));
								break;
							case "regionName":
								$ret['location']['region'] = ucwords(strtolower($value));
								break;
							case "cityName":
								$ret['location']['city'] = ucwords(strtolower($value));
								break;
							case "zipCode":
								$ret['location']['postcode'] = $value;
								break;
							case "timeZone":
								$ret['location']['gmt'] = $value;
								break;
							case "latitude":
							case "longitude":
								$ret['location']['coordinates'][$key] = $value;
								break;
							case "key":
								$ret[$key] = $value;
								break;
										
						}
					}
					$ret['key'] = md5(implode('|', $ret));
					return $ret;
				}
				catch(Exception $e){
					$this->errors[] = $e->getMessage();
					return;
				}
			}
	
			$this->errors[] = '"' . $host . '" is not a valid IP address or hostname.';
			return;
		}
		
		/**
		 * function getURL()
		 * 
		 * @summary		Uses cURL to retreive an API Result
		 *
		 * @param 		$url		string		URL of API
		 * @param 		$params 	array		Array of Parameters for API
		 * @param 		$method 	string		method of exchange with API Service
		 *
		 * @author Simon Roberts (Chronolabs) simon@labs.coop
		 *
		 * @return string
		 */
		function getURL($url, $params, $method = 'GET') {
			if ($ch = curl_init()) {
				switch($method) {
					default:
					case 'GET':
						$url .= '?'.http_build_query($params);
						trigger_error('API GET Action: '.$url, E_NOTICE);
						break;
					case 'POST':
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
						trigger_error('API POST Action: '.$url. ' - Parameters:'.http_build_query($params), E_NOTICE);
				}
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_USERAGENT, '5.0/Mozilla - cURL Get Seed URL/PHP Version ' . PHP_VERSION);
				$data = curl_exec($ch);
				curl_close($ch);
				return $data;
			}
			return false;
		}
		

		/* function getIP()
		 *
		 * 	get the True IPv4/IPv6 address of the client using the API
		 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
		 *
		 * @param		$asString	boolean		Whether to return an address or network long integer
		 *
		 * @return 		mixed
		 */
		public static function getIP($asString = true){
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
	
}
?>
