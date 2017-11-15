<?php
define('HACKER_ATTACK', true);
include_once dirname(dirname(__FILE__)) . '/initiate.php';
$fileName = basename(__FILE__);

$recordId = intval(Common::obtainVariable('id','get'));
if(empty($recordId)) return;
$sql = "SELECT * 
        FROM `record_list`
        WHERE Id = '{$recordId}' and RunStatus = 'PENDING' and Status ='ACTIVE'";
$r = $objMysql->getFirstRow($sql);
if (empty($r)) {
    $content = "record({$recordId}) not found";
    Common::recordLog('OTHER','OTHER',$recordId, $fileName, 'recordTest Record Not Found', $content);
    echo "Error : 该条记录未找到！";
    exit();
}

$r['Content'] = json_decode($r['Content'], true);

if( isset($r['Content']['LoginId']['Value']) && !empty($r['Content']['LoginId']['Value']) ){
    //若果有loginId 则需要进行二次登录
    $LoginId = $r['Content']['LoginId']['Value'];
    $sql = "SELECT * 
            FROM `record_list`
            WHERE Id = '{$LoginId}' and Batch = 0 and RunStatus = 'PENDING' and Status ='ACTIVE'";
    $tmpRes = $objMysql->getFirstRow($sql);
    if (empty($tmpRes)) {
        $content = "loginId({$LoginId}) not found";
        Common::recordLog('RUN','RECORD',$recordId, $fileName, 'LoginId Not Found', $content);
        $r['LoginIdInfo'] = array();
    }
    $r['LoginIdInfo'] = json_decode($tmpRes['Content'], true);
}
$isTest = true;
$message = Custom::early_warning_processing($r,$isTest);
$errorMessage = !empty($message['alertMessage']) ? $message['alertMessage'] : '';
if(empty($errorMessage)){
    $errorMessage['Success'] = "配置项全部正常！";
}
$str ='';
foreach ($errorMessage as $k => $v) {
    $str .= $k." : ".$v."<br/>";
}
echo $str;