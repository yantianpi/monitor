<?php
define('HACKER_ATTACK', true);
$scriptStartTime = microtime(true);
include_once dirname(dirname(__FILE__)) . '/initiate.php';

if ( addslashes(trim(Common::obtainVariable('Add','post'))) ){
    $recordName = addslashes(trim(Common::obtainVariable('Name', 'post')));
    $description = addslashes(trim(Common::obtainVariable('Description', 'post')));
    $projectId = intval(Common::obtainVariable('ProjectId', 'post'));
    $categoryId = 1;

    //获取content配置项
    $content = array();
    $contentUrl = addslashes(trim(Common::obtainVariable('Url', 'post')));
    if (!empty($contentUrl)){
        $content['Url']['Value'] = $contentUrl;
        $content['Url']['ContentType'] = 'STRING';
    }
    $contentType = addslashes(trim(Common::obtainVariable('Type', 'post')));
    if (!empty($contentType)){
        $content['Type']['Value'] = $contentType;
        $content['Type']['ContentType'] = 'STRING';
    }
    if($contentType != 'login'){
        $contentLoginId = intval(Common::obtainVariable('LoginMethodId', 'post'));
        if (!empty($contentLoginId)){
            $content['LoginId']['Value'] = $contentLoginId;
            $content['LoginId']['ContentType'] = 'INT';
        }
    }
    $contentMethod = strtoupper(addslashes(trim(Common::obtainVariable('Method', 'post'))));
    if (!empty($contentMethod)){
        $content['Method']['Value'] = $contentMethod;
        $content['Method']['ContentType'] = 'STRING';
    }
    $contentParams = addslashes(trim(Common::obtainVariable('Params', 'post')));
    if (!empty($contentParams)){
        $content['Params']['Value'] = $contentParams;
        $content['Params']['ContentType'] = 'STRING';
    }
    $contentHttpCode = intval(Common::obtainVariable('HttpCode', 'post'));
    if (!empty($contentHttpCode)){
        $content['HttpCode']['Value'] = $contentHttpCode;
        $content['HttpCode']['ContentType'] = 'INT';
    }
    $contentHeader = addslashes(trim(Common::obtainVariable('Header', 'post')));
    if (!empty($contentHeader)){
        $content['Header']['Value'] = $contentHeader;
        $content['Header']['ContentType'] = 'STRING';
    }
    $contentResponseTime = trim(Common::obtainVariable('ResponseTime', 'post'));
    $contentResponseTime = !empty($contentResponseTime) && is_numeric($contentResponseTime) ? abs($contentResponseTime) : 5;
    if (!empty($contentResponseTime)){
        $content['ResponseTime']['Value'] = $contentResponseTime;
        $content['ResponseTime']['ContentType'] = 'STRING';
    }
    $contentContentSize = intval(Common::obtainVariable('ContentSize', 'post'));
    if (!empty($contentContentSize)){
        $content['ContentSize']['Value'] = $contentContentSize;
        $content['ContentSize']['ContentType'] = 'INT';
    }
    $contentWhiteList = addslashes(trim(Common::obtainVariable('WhiteList', 'post')));
    if (!empty($contentWhiteList)){
        $content['WhiteList']['Value'] = $contentWhiteList;
        $content['WhiteList']['ContentType'] = 'REGEX';
    }
    $contentWhiteList2 = addslashes(trim(Common::obtainVariable('WhiteList2', 'post')));
    if (!empty($contentWhiteList2)){
        $content['WhiteList2']['Value'] = $contentWhiteList2;
        $content['WhiteList2']['ContentType'] = 'REGEX';
    }
    $contentWhiteList3 = addslashes(trim(Common::obtainVariable('WhiteList3', 'post')));
    if (!empty($contentWhiteList3)){
        $content['WhiteList3']['Value'] = $contentWhiteList3;
        $content['WhiteList3']['ContentType'] = 'STRING';
    }
    $contentBlackList = addslashes(trim(Common::obtainVariable('BlackList', 'post')));
    if (!empty($contentBlackList)){
        $content['BlackList']['Value'] = $contentBlackList;
        $content['BlackList']['ContentType'] = 'REGEX';
    }
    $contentBlackList2 = addslashes(trim(Common::obtainVariable('BlackList2', 'post')));
    if (!empty($contentBlackList2)){
        $content['BlackList2']['Value'] = $contentBlackList2;
        $content['BlackList2']['ContentType'] = 'STRING';
    }

    $content = json_encode($content);
    $flagAdd = false;
    $batchId = intval(Common::obtainVariable('BatchId', 'post'));
    $otherBatch = intval(Common::obtainVariable('otherBatch', 'post')); 

    if ($batchId == 0 && $otherBatch == 0){
        $flagAdd = true;
    }else{
        if ($batchId == 0){
            //独享任务
            if($otherBatch < 10 || $otherBatch > 60) $flagAdd = true;
            $tmpCron = "*/{$otherBatch} * * * *";
        }else{
            $sql = "SELECT Crontime
                    FROM batch_list
                    WHERE `Status` = 'ACTIVE'
                    AND Id = {$batchId} ;";
            $tmpArray = $objMysql->getFirstRow($sql);
            if(empty($tmpArray)){
                if($otherBatch == 0){
                    $flagAdd = true;
                }else{
                    if($otherBatch < 10 || $otherBatch > 60) $flagAdd = true;
                    $otherBatchName = "per".$otherBatch."minute";
                    $tmpCron = "*/{$otherBatch} * * * *";
                    $date = date('Y-m-d H:i:s');
                    $sql = "insert  into `batch_list`
                    (`Name`,`Alias`,`Crontime`,`Throughput`,`Status`,`AddTime`) 
                    values 
                    ('{$otherBatchName}','每{$otherBatch}分钟','{$tmpCron}',100,'ACTIVE','{$date}')";
                    $objMysql->query($sql);
                }
            }else{
                $tmpCron = $tmpArray['Crontime'];
            }
        }
    }
    $notifyType = addslashes(trim(Common::obtainVariable('notifyType', 'post')));

    $Addressee = Common::obtainVariable('Addressee', 'post');
    $Addressee = !empty($Addressee) ? $Addressee :array();
    $CC = Common::obtainVariable('CC', 'post');
    $CC = !empty($CC) ? $CC :array();
    $commonTitle = addslashes(trim(Common::obtainVariable('commonTitle', 'post')));
    $commonTitle = !empty($commonTitle) ? $commonTitle :"";
    $commonBody = addslashes(trim(Common::obtainVariable('commonBody', 'post')));
    $commonBody = !empty($commonBody) ? $commonBody :"";
    $notifyObject = array(
        'Addressee' => $Addressee,
        'CC' => $CC,
        'commonTitle' => $commonTitle,
        'commonBody' => $commonBody,
    );
    $notifyObject = json_encode($notifyObject);

    $alertLimit = abs(intval(Common::obtainVariable('alertLimit', 'post')));
    $recordRunStatus = trim(Common::obtainVariable('runStatus', 'post'));
    $recordStatus = trim(Common::obtainVariable('status', 'post'));

    if (empty($contentUrl) || empty($Addressee) || $flagAdd ){
        $url = "http://".$_SERVER['HTTP_HOST']."/set/recordList.php?batchId=-1";
        header("Location: $url"); 
        exit;
    }

    $date = date('Y-m-d H:i:s');
    $sql = "insert into `record_list`
            (`Name`, `Description`, `ProjectId`, `CategoryId`, `Content`, `CronTime`, `Batch`, `NotifyType`, `NotifyObject`, `MonitorCount`, `AlertCount`, `SeriesAlertCount`, `AlertLimit`, `RunStatus`, `Status`, `AddTime`,`UpdateTime`) 
            values
            ('{$recordName}','{$description}','{$projectId}','{$categoryId}','{$content}','{$tmpCron}','{$batchId}','{$notifyType}','{$notifyObject}','0','0','0','{$alertLimit}','PENDING','{$recordStatus}','{$date}','{$date}');";
    $objMysql->query($sql);
    if($objMysql->getAffectedRows()){
        $url = "http://".$_SERVER['HTTP_HOST']."/set/recordList.php?batchId=-1";
        header("Location: $url"); 
        exit;
    }
}
$sql = "SELECT Name,ContentType
        FROM attribute_list
        WHERE `Status` = 'ACTIVE';";
$tmpArray = $objMysql->getRows($sql);
foreach ($tmpArray as $v) {
    $attributeOptionArray[ucfirst($v['Name'])]['Value'] = '';
    $attributeOptionArray[ucfirst($v['Name'])]['ContentType'] = $v['ContentType'];
}

$sql = "SELECT Id, Alias, Script
        FROM category_list
        WHERE `Status` = 'ACTIVE';";
$tmpArray = $objMysql->getRows($sql, 'Id');
$sql = "SELECT Id, `Name`
        FROM project_list
        WHERE `Status` = 'ACTIVE';";
$tmpArray = $objMysql->getRows($sql, 'Id');
$projectOptionArray = array();
foreach ($tmpArray as $tmpInfo) {
    $tmpId = intval($tmpInfo['Id']);
    $tmpName = trim($tmpInfo['Name']);
    if (!isset($projectOptionArray[$tmpId])) {
        $projectOptionArray[$tmpId] = $tmpName;
    }
}
$sql = "SELECT Id, Alias, Throughput
        FROM batch_list
        WHERE `Status` = 'ACTIVE';";
$tmpArray = $objMysql->getRows($sql, 'Id');
$batchOptionArray = array(0 => 'exclusive');
foreach ($tmpArray as $tmpInfo) {
    $tmpId = intval($tmpInfo['Id']);
    $tmpName = "{$tmpId} {$tmpInfo['Alias']} {$tmpInfo['Throughput']}";
    if (!isset($batchOptionArray[$tmpId])) {
        $batchOptionArray[$tmpId] = $tmpName;
    }
}
array_push($batchOptionArray,'other');
$sql = "SELECT Id,Name,Mail
        FROM mail_list
        WHERE `Status` = 'ACTIVE';";
