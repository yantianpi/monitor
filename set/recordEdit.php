<?php
define('HACKER_ATTACK', true);
$scriptStartTime = microtime(true);
include_once dirname(dirname(__FILE__)) . '/initiate.php';

//接受编辑ID 查出数据  修改  再入库
$recordId = addslashes(trim(Common::obtainVariable('id', 'get')));
if ( addslashes(trim(Common::obtainVariable('update','post'))) ){
    $recordId = addslashes(trim(Common::obtainVariable('Id', 'post')));
    $recordName = addslashes(trim(Common::obtainVariable('Name', 'post')));
    $description = addslashes(trim(Common::obtainVariable('Description', 'post')));
    $projectId = intval(Common::obtainVariable('ProjectId', 'post'));

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

    $flagEdit = false;
    $batchId = intval(Common::obtainVariable('batchId', 'post'));
    $otherBatch = intval(Common::obtainVariable('otherBatch', 'post')); 

    if ($batchId == 0 && $otherBatch == 0){
        $flagEdit = true;
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
                    $flagEdit = true;
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

    $alertLimit = intval(Common::obtainVariable('alertLimit', 'post'));
    $alertLimit = preg_match("/^[1-9][0-9]*$/",$alertLimit) ? $alertLimit : 10; //判断是否为为正整数
    $recordStatus = trim(Common::obtainVariable('status', 'post'));

    if (empty($contentUrl) || empty($Addressee) || $flagEdit ){
        $url = "http://".$_SERVER['HTTP_HOST']."/set/recordList.php?batchId=-1";
        header("Location: $url"); 
        exit;
    }

    $date = date('Y-m-d H:i:s');
    $sql = "UPDATE record_list SET
            `Name` = '{$recordName}',
            `Description` = '{$description}',
            `ProjectId` = '{$projectId}',
            `Content` = '{$content}',
            `CronTime` = '{$tmpCron}',
            `Batch` = '{$batchId}',
            `NotifyType` = '{$notifyType}',
            `NotifyObject` = '{$notifyObject}',
            `AlertLimit` = '{$alertLimit}',
            `Status` = '{$recordStatus}',
            `UpdateTime` = '{$date}'
            WHERE `Id` = '{$recordId}'";
    $objMysql->query($sql);
    if($objMysql->getAffectedRows()){
        $url = "http://".$_SERVER['HTTP_HOST']."/set/recordList.php?batchId=-1";
        header("Location: $url");
        exit;
    }
}

$sql = "SELECT rl.*, pl.`Name` AS ProjectName, cl.Alias, cl.Script
        FROM record_list rl
        LEFT JOIN project_list pl ON rl.ProjectId = pl.Id
        LEFT JOIN category_list cl ON rl.CategoryId = cl.Id
        WHERE rl.Id = '{$recordId}';";
$recordList = $objMysql->getFirstRow($sql);
//print_r($recordList);die;
$recordList['CromMinute'] = substr($recordList['CronTime'],2,2); //暂时针对分钟的处理方式
$contentArray = json_decode($recordList['Content'],true);
$NotifyObjectArray = json_decode($recordList['NotifyObject'],true);

$sql = "SELECT Name,ContentType
        FROM attribute_list
        WHERE `Status` = 'ACTIVE';";
$tmpArray = $objMysql->getRows($sql);
foreach ($tmpArray as $v) {
    $attributeOptionArray[ucfirst($v['Name'])]['Value'] = '';
    $attributeOptionArray[ucfirst($v['Name'])]['ContentType'] = $v['ContentType'];
}
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

$sql = "SELECT Id, Alias, Script
        FROM category_list
        WHERE `Status` = 'ACTIVE';";
$tmpArray = $objMysql->getRows($sql, 'Id');

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
$tmpArray = Common::obtainEnumList('record_list', 'NotifyType');
$notifyTypeOptionArray = array() + Common::mappingValueArray($tmpArray);
$tmpArray = Common::obtainEnumList('record_list', 'status');
$StatusOptionArray = array() + Common::mappingValueArray($tmpArray);
$tmpArray = Common::obtainEnumList('attribute_list', 'ContentType');
$ContentTypeFieldOptionArray = array() + Common::mappingValueArray($tmpArray);
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
        'title' => 'record Edit Url',
        'userName' => 'howietu',
        'pageTag' => 'recordEditUrl',
        'recordId' => $recordId,
        'attributeOptionArray' => $attributeOptionArray,
        'contentArray' => $contentArray,
        'pageName' => 'Record Edit Url',
        'contentArray' => $contentArray,
        'recordList' => $recordList,
        'NotifyObjectArray' => $NotifyObjectArray,
         'ContentTypeFieldOptionArray' => $ContentTypeFieldOptionArray,
         'mailOptionArray' => $mailOptionArray,
         'notifyTypeOptionArray' => $notifyTypeOptionArray,
         'projectOptionArray' => $projectOptionArray,
         'MethodOptionArray' => $MethodOptionArray,
         'batchOptionArray' => $batchOptionArray,
         'StatusOptionArray' => $StatusOptionArray,
         'TypeOptionArray' => $TypeOptionArray,
    )
);

$timeConsume = round(microtime(true) - $scriptStartTime, 4);
$message = "cost {$timeConsume}s";
$smartyObj->assign('message', $message);

$smartyObj->displayTpl('recordEdit.tpl');
