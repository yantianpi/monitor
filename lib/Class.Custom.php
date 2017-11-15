<?php

class Custom
{
    public static function early_warning_processing($data,$isTest = false) {
        if(!isset($data['Id'])) return;
        $returnInfo = " in record_list:id = " . $data['Id'];
        if ($data['CategoryId'] == 1) {
            //属于监控URL类型
            $ch = curl_init();
            $curlData = isset($data['Content'])&&is_array($data['Content']) ? $data['Content'] : array();
            $messagearr = array();   //需要发出预警消息的数组
            $tetsMessage = array();  //测试返回消息数组
            if( isset($curlData['LoginId']['Value']) && !empty($curlData['LoginId']['Value']) ){
                //若果有loginId 则需要进行二次登录
                $tmpData = array();
                $tmpData['LoginId'] = $curlData['LoginId']['Value'];
                $tmpData['LoginData'] = isset($data['LoginIdInfo'])&&is_array($data['LoginIdInfo']) ? $data['LoginIdInfo'] : array();
                $LogData = self::get_cookie_by_loginId($tmpData);
                if(isset($LogData['loginMessage'])) $messagearr['loginMessage'] = $LogData['loginMessage'];
                if(isset($LogData['cookie']) && !empty($LogData['cookie'])){
                    $cookiePath = $LogData['cookie'];
                    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiePath);
                }
            }
            
            $NotifyObject = isset($data['NotifyObject']) ? $data['NotifyObject'] : array();
            $Url = isset($curlData['Url']['Value']) && !empty($curlData['Url']['Value']) ? $curlData['Url']['Value'] : "";
            $Params = isset($curlData['Params']['Value']) && !empty($curlData['Params']['Value']) ? $curlData['Params']['Value'] : "";
            if(!empty($Params) && (substr($Params,0,1) != "?") ) $Params = "?".$Params;
            $Method = isset($curlData['Method']['Value']) && !empty($curlData['Method']['Value']) ? $curlData['Method']['Value'] : "";
            if ( empty($Url) || empty($Method)){
                $messagearr['NotifyObject'] = $NotifyObject;
                $messagearr['alertMessage']['UrlOrMethodNot'] = "Url or Method does not exist ".$returnInfo;
                $tetsMessage['alertMessage']['UrlOrMethodNot'] = "Url or Method does not exist ".$returnInfo;
                return $isTest ? $tetsMessage : $messagearr;
            }
            if ($Method == 'GET') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_URL, $Url . $Params);  //请求的URL 若有get参数 则直接拼上去
            } elseif ($Method == 'POST') {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_URL, $Url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $Params);
            } else{
                $messagearr['NotifyObject'] = $NotifyObject;
                $messagearr['alertMessage']['MethodNot'] = "Method is not set as GET or POST ".$returnInfo;
                $tetsMessage['alertMessage']['MethodNot'] = "Method is not set as GET or POST ".$returnInfo;
                return $isTest ? $tetsMessage : $messagearr;
            }

            if(isset($curlData['Header']['Value']) && !empty($curlData['Header']['Value'])){
                $headr[] = $curlData['Header']['Value'];
                curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
            }

            $ResponseTime = isset($curlData['ResponseTime']['Value']) && !empty($curlData['ResponseTime']['Value']) ? abs(floatval($curlData['ResponseTime']['Value'])) : 5;
            curl_setopt($ch, CURLOPT_TIMEOUT, $ResponseTime);  //设置cURL允许执行的最长秒数。
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);  //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。

            $res = curl_exec($ch);

            if (!($res === false)) {
                $tetsMessage['alertMessage']['responseTime'] = "responseTime Normal response! ".$returnInfo;
                $getHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  //返回状态码
                $HttpCode = isset($curlData['HttpCode']['Value']) ? $curlData['HttpCode']['Value'] : '' ;
                if(empty($HttpCode) && $getHttpCode != 200 ){
                    $messagearr['alertMessage']['http_code'] = "HttpCode abnormal！ gethttpcode :" . $getHttpCode." your httpcode not set! ".$returnInfo;
                }elseif (!empty($HttpCode) && $getHttpCode != $HttpCode ) {
                    $messagearr['alertMessage']['http_code'] = "HttpCode abnormal！ gethttpcode :" . $getHttpCode." sethttpcode : ".$HttpCode.$returnInfo;
                }

                $HttpCodeInfo = !empty($HttpCode) ? $HttpCode : 'not set!';
                $tetsMessage['alertMessage']['http_code'] = "gethttpcode :" . $getHttpCode." sethttpcode : ".$HttpCodeInfo.$returnInfo;

                if ($getHttpCode == 200) {  //状态码不为200则不需要判断下面信息
                    $contentSize = isset($curlData['ContentSize']['Value']) && !empty($curlData['ContentSize']['Value']) ? $curlData['ContentSize']['Value'] : '';
                    if (!empty($contentSize)) {
                        $content = mb_strlen($res);
                        if ($content > $contentSize) $messagearr['alertMessage']['contentSize'] = "get text size : " . $content." set size: ".$contentSize.$returnInfo;
                        $tetsMessage['alertMessage']['contentSize'] = "get text size : " . $content." set size: ".$contentSize.$returnInfo;
                    }
                    //判断白名单   白名单有多个 需全部通过才为true    黑名单有多个  有其一则为false
                    $whiteList = isset($curlData['WhiteList']['Value']) && !empty($curlData['WhiteList']['Value']) ? $curlData['WhiteList']['Value'] : '';
                    $whiteListType = isset($curlData['WhiteList']['ContentType']) && !empty($curlData['WhiteList']['ContentType']) ? $curlData['WhiteList']['ContentType'] : '';
                    if (!empty($whiteList)) {
                        if ($whiteListType == 'REGEX') {
                            $matches = @preg_match($whiteList, $res);
                            $tmpAbnormal = error_get_last();
                            if (!empty($tmpAbnormal)){
                                $messagearr['alertMessage']['WhiteList'] = "WhiteList grammar abnormal!".$returnInfo;
                                $tetsMessage['alertMessage']['WhiteList'] = "WhiteList grammar abnormal!".$returnInfo;
                            }else{
                                if (!$matches){
                                    $messagearr['alertMessage']['WhiteList'] = "WhiteList is Not matching".$returnInfo;
                                    $tetsMessage['alertMessage']['WhiteList'] = "WhiteList is Not matching".$returnInfo;
                                }else{
                                    $tetsMessage['alertMessage']['WhiteList'] = "WhiteList matching normal! ".$returnInfo;
                                }
                            }
                        } elseif ($whiteListType == 'STRING') {
                            if (strpos($res, $whiteList) === false){
                                $messagearr['alertMessage']['WhiteList'] = "WhiteList is Not matching".$returnInfo;
                                $tetsMessage['alertMessage']['WhiteList'] = "WhiteList is Not matching".$returnInfo;
                            }else{
                                $tetsMessage['alertMessage']['WhiteList'] = "WhiteList matching normal! ".$returnInfo;
                            }
                        }else{
                            $messagearr['alertMessage']['WhiteList'] = "WhiteListType is Not Set as REGEX or STRING".$returnInfo;
                            $tetsMessage['alertMessage']['WhiteList'] = "WhiteListType is Not Set as REGEX or STRING".$returnInfo;
                        }
                    }
                    $whiteList2 = isset($curlData['WhiteList2']['Value']) && !empty($curlData['WhiteList2']['Value']) ? $curlData['WhiteList2']['Value'] : '';
                    $whiteList2Type = isset($curlData['WhiteList2']['ContentType']) && !empty($curlData['WhiteList2']['ContentType']) ? $curlData['WhiteList2']['ContentType'] : '';
                    if (!empty($whiteList2)) {
                        if ($whiteList2Type == 'REGEX') {
                            $matches = @preg_match($whiteList2, $res);
                            $tmpAbnormal = error_get_last();
                            if (!empty($tmpAbnormal)){
                                $messagearr['alertMessage']['WhiteList2'] = "WhiteList2 grammar abnormal!".$returnInfo; 
                                $tetsMessage['alertMessage']['WhiteList2'] = "WhiteList2 grammar abnormal!".$returnInfo;
                            }else{
                                if (!$matches){
                                    $messagearr['alertMessage']['WhiteList2'] = "WhiteList2 is Not matching".$returnInfo;
                                    $tetsMessage['alertMessage']['WhiteList2'] = "WhiteList2 is Not matching".$returnInfo;
                                }else{
                                    $tetsMessage['alertMessage']['WhiteList2'] = "WhiteList2 matching normal! ".$returnInfo;
                                }
                            }
                        } elseif ($whiteList2Type == 'STRING') {
                            if (strpos($res, $whiteList2) === false){
                                $messagearr['alertMessage']['WhiteList2'] = "WhiteList2 is Not matching".$returnInfo;
                                $tetsMessage['alertMessage']['WhiteList2'] = "WhiteList2 is Not matching".$returnInfo;
                            }else{
                                $tetsMessage['alertMessage']['WhiteList2'] = "WhiteList2 matching normal! ".$returnInfo;
                            }
                        }else{
                            $messagearr['alertMessage']['WhiteList2'] = "WhiteList2Type is Not Set as REGEX or STRING".$returnInfo;
                            $tetsMessage['alertMessage']['WhiteList2'] = "WhiteList2Type is Not Set as REGEX or STRING".$returnInfo;
                        }
                    }
                    $whiteList3 = isset($curlData['WhiteList3']['Value']) && !empty($curlData['WhiteList3']['Value']) ? $curlData['WhiteList3']['Value'] : '';
                    $whiteList3Type = isset($curlData['WhiteList3']['ContentType']) && !empty($curlData['WhiteList3']['ContentType']) ? $curlData['WhiteList3']['ContentType'] : '';
                    if (!empty($whiteList3)) {
                        if ($whiteList3Type == 'REGEX') {
                            $matches = @preg_match($whiteList3, $res);
                            $tmpAbnormal = error_get_last();
                            if (!empty($tmpAbnormal)){
                                $messagearr['alertMessage']['WhiteList3'] = "WhiteList3 grammar abnormal!".$returnInfo; 
                                $tetsMessage['alertMessage']['WhiteList3'] = "WhiteList3 grammar abnormal!".$returnInfo;
                            }else{
                                if (!$matches){
                                    $messagearr['alertMessage']['WhiteList3'] = "WhiteList3 is Not matching!".$returnInfo;
                                    $tetsMessage['alertMessage']['WhiteList3'] = "WhiteList3 is Not matching!".$returnInfo;
                                }else{
                                    $tetsMessage['alertMessage']['WhiteList3'] = "WhiteList3 matching normal! ".$returnInfo;
                                }
                            }
                        } elseif ($whiteList3Type == 'STRING') {
                            if (strpos($res, $whiteList3) === false){
                                $messagearr['alertMessage']['WhiteList3'] = "WhiteList3 is Not matching".$returnInfo;
                                $tetsMessage['alertMessage']['WhiteList3'] = "WhiteList3 is Not matching".$returnInfo;
                            }else{
                                $tetsMessage['alertMessage']['WhiteList3'] = "WhiteList3 matching normal! ".$returnInfo;
                            }
                        }else{
                            $messagearr['alertMessage']['WhiteList3'] = "WhiteList3Type is Not Set as REGEX or STRING".$returnInfo;
                            $tetsMessage['alertMessage']['WhiteList3'] = "WhiteList3Type is Not Set as REGEX or STRING".$returnInfo;
                        }
                    }

                    //判断黑名单
                    $blackList = isset($curlData['BlackList']['Value']) && !empty($curlData['BlackList']['Value']) ? $curlData['BlackList']['Value'] : '';
                    $blackListType = isset($curlData['BlackList']['ContentType']) && !empty($curlData['BlackList']['ContentType']) ? $curlData['BlackList']['ContentType'] : '';
                    if (!empty($blackList)) {
                        if ($blackListType == 'REGEX') {
                            $matches = @preg_match($blackList, $res);
                            $tmpAbnormal = error_get_last();
                            if (!empty($tmpAbnormal)){
                                $messagearr['alertMessage']['BlackList'] = "BlackList grammar abnormal!".$returnInfo;
                                $tetsMessage['alertMessage']['BlackList'] = "BlackList grammar abnormal!".$returnInfo;
                            }else{
                                if ($matches){
                                    $messagearr['alertMessage']['BlackList'] = "Match to BlackList!".$returnInfo;
                                    $tetsMessage['alertMessage']['BlackList'] = "Match to BlackList!".$returnInfo;
                                }else{
                                    $tetsMessage['alertMessage']['BlackList'] = "BlackList is Not matching!".$returnInfo;
                                }
                            }
                        } elseif ($blackListType == 'STRING') {
                            if (!strpos($res, $blackList) === false){
                                $messagearr['alertMessage']['BlackList'] = "BlackList is Not matching!".$returnInfo;
                                $tetsMessage['alertMessage']['BlackList'] = "BlackList is Not matching!".$returnInfo;
                            }else{
                                $tetsMessage['alertMessage']['BlackList'] = "Match to BlackList!".$returnInfo;
                            }
                        }else{
                            $messagearr['alertMessage']['BlackList'] = "BlackListType is Not Set as REGEX or STRING".$returnInfo;
                            $tetsMessage['alertMessage']['BlackList'] = "BlackListType is Not Set as REGEX or STRING".$returnInfo;
                        }
                    }
                    $blackList2 = isset($curlData['BlackList2']['Value']) && !empty($curlData['BlackList2']['Value']) ? $curlData['BlackList2']['Value'] : '';
                    $blackList2Type = isset($curlData['BlackList2']['ContentType']) && !empty($curlData['BlackList2']['ContentType']) ? $curlData['BlackList2']['ContentType'] : '';
                    if (!empty($blackList2)) {
                        if ($blackList2Type == 'REGEX') {
                            $matches = @preg_match($blackList2, $res);
                            $tmpAbnormal = error_get_last();
                            if (!empty($tmpAbnormal)){
                                $messagearr['alertMessage']['BlackList2'] = "BlackList2 grammar abnormal!".$returnInfo; 
                                $tetsMessage['alertMessage']['BlackList2'] = "BlackList2 grammar abnormal!".$returnInfo;
                            }else{
                                if ($matches){
                                    $messagearr['alertMessage']['BlackList2'] = "Match to BlackList2!".$returnInfo;
                                    $tetsMessage['alertMessage']['BlackList2'] = "Match to BlackList2!".$returnInfo;
                                }else{
                                    $tetsMessage['alertMessage']['BlackList2'] = "BlackList2 is Not matching! ".$returnInfo;
                                }
                            }
                        } elseif ($blackList2Type == 'STRING') {
                            if (!strpos($res, $blackList2) === false){
                                $messagearr['alertMessage']['BlackList2'] = "BlackList2 is Not matching!".$returnInfo;
                                $tetsMessage['alertMessage']['BlackList2'] = "BlackList2 is Not matching!".$returnInfo;
                            }else{
                                $tetsMessage['alertMessage']['BlackList2'] = "Match to BlackList2!".$returnInfo;
                            }
                        }else{
                            $messagearr['alertMessage']['BlackList2'] = "BlackList2Type is Not Set as REGEX or STRING".$returnInfo;
                            $tetsMessage['alertMessage']['BlackList2'] = "BlackList2Type is Not Set as REGEX or STRING".$returnInfo;
                        }
                    }
                }
            } else {
                $messagearr['alertMessage']['error'] = curl_error($ch) . "error in recordId ". $data['Id'];
                $tetsMessage['alertMessage']['error'] = curl_error($ch) . "error in recordId ". $data['Id'];
            }
            // 关闭CURL资源，并且释放系统资源
            curl_close($ch);
            if (!empty($messagearr)) {
                $data['NotifyType'] = isset($data['NotifyType']) && !empty($data['NotifyType']) ? $data['NotifyType'] : 'MAIL';
                $messagearr['SendType'] = $data['NotifyType']; //消息发送类型
                $messagearr['NotifyObject'] = $NotifyObject;
            }
            return $isTest ? $tetsMessage : $messagearr;
        }
    }

    public static function send_caveat_message($data) {
        global $objMysql;
        $NotifyObject = isset($data['NotifyObject']) ? json_decode($data['NotifyObject'],true) : array();
        $Addressee = isset($NotifyObject['Addressee']) && !empty($NotifyObject['Addressee']) ? $NotifyObject['Addressee'] : array();
        if ( !empty($Addressee) && is_array($Addressee)) {
            $tmpString = implode("', '", $Addressee);
            $sql = "SELECT Mail 
                    FROM mail_list 
                    WHERE `Status` = 'ACTIVE' AND id IN ('{$tmpString}')";
            $r = $objMysql->getRows($sql);
            if (empty($r)) {
                $to = '1262233230@qq.com';
            } else {
                $to = '';
                foreach ($r as $v) {
                    $to .= $v['Mail'] . ',';
                }
                $to = rtrim($to, ",");
            }
        } else {
            $to = '1262233230@qq.com';
        }

        if (isset($NotifyObject['CC']) && is_array($NotifyObject['CC'])) {
            $tmpString = implode("', '", $NotifyObject['CC']);
            $sql = "SELECT Mail 
                    FROM mail_list 
                    WHERE `Status` = 'ACTIVE' AND id IN ('{$tmpString}')";
            $r = $objMysql->getRows($sql);
            $cc = '';
            foreach ($r as $v) {
                $cc .= $v['Mail'] . ',';
            }
            $cc = rtrim($cc, ",");
        } else {
            $cc = '';
        }
        // todo 邮件信息整理，使用新数组

        $tmpMessage = isset($data['alertMessage']) ? $data['alertMessage'] : array();
        $subject = isset($NotifyObject['commonTitle']) ? $NotifyObject['commonTitle'] : 'Title';
        $body = isset($NotifyObject['commonBody']) ? $NotifyObject['commonBody']. "\r\n" : 'Body';
        foreach ($tmpMessage as $k => $v) {
            $body .= $k . ":" . $v . "\r\n";
        }
        $messWhether = SendMail::SendUtf8Html($to, $cc, $subject, $body);
        return $messWhether;
    }

    public static function get_cookie_by_loginId($data){
        if (empty($data) || !isset($data['LoginId'])) return;

        $filename = $data['LoginId']."--".time()."--cookie.txt";
        $cookiePath = dirname(dirname(__FILE__))."/data/cookie/".$filename;
        if (!file_exists($cookiePath)) {
            file_put_contents($cookiePath,'');
        }
        $Url = isset($data['LoginData']['Url']['Value']) && !empty($data['LoginData']['Url']['Value']) ? $data['LoginData']['Url']['Value'] : "";
        $Params = isset($data['LoginData']['Params']['Value']) && !empty($data['LoginData']['Params']['Value']) ? $data['LoginData']['Params']['Value'] : "";
        $Method = isset($data['LoginData']['Method']['Value']) && !empty($data['LoginData']['Method']['Value']) ? $data['LoginData']['Method']['Value'] : "";

        $loginMessage = array();
        $returnInfo = " in record_list:id = " . $data['LoginId'];
        if ( empty($Url) || empty($Method)){
            $loginMessage['UrlOrMethodNot'] = "login Url or Method does not exist ".$returnInfo;
        }

        $ch = curl_init();//初始化curl模块 
        if ($Method == 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_URL, $Url . '?' . $Params);  //请求的URL 若有get参数 则直接拼上去
        } elseif ($Method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_URL, $Url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $Params);
        }else{
            $loginMessage['MethodNot'] = "login Method is not set as GET or POST ".$returnInfo;
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);//是否自动显示返回的信息 
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiePath); //设置Cookie信息保存在指定的文件中
        
        $res = curl_exec($ch);//执行cURL 

        $loginData = array();
        if(!empty($loginMessage)) $loginData['loginMessage'] = $loginMessage;
        if($res === false){
            $loginMessage['error'] = curl_error($ch) . "error in recordId ". $data['LoginId'];
        }else{
            $loginData['cookie'] = $cookiePath;
        }
        curl_close($ch);//关闭cURL资源，并且释放系统资源 
        return $loginData;
    }
}