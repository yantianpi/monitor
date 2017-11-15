<?php
define('HACKER_ATTACK', true);
$scriptStartTime = microtime(true);
include_once dirname(dirname(__FILE__)) . '/initiate.php';
$timeInterval = 60 * 10;
$startDateLimit = date('Y-m-d H:i:s', time() - $timeInterval);
$countLimit = 1000;

/*
 * anormal record list
 */
$messageArray = array();
while (true) {
    $sql = "SELECT Id, `Name`, CronTime, RunStatus, StartTime, EndTime, `Status`
        FROM record_list
        WHERE Status = 'ACTIVE' AND RunStatus != 'PENDING' AND StartTime >= EndTime AND StartTime < '{$startDateLimit}'
        ORDER BY Id ASC
        LIMIT {$countLimit};";
    $recordList = $objMysql->getRows($sql);
    if (empty($recordList)) {
        break;
    }
    $count = count($recordList);
    $anormalRecordIdArray = array();
    foreach ($recordList as $recordInfo) {
        $recordId = intval($recordInfo['Id']);
        $anormalRecordIdArray[] = $recordId;
        $messageArray[$recordId] = "record {$recordId} name {$recordInfo['Name']} anormalStatus {$recordInfo['RunStatus']} startTime {$recordInfo['StartTime']} endTime {$recordInfo['EndTime']}";
    }
    if (!empty($anormalRecordIdArray)) {
        $anormalRecordIdString = implode("', '", $anormalRecordIdArray);
        $nowDate = date('Y-m-d H:i:s');
        $sql = "UPDATE record_list
                SET RunStatus = 'PENDING',
                UpdateTime = '{$nowDate}'
                WHERE Id IN ('{$anormalRecordIdString}') AND `Status` = 'ACTIVE' AND RunStatus != 'PENDING';";
        $objMysql->query($sql);
    }
    if ($count < $countLimit) {
        break;
    }
}
$message = '';
if (!empty($messageArray)) {
    $message = implode("\n", $messageArray);
} else {
    $message = 'without anormal record';
}
$message .= '. time ' . date('Y-m-d H:i:s') . ' cost ' . round(microtime(true) - $scriptStartTime, 4) . 's';
Common::recordLog('RUN','OTHER','0', basename(__FILE__), 'Anormal Detection', $message);

