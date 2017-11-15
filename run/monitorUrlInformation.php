<?php
if (!defined('HACKER_ATTACK')) {
    define('HACKER_ATTACK', true);
}
include_once dirname(dirname(__FILE__)) . '/initiate.php';
$fileName = basename(__FILE__);
//接收到命令行参数参数  取出配置信息进行解密 运行 产生预警信息或正常信息
if (isset($argv[1]) && isset($argv[2])) {
    $argOne = intval($argv[1]); // exclusive orbatchid
    $argTwo = intval($argv[2]); // throughput or recordid
    if ($argOne == 0 && $argTwo > 0) { // exclusive
        $recordId = $argTwo;
        $sql = "SELECT * 
                FROM `record_list`
                WHERE Id = '{$recordId}' and Batch = 0 and RunStatus = 'PENDING' and Status ='ACTIVE'";
        $r = $objMysql->getFirstRow($sql);
        $recordInfo = $r;
        if (empty($r)) {
            $content = "record({$recordId}) not found";
            Common::recordLog('RUN','RECORD',$recordId, $fileName, 'Record Not Found', $content);
            exit();
        }
        // runstatus from pending to processing
        $date = date('Y-m-d H:i:s');
        $sql = "update record_list 
                set RunStatus = 'PROCESSING',MonitorCount=MonitorCount+1,LastMonitorTime='{$date}',StartTime = '{$date}',UpdateTime = '{$date}' 
                where Id = '{$recordId}' and RunStatus = 'PENDING' and Status ='ACTIVE'";
        $objMysql->query($sql);
        $content = "record({$recordId}) runstatus PENDING to PROCESSING time : {$date}";
        Common::recordLog('RUN','RECORD',$recordId, $fileName, 'State Change', $content);
        $r['Content'] = json_decode($r['Content'], true); // processing
        //判断是否需要二次登录 若需 则处理好数据 放入$r
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
        $message = Custom::early_warning_processing($r);
        // runstatus from processing to resloved
        $date = date('Y-m-d H:i:s');
        $sql = "update record_list 
                set RunStatus = 'RESLOVED',UpdateTime = '{$date}' 
                where Id = '{$recordId}' and RunStatus = 'PROCESSING' and Status ='ACTIVE'";
        $objMysql->query($sql);
        $content = "record({$recordId}) runstatus PROCESSING to RESLOVED time : {$date}";
        Common::recordLog('RUN','RECORD',$recordId, $fileName, 'State Change', $content);

        if (!empty($message)) {// alert
            $date = date('Y-m-d H:i:s');
            $sql = "update record_list 
                    set SeriesAlertCount=SeriesAlertCount+1,AlertCount=AlertCount+1,LastAlertTime='{$date}',UpdateTime = '{$date}' 
                    where Id = '{$recordId}' and Status ='ACTIVE'";
            $objMysql->query($sql);
            $content = "record({$recordId}) produce alert time : {$date}";
            Common::recordLog('RUN','RECORD',$recordId, $fileName, 'Alert', $content, 'YES');
            $seriesAlertCount = isset($recordInfo['SeriesAlertCount']) ? intval($recordInfo['SeriesAlertCount']) : 0;
            $alertLimit = isset($recordInfo['AlertLimit']) ? intval($recordInfo['AlertLimit']) : 0;
            if ($seriesAlertCount < $alertLimit) {
                //发预警
                if($recordInfo['NotifyType'] == 'MAIL'){
                    $flag = Custom::send_caveat_message($message);
                }else{
                    return false;
                }
                $content = $flag ? "Send an alert message successfully！" : "Sending an alert message failed！";
                $date = date('Y-m-d H:i:s');
                $content = "record({$recordId}) {$content} time : {$date}";
                Common::recordLog('NOTIFY','RECORD',$recordId, $fileName, 'Send Mail', $content);
            } else {
                //不发 做记录
                $date = date('Y-m-d H:i:s');
                $content = "record({$recordId}) alert but not notify (seriesalert {$seriesAlertCount} alertlimit {$alertLimit}) time : {$date}";
                Common::recordLog('RUN','RECORD',$recordId, $fileName, 'Alert But Not Notify', $content);
            }
        } else {
            $sql = "update record_list 
                    set SeriesAlertCount=0,UpdateTime = '{$date}' 
                    where Id = '{$recordId}' and Status ='ACTIVE'";
            $objMysql->query($sql);
            $date = date('Y-m-d H:i:s');
            $content = "record({$recordId}) series alert reset time : {$date}";
            Common::recordLog('RUN','RECORD',$recordId, $fileName, 'Series Alert Reset', $content);
        }
        $date = date('Y-m-d H:i:s');
        $sql = "update record_list 
                set RunStatus = 'PENDING',EndTime = '{$date}',UpdateTime = '{$date}' 
                where Id = '{$recordId}' and RunStatus = 'RESLOVED' and Status ='ACTIVE'";
        $objMysql->query($sql);
        $content = "record({$recordId}) runstatus RESLOVED to PENDING time : {$date}";
        Common::recordLog('RUN','RECORD',$recordId, $fileName, 'State Change', $content);
    } elseif ($argOne > 0 && $argOne > 0) {
        $threshold = 500;  //批处理阀值
        $batch = $argOne;  //批次
        $count = $argTwo;  //批处理量
        $countLimit = intval(min($count, $threshold));
        $end_restored_id = array();
        while (true) {
            $sql = "SELECT *
                    FROM`record_list` 
                    WHERE Batch = '{$batch}' AND RunStatus = 'PENDING' AND Status ='ACTIVE' 
                    ORDER BY Id ASC
                    LIMIT {$countLimit}";
            $r = $objMysql->getRows($sql);
            if (empty($r)) {
                break;
            }
            $count = count($r);
            $pending_id = array();
            foreach ($r as $v) {
                $pending_id[] = intval($v['Id']);
                $end_restored_id[] = intval($v['Id']);  //处理完等待恢复为pending的数组
            }
            $strId = implode("', '", $pending_id);
            $date = date('Y-m-d H:i:s');
            $sql = "update record_list 
                    set RunStatus = 'PROCESSING',MonitorCount=MonitorCount+1,LastMonitorTime='{$date}',StartTime = '{$date}',UpdateTime = '{$date}' 
                    where Id IN ('{$strId}') and RunStatus = 'PENDING' and Status ='ACTIVE'";
            $objMysql->query($sql);
            $content = "records('{$strId}') runstatus PENDING to PROCESSING time {$date}";
            Common::recordLog('RUN','RECORD','0', $fileName, 'State Change Batch', $content);
            foreach ($r as $k => $v) {
                $v['Content'] = json_decode($v['Content'], true);
                $message = Custom::early_warning_processing($v);
                if (!empty($message)) {
                    $date = date('Y-m-d H:i:s');
                    $sql = "update record_list 
                            set SeriesAlertCount=SeriesAlertCount+1,AlertCount=AlertCount+1,LastAlertTime='{$date}',UpdateTime = '{$date}' 
                            where Id = '{$v['Id']}' and Status ='ACTIVE'";
                    $objMysql->query($sql);
                    $content = "records({$v['Id']}) produce alert time {$date}";
                    Common::recordLog('RUN','RECORD',$v['Id'], $fileName, 'Alert', $content, 'YES');
                    $seriesAlertCount = isset($v['SeriesAlertCount']) ? intval($v['SeriesAlertCount']) : 0;
                    $alertLimit = isset($v['AlertLimit']) ? intval($v['AlertLimit']) : 0;
                    if ($seriesAlertCount < $alertLimit) {
                        if($v['NotifyType'] == 'MAIL'){
                            $flag = Custom::send_caveat_message($message);
                        }else{
                            return false;
                        }
                        $content = $flag ? "Send an alert message successfully！" : "Sending an alert message failed！";
                        $content = "records({$v['Id']}) {$content} time {$date}";
                        Common::recordLog('NOTIFY','RECORD',$v['Id'], $fileName, 'Send Mail', $content);
                    } else {
                        $date = date('Y-m-d H:i:s');
                        $content = "records({$v['Id']}) alert but not notify(seriesalert {$seriesAlertCount} alertlimit {$alertLimit}) time {$date}";
                        Common::recordLog('RUN','RECORD',$v['Id'], $fileName, 'Alert But Not Notify', $content);
                    }
                } else {
                    $date = date('Y-m-d H:i:s');
                    $sql = "update record_list 
                            set SeriesAlertCount=0,UpdateTime = '{$date}' 
                            where Id = '{$v['Id']}' and Status ='ACTIVE'";
                    $objMysql->query($sql);
                    $content = "records({$v['Id']}) series alert reset time {$date}";
                    Common::recordLog('RUN','RECORD',$v['Id'], $fileName, 'Series Alert Reset', $content);
                }
            }
            //处理完改为RESLOVED
            $date = date('Y-m-d H:i:s');
            $sql = "update record_list 
                    set RunStatus = 'RESLOVED',UpdateTime = '{$date}' 
                    where Id IN ('{$strId}') and RunStatus = 'PROCESSING' and Status ='ACTIVE'";
            $objMysql->query($sql);
            $content = "records('{$strId}') runstatus PROCESSING to RESLOVED time {$date}";
            Common::recordLog('RUN','RECORD','0', $fileName, 'State Change Batch', $content);
            if ($count < $countLimit) {
                break;
            }
        }
        $end_restored_id = implode("', '", $end_restored_id);
        $date = date('Y-m-d H:i:s');
        $sql = "update record_list 
                set RunStatus = 'PENDING',EndTime= '{$date}',UpdateTime = '{$date}' 
                where Id IN ('{$end_restored_id}') and RunStatus = 'RESLOVED' and Status ='ACTIVE'";
        $objMysql->query($sql);
        $content = "records('{$end_restored_id}') runstatus RESLOVED to PENDING time {$date}";
        Common::recordLog('RUN','RECORD','0', $fileName, 'State Change Batch', $content);
    } else {
        //参数设置异常
        $content = "Parameter 1: {$argOne} Parameter 2: {$argTwo}.";
        Common::recordLog('OTHER','RECORD','0', $fileName, 'Parameter Error', $content);
    }
} else {
    //参数未设置
    $content = 'argment one: 0(batchid), argument two: recordid(throughput)';
    Common::recordLog('OTHER','RECORD','0', $fileName, 'Parameter Missing', $content);
}