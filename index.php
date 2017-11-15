<?php
define('HACKER_ATTACK', true);

include_once dirname(__FILE__) . '/initiate.php';
$recordListUrl = '/set/recordList.php?batchId=-1';
Header("HTTP/1.1 301 Moved Permanently");
Header("Cache-Control: no-cache");
Header("Location: $recordListUrl");
exit();