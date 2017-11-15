<?php
define('HACKER_ATTACK', true);
$scriptStartTime = microtime(true);
include_once dirname(dirname(__FILE__)) . '/initiate.php';
$orderOptionArray = array(
    'id' => 'id',
    'project' => 'project',
    'batch' => 'batch',
    'category' => 'category',
);
$sortOptionArray = array(
    'ASC' => 'ASC',
    'DESC' => 'DESC',
);
$tmpArray = Common::obtainEnumList('record_list', 'Status');
$recordStatusArray = array('all' => 'ALL') + Common::mappingValueArray($tmpArray);
$tmpArray = Common::obtainEnumList('record_list', 'RunStatus');
$recordRunStatusArray = array('all' => 'ALL') + Common::mappingValueArray($tmpArray);
$sql = "SELECT Id, Alias, Script
        FROM category_list
        WHERE `Status` = 'ACTIVE';";
$tmpArray = $objMysql->getRows($sql, 'Id');
$categoryOptionArray = array(0 => 'all');
foreach ($tmpArray as $tmpInfo) {
    $tmpId = intval($tmpInfo['Id']);
    $tmpName = "{$tmpId} {$tmpInfo['Alias']}";
    if (!isset($categoryOptionArray[$tmpId])) {
        $categoryOptionArray[$tmpId] = $tmpName;
    }
}
$sql = "SELECT Id, `Name`
        FROM project_list
        WHERE `Status` = 'ACTIVE';";
$tmpArray = $objMysql->getRows($sql, 'Id');
$projectOptionArray = array(0 => 'all');
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
$batchOptionArray = array(-1 => 'all', 0 => 'exclusive');
foreach ($tmpArray as $tmpInfo) {
    $tmpId = intval($tmpInfo['Id']);
    $tmpName = "{$tmpId} {$tmpInfo['Alias']} {$tmpInfo['Throughput']}";
    if (!isset($batchOptionArray[$tmpId])) {
        $batchOptionArray[$tmpId] = $tmpName;
    }
}

/*
 * where
 */
$where = ' WHERE true';
$categoryId = intval(Common::obtainVariable('categoryId', 'get'));
if (!empty($categoryId)) {
    $where .= " AND rl.CategoryId = '{$categoryId}'";
}
$projectId = intval(Common::obtainVariable('projectId', 'get'));
if (!empty($projectId)) {
    $where .= " AND rl.ProjectId = '{$projectId}'";
}
$batchId = intval(Common::obtainVariable('batchId', 'get'));
if ($batchId != -1) {
    $where .= " AND rl.Batch = '{$batchId}'";
}
$recordStatus = trim(Common::obtainVariable('recordStatus', 'get'));
if (empty($recordStatus)) {
    $recordStatus = 'all';
}
if ($recordStatus != 'all') {
    $tmp = addslashes($recordStatus);
    $where .= " AND rl.Status = '{$tmp}'";
}
$recordRunStatus = trim(Common::obtainVariable('recordRunStatus', 'get'));
if (empty($recordRunStatus)) {
    $recordRunStatus = 'all';
}
if ($recordRunStatus != 'all') {
    $tmp = addslashes($recordRunStatus);
    $where .= " AND rl.RunStatus = '{$tmp}'";
}
$recordName = addslashes(trim(Common::obtainVariable('recordName', 'get')));
if (!empty($recordName)) {
    $where .= " AND rl.Name LIKE '%{$recordName}%'";
}
$recordId = intval(Common::obtainVariable('recordId', 'get'));
if (!empty($recordId)) {
    $where .= " AND rl.Id = '{$recordId}'";
}
/*
 * order
 */
$order = Common::obtainVariable('order', 'get');
if (empty($order) || !isset($orderOptionArray[$order])) {
    $order = 'id';
}
$sort = Common::obtainVariable('sort', 'get');
if (empty($sort) || !isset($sortOptionArray[$sort])) {
    $sort = 'DESC';
}
$orderStr = '';
if ($order == 'id') {
    $orderStr = "ORDER BY rl.Id {$sort}";
} elseif ($order == 'project') {
    $orderStr = "ORDER BY rl.ProjectId {$sort}";
} elseif ($order == 'batch') {
    $orderStr = "ORDER BY rl.Batch {$sort}";
} elseif ($order == 'category') {
    $orderStr = "ORDER BY rl.CategoryId {$sort}";
} else {
    $orderStr = "ORDER BY rl.Id DESC";
}

/*
 * limit
 */
$limit = ' LIMIT ' . $pageObj->offset . ', ' . $pageObj->onepage;

$sql = "SELECT `Id`,`Name` FROM mail_list WHERE Status = 'ACTIVE'";
$tmpArray = $objMysql->getRows($sql,'Id');

$sql = "SELECT rl.*, pl.`Name` AS ProjectName, cl.Alias AS CategoryAlias, cl.Script
        FROM record_list rl
        LEFT JOIN project_list pl ON rl.ProjectId = pl.Id
        LEFT JOIN category_list cl ON rl.CategoryId = cl.Id
        {$where}
        {$orderStr}
        {$limit};";
//Common::debugDump($sql);
$recordList = $objMysql->getRows($sql, '', true);

if(!empty($tmpArray)){
    foreach ($recordList as $k => $v) {
        if (!isset($v['NotifyObject']) || empty($v['NotifyObject']) ) break;
        $tmp = json_decode($v['NotifyObject'],true);
        $tmp['AddresseeName']=$tmp['CCName']='';
        if (!isset($tmp['Addressee']) || empty($tmp['Addressee']) ) break;
        foreach ($tmp['Addressee'] as $kk => $vv) {
            if(isset($tmpArray[$vv])) $tmp['AddresseeName'] .= $tmpArray[$vv]['Name'].' ';
        }
        foreach ($tmp['CC'] as $kk => $vv) {
            if(isset($tmpArray[$vv])) $tmp['CCName'] .= $tmpArray[$vv]['Name'].' ';
        }
        unset($tmp['Addressee']);
        unset($tmp['CC']);
        $recordList[$k]['NotifyObjectInfo'] = $tmp;
    }
}

$totalCnt = $objMysql->getFoundRows();
$pageObj->setTotal($totalCnt);
$pagebar = $pageObj->whole_bar(1, 10);
$smartyObj->assign(
    array(
        'title' => 'Record List',
        'userName' => isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '',
        'pagebar' => $pagebar,
        'pageTag' => 'recordList',
        'pageName' => 'Record List',
        'orderOptionArray' => $orderOptionArray,
        'order' => $order,
        'sortOptionArray' => $sortOptionArray,
        'sort' => $sort,
        'categoryOptionArray' => $categoryOptionArray,
        'categoryId' => $categoryId,
        'projectOptionArray' => $projectOptionArray,
        'projectId' => $projectId,
        'batchOptionArray' => $batchOptionArray,
        'batchId' => $batchId,
        'recordStatusArray' => $recordStatusArray,
        'recordStatus' => $recordStatus,
        'recordRunStatusArray' => $recordRunStatusArray,
        'recordRunStatus' => $recordRunStatus,
        'recordName' => $recordName,
        'recordId' => $recordId,
        'recordList' => $recordList,
    )
);
$timeConsume = round(microtime(true) - $scriptStartTime, 4);
$message = "cost {$timeConsume}s";
$smartyObj->assign('message', $message);

/*
 * render
 */
$smartyObj->displayTpl('recordList.tpl');