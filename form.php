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

	$parts = explode(".", microtime(true));
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	$salter = ((float)(mt_rand(0,1)==1?'':'-').$parts[1].'.'.$parts[0]) / sqrt((float)$parts[1].'.'.intval(cosh($parts[0])))*tanh($parts[1]) * mt_rand(1, intval($parts[0] / $parts[1]));
	header('Blowfish-salt: '. $salter);
	
	/**
	 * URI Path Finding of API URL Source Locality
	 * @var unknown_type
	 */
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'apiconfig.php';
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'functions.php';

	/**
	 * URI Path Finding of API URL Source Locality
	 * @var unknown_type
	 */
	$odds = $inner = array();
	foreach($_GET as $key => $values) {
	    if (!isset($inner[$key])) {
	        $inner[$key] = $values;
	    } elseif (!in_array(!is_array($values) ? $values : md5(json_encode($values, true)), array_keys($odds[$key]))) {
	        if (is_array($values)) {
	            $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
	        } else {
	            $odds[$key][$inner[$key] = $values] = "$values--$key";
	        }
	    }
	}
	
	foreach($_POST as $key => $values) {
	    if (!isset($inner[$key])) {
	        $inner[$key] = $values;
	    } elseif (!in_array(!is_array($values) ? $values : md5(json_encode($values, true)), array_keys($odds[$key]))) {
	        if (is_array($values)) {
	            $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
	        } else {
	            $odds[$key][$inner[$key] = $values] = "$values--$key";
	        }
	    }
	}
	
	foreach(parse_url('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'], '?')?'&':'?').$_SERVER['QUERY_STRING'], PHP_URL_QUERY) as $key => $values) {
	    if (!isset($inner[$key])) {
	        $inner[$key] = $values;
	    } elseif (!in_array(!is_array($values) ? $values : md5(json_encode($values, true)), array_keys($odds[$key]))) {
	        if (is_array($values)) {
	            $odds[$key][md5(json_encode($inner[$key] = $values, true))] = $values;
	        } else {
	            $odds[$key][$inner[$key] = $values] = "$values--$key";
	        }
	    }
	}
	$help=false;
	if ((!isset($inner['mode']) || empty($inner['mode'])) && (!isset($inner['ip']) || empty($inner['ip']))) {
		$help=true;
	} elseif (isset($inner['output']) || !empty($inner['output'])) {
		$mode = trim($inner['mode']);
		$session = trim($inner['session']);
		$output = trim($inner['output']);
	} else {
		$help=true;
	}
	
	if ($help==true) {
		header("Location: " . API_URL);
		exit;
	}
	
	if (function_exists("http_response_code"))
		http_response_code(200);
	
    switch($mode)
    {
        case 'html':
            echo $form = getHTMLForm('lookups', $session);
            break;
        case 'post':
            if (empty($inner['addresses']))
            {
                http_response_code(501);
                die("<h1>error missing variable</h1><p>The Variable _POST['addresses'] should contain a carriage return seperated list of IPv4, IPv6 and/or NetBIOS Addresses but is empty!</p>");
            }
            if (empty($inner['method'])||count($inner['method'])==0)
            {
                http_response_code(501);
                die("<h1>error missing variable</h1><p>The Variable _POST['method'][] open elemented array that contains the function name being processed for the callback!</p>");
            }
            if (empty($inner['return']))
            {
                http_response_code(501);
                die("<h1>error missing variable</h1><p>The Variable _POST['return'] should contain a URL that this function returns the post from!</p>");
            }
            if (empty($inner['callback']))
            {
                http_response_code(501);
                die("<h1>error missing variable</h1><p>The Variable _POST['callback'] should contain a URL that is called with this API returning the result to your website!</p>");
            }
            if (empty($inner['session']) && strlen($inner['session'])>128)
            {
                http_response_code(501);
                die("<h1>error missing variable</h1><p>The Variable _POST['session'] This should contain a session identified key or hash that is no more than 128 characters!</p>");
            }
            
            $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('questions') . "` (`session`, `callback`, `methods`, `created`) VALUES('" . $inner['session'] . "','" . $inner['callback'] . "','" . json_encode($inner['method']) . "', UNIX_TIMESTAMP())";
            if (!$GLOBALS['APIDB']->queryF($sql))
            {
                die("SQL Failed: $sql;");
            }
            $questionid = $GLOBALS['APIDB']->getInsertId();
            foreach(explode("\n", str_replace(ord(10), "", $inner['addresses'])) as $address)
            {
                if (!empty($addy = trim($address)))
                {
                    $type = '';
                    if ($type = '' && validateIPv4($addy))
                        $type = 'ipv4';
                    elseif ($type = '' && validateIPv6($addy))
                        $type = 'ipv6';
                    elseif ($type = '' && validateDomain($addy))
                        $type = 'realm';
                    if (!empty($type))
                    {
                        $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('queries') . "` (`question-id`, `type`, `value`, `method`, `todo`, `created`) VALUES('" . $questionid . "','" . $type . "','" . $addy . "','" . json_encode($inner['method']) . "','" . count($inner['method']) . "', UNIX_TIMESTAMP())";
                        if (!$GLOBALS['APIDB']->queryF($sql))
                        {
                            die("SQL Failed: $sql;");
                        }
                    }
                }
            }
            header('Location: ' . $inner['return']);
            break;
    }
?>