$mailOptionArray = $objMysql->getRows($sql);
$sql = "SELECT Id, `Name`
        FROM project_list
        WHERE `Status` = 'ACTIVE';";
$tmpArray = $objMysql->getRows($sql, 'Id');
$projectOptionArray = array();
foreach ($tmpArray as $tmpInfo) {
    $tmpId = intval($tmpInfo['Id']);
    $tmpName = trim($tmpInfo['Name']);
    if (!isset($projectOptionArray[$tmpId])) {
        $projectOptionArray[$tmpId] = $tmpName;
    }
}
$tmpArray = Common::obtainEnumList('record_list', 'NotifyType');
$notifyTypeOptionArray = array() + Common::mappingValueArray($tmpArray);
$tmpArray = Common::obtainEnumList('record_list', 'status');
$StatusOptionArray = array() + Common::mappingValueArray($tmpArray);
$MethodOptionArray = array(
    'GET' => 'GET',
    'POST' => 'POST',
);
$TypeOptionArray = array(
    'page' => 'page',
    'login' => 'login'
);

$smartyObj->assign(
    array(
        'title' => 'Record Add Url',
        'userName' => isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '',
        'pageTag' => 'recordAddUrl',
        'pageName' => 'Record Add Url',
         'mailOptionArray' => $mailOptionArray,
         'projectOptionArray' => $projectOptionArray,
         'batchOptionArray' => $batchOptionArray,
         'notifyTypeOptionArray' => $notifyTypeOptionArray,
         'StatusOptionArray' => $StatusOptionArray,
         'attributeOptionArray' => $attributeOptionArray,
         'MethodOptionArray' => $MethodOptionArray,
         'TypeOptionArray' => $TypeOptionArray,
    )
);

$timeConsume = round(microtime(true) - $scriptStartTime, 4);
$message = "cost {$timeConsume}s";
$smartyObj->assign('message', $message);
$smartyObj->displayTpl('recordAdd.tpl');
