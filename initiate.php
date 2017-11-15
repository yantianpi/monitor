<?php
if (!defined('HACKER_ATTACK')) {
	die('Hacking attempt');
}

include_once(dirname(__FILE__) . '/etc/const.php');

$objMysql = new PdoMysql(DB_NAME, DB_HOST, DB_USER, DB_PASS);
$smartyObj = new SmartyExt();
$pageObj = new Page();
?>