<?php
define('HACKER_ATTACK', true);
$scriptStartTime = microtime(true);
include_once dirname(dirname(__FILE__)) . '/initiate.php';
$scriptDir = rtrim(SCRIPT_DIR, '/') . '/';

/*
 * category list
 */
$categoryList = array();
$sql = "SELECT Id, `Name`, Script
        FROM category_list
        WHERE `Status` = 'ACTIVE';";
$tmpArray = $objMysql->getRows($sql);
if (!empty($tmpArray)) {
    $tmpInfo = array();
    clearstatcache();
    foreach ($tmpArray as $categoryInfo) {
        $categoryId = intval($categoryInfo['Id']);
        $name = trim($categoryInfo['Name']);
        $script = trim($categoryInfo['Script']);
        $scriptPath = $scriptDir . $script;
        if (file_exists($scriptPath) && is_file($scriptPath)) {
            $tmpInfo = array(
                'categoryId' => $categoryId,
                'name' => $name,
                'scriptPath' => $scriptPath,
            );
            $categoryList[$categoryId] = $tmpInfo;
        } else {
            continue;
        }
    }
}
//Common::debugDump($categoryList);

/*
 * batch list
 */
$batchList = array();
$sql = "SELECT Id, `Name`, Throughput
        FROM batch_list
        WHERE `Status` = 'ACTIVE';";
$tmpArray = $objMysql->getRows($sql);
if (!empty($tmpArray)) {
    $tmpInfo = array();
    foreach ($tmpArray as $batchInfo) {
        $batchId = intval($batchInfo['Id']);
        $name = trim($batchInfo['Name']);
        $throughput = intval($batchInfo['Throughput']);
        $tmpInfo = array(
            'batchId' => $batchId,
            'name' => $name,
            'throughput' => $throughput,
        );
        $batchList[$batchId] = $tmpInfo;
    }
}
//Common::debugDump($batchList);

/*
 * record list
 */
$sql = "SELECT Id, `Name`, CategoryId, CronTime, Batch
        FROM record_list
        WHERE `Status` = 'ACTIVE'
        ORDER BY CategoryId, batch ASC;";
$recordList = $objMysql->getRows($sql);
//Common::debugDump($recordList);

/*
 *  cron config list
 */
$cronConfigList = array(
    'batch' => array(),
    'exclusive' => array(),
);
foreach ($recordList as $recordInfo) {
    $recordId = intval($recordInfo['Id']);
    $categoryId = intval($recordInfo['CategoryId']);
    $batchId = intval($recordInfo['Batch']);
    $cronTime = trim($recordInfo['CronTime']);
    if (isset($categoryList[$categoryId])) {
        if ($batchId == 0) { // 独享任务
            if (!isset($cronConfigList['exclusive'][$categoryId]['script'])) {
                $cronConfigList['exclusive'][$categoryId]['script'] = $categoryList[$categoryId]['scriptPath'];
            }
            $cronConfigList['exclusive'][$categoryId]['opt'][$recordId] = array(
                'recordId' => $recordId,
                'cronTime' => $cronTime,
            );
        } else { // 批次任务
            if (isset($batchList[$batchId])) { // 批次有效
                if (!isset($cronConfigList['batch'][$categoryId]['script'])) {
                    $cronConfigList['batch'][$categoryId]['script'] = $categoryList[$categoryId]['scriptPath'];
                }
                if (!isset($cronConfigList['batch'][$categoryId]['opt'][$batchId])) {
                    $cronConfigList['batch'][$categoryId]['opt'][$batchId] = array(
                        'batchId' => $batchId,
                        'cronTime' => $cronTime,
                        'throughput' => intval($batchList[$batchId]['throughput']),
                    );
                }
            } else {
                continue;
            }
        }
    } else {
        continue;
    }
}
//Common::debugDump($cronConfigList);

/*
 * basal cron
 */
$baseCronString = '';
// generate cron */10 * * * *
$script = basename(__FILE__);
$scriptPath = $scriptDir . $script;
$tmpString = "*/10 * * * * php {$scriptPath} > /dev/null 2>&1\n";
$baseCronString .= $tmpString;
// anomal record detection */10 * * * *
$script = 'anormalDetection.php';
$scriptPath = $scriptDir . $script;
$tmpString = "*/10 * * * * php {$scriptPath} > /dev/null 2>&1\n";
$baseCronString .= $tmpString;

/*
 * collate cron
 */
$cronList = array();
// exclusive
foreach ($cronConfigList['exclusive'] as $categoryCronInfo) {
    $script = $categoryCronInfo['script'];
    $opt = $categoryCronInfo['opt'];
    foreach ($opt as $tmpInfo) {
        $recordId = $tmpInfo['recordId'];
        $cronTime = $tmpInfo['cronTime'];
        $cronList[] = "{$cronTime} php {$script} 0 {$recordId} > /dev/null 2>&1";
    }
}
// batch
foreach ($cronConfigList['batch'] as $categoryCronInfo) {
    $script = $categoryCronInfo['script'];
    $opt = $categoryCronInfo['opt'];
    foreach ($opt as $tmpInfo) {
        $batchId = $tmpInfo['batchId'];
        $cronTime = $tmpInfo['cronTime'];
        $throughput = $tmpInfo['throughput'];
        $cronList[] = "{$cronTime} php {$script} {$batchId} {$throughput} > /dev/null 2>&1";
    }
}
//Common::debugDump($cronList);

/*
 * write file
 */
$cronPath = rtrim(DATA_DIR, '/') . '/' . 'cronList.txt';
$message = '';
if (!empty($cronList)) {
    $originalCron = @file_get_contents($cronPath);
    $string = $baseCronString . implode("\n", $cronList) . "\n";
    if ($originalCron == $string) {
        $message = "don't need to update.";
    } else {
        $status = file_put_contents($cronPath, $string, LOCK_EX);
        if ($status !== false) {
            $tmpArray = array();
            @exec("crontab {$cronPath}", $tmpArray);
            $tmp = implode("\t", $tmpArray);
            $message = "write cron {$tmp}.";
        } else {
            $message = 'write file unsuccessfully!';
        }
    }
} else {
    $message = 'cron list is empty.';
}
$message .= '. time ' . date('Y-m-d H:i:s') . ' cost ' . round(microtime(true) - $scriptStartTime, 4) . 's';
Common::recordLog('RUN','OTHER','0', basename(__FILE__), 'Generate Cron List', $message);