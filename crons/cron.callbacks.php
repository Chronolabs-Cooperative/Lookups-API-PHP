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

/**
 * Cron Scheduling Suggestion
 *
 * * / 3 * * * * /usr/bin/php -q /path/to/lookupsapi/cron/cron.callback.php
 * * / 3 * * * * /usr/bin/php -q /path/to/lookupsapi/cron/cron.callback.php
 * * / 3 * * * * /usr/bin/php -q /path/to/lookupsapi/cron/cron.callback.php
 *
 */

// The following Line Adjusts to the scheduling break period
sleep(mt_rand(0, 360));

/**
 * URI Path Finding of API URL Source Locality
 * @var unknown_type
 */
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'apiconfig.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'functions.php';

$result = $GLOBALS['APIDB']->queryF($sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('callbacks') . "` WHERE `fails` < 5 ORDER BY `id` ASC");
while ($row = $GLOBALS['APIDB']->fetchArray($result))
{
	$success = false;
	$data = json_decode($row['data'], true);
	$queries = json_decode($row['queries'], true);
	
	if (isset($queries['before']) && !empty($queries['before']))
		if (is_array($queries['before']))
			foreach($queries['before'] as $question)
				$GLOBALS['APIDB']->queryF($question);
		elseif (is_string($queries['before']))
			$GLOBALS['APIDB']->queryF($queries['before']);
	
	setTimeLimit($row['timeout']+$row['connection']+25);
			
	if (!function_exists("curl_init"))
	{
		if (strlen(file_get_contents($uri)) > 0)
			$success = true;
	} elseif (!$btt = curl_init($row['uri'])) {
		$success = false;
	} 
	if ($btt)
	{
		curl_setopt($btt, CURLOPT_HEADER, 0);
		curl_setopt($btt, CURLOPT_POST, (count($data)==0?false:true));
		if (count($data)!=0)
			curl_setopt($btt, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($btt, CURLOPT_CONNECTTIMEOUT, $row['connection']);
		curl_setopt($btt, CURLOPT_TIMEOUT, $row['timeout']);
		curl_setopt($btt, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($btt, CURLOPT_VERBOSE, false);
		curl_setopt($btt, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($btt, CURLOPT_SSL_VERIFYPEER, false);
		@curl_exec($btt);
		if (curl_getinfo($btt, CURLINFO_HTTP_CODE) == 200)
			$success = true;
		curl_close($btt);
	}
	if ($success != false)
	{

		if (isset($queries['success']) && !empty($queries['success']))
			if (is_array($queries['success']))
				foreach($queries['success'] as $question)
					$GLOBALS['APIDB']->queryF($question);
			elseif (is_string($queries['success']))
				$GLOBALS['APIDB']->queryF($queries['success']);
		$GLOBALS['APIDB']->queryF($sql = "DELETE FROM `" . $GLOBALS['APIDB']->prefix('callbacks') . "` WHERE `id` = '".$row['id']."' AND `uri` LIKE '".$row['uri']."'");
	} else {

		if (isset($queries['failed']) && !empty($queries['failed']))
			if (is_array($queries['failed']))
				foreach($queries['failed'] as $question)
					$GLOBALS['APIDB']->queryF($question);
			elseif (is_string($queries['failed']))
				$GLOBALS['APIDB']->queryF($queries['failed']);
			
		$GLOBALS['APIDB']->queryF($sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('callbacks') . "` SET `fails` = `fails` + 1 WHERE `id` = '".$row['id']."' AND `uri` LIKE '".$row['uri']."'");
	}
}
$GLOBALS['APIDB']->queryF($sql = "DELETE FROM `" . $GLOBALS['APIDB']->prefix('callbacks') . "` WHERE `fails` >= '5'");
$GLOBALS['APIDB']->queryF($sql = "COMMIT");
exit(0);
?>