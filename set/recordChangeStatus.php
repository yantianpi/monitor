<?php
define('HACKER_ATTACK', true);
$scriptStartTime = microtime(true);
include_once dirname(dirname(__FILE__)) . '/initiate.php';

$recordId = intval(Common::obtainVariable('id','get'));
$changeType = addslashes(trim(Common::obtainVariable('type', 'get')));
if(!empty($recordId) && !empty($changeType)){
    if ($changeType == 'delete'){
        $status = 'INACTIVE';
    }elseif ($changeType == 'recovery') {
        $status = 'ACTIVE';
    }
    $sql = "UPDATE record_list SET `Status` = '{$status}' WHERE `Id` = '{$recordId}'";
    $objMysql->query($sql);
    if($objMysql->getAffectedRows()){
        $url = "http://".$_SERVER['HTTP_HOST']."/set/recordList.php?batchId=-1";
        header("Location: $url");
        exit;
    }
}
$smartyObj->displayTpl('recordList.tpl');
