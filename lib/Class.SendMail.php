<?php

class SendMail {
    public $mailBoxs = array();

    static function SendUtf8Html($to, $cc, $subject, $body = "", $from = "teg") {
        $headers = array();
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf-8';
        $headers[] = "Content-Transfer-Encoding: base64";
        if (!empty($from))
            $headers[] = "From: {$from}";
        if (empty($to)) {
            return false;
        } else {
            $headers[] = "To: {$to}";
        }
        if (!empty($cc)) {
            $headers[] = "Cc: {$cc}";
        }
        $headers[] = "Date: " . date("r");
        $headers[] = "X-Mailer: Monitor Email Sender";
        list($msec, $sec) = explode(" ", microtime());
        $headers[] = "Message-ID: <" . date("YmdHis", $sec) . "." . ($msec * 1000000) . "." . md5($from . $subject) . ">";
        $str_header = implode("\r\n", $headers);
        $body = chunk_split(base64_encode($body));
        return mail('', $subject, $body, $str_header);
    }

    function sendmail_edm(&$_info) {
        $cookiejar = "r.bwe.io.cookie";
        if (defined("DATA_ROOT")) {
            $cookiejar = DATA_ROOT . $cookiejar;
        } else $cookiejar = "/tmp/" . $cookiejar;

        $mailSender = "http://edm.bwe.io/sendmail.php";
        $ch = curl_init($mailSender);
        curl_setopt($ch, CURLOPT_URL, $mailSender);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiejar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiejar);
        curl_setopt($ch, CURLOPT_USERAGENT, "sendmail_edm");
        curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_info);

        //$this->sendmail_edm_get_curl_handle($_info,$ch);
        $pagecontent = curl_exec($ch);
        $curl_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($curl_code != 200)
            return false;
        if (substr($pagecontent, 0, 1) != "1")
            return false;
        return true;
    }


    /***
     * @param $to  收件人
     * @param string $cc 抄送人
     * @param string $from 寄件人
     * @param string $title 邮件标题
     * @param string $content 正文内容
     * @param string $attachContent 附件内容
     * @param string $attachName 附件名称（中文会乱码）
     * @param string $attachMark 附件后缀类型，如csv
     * @return bool
     */

    public static function sendMailWithAttach($to, $cc = '', $from = '', $title = '', $content = '', $attachContent = '', $attachName = '', $attachMark = '') {
        $headers = array();
        $boundary = uniqid("");
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = "Content-type: multipart/mixed;charset=utf-8;boundary=\"{$boundary}\"";
        $headers[] = "Content-Transfer-Encoding: base64";

        if (!empty($from))
            $headers[] = "From: {$from}";
        if (empty($to)) {
            return false;
        } else {
            $headers[] = "To: {$to}";
        }
        if (!empty($cc))
            $headers[] = "Cc: {$cc}";
        $headers[] = "Date: " . date("r");
        $headers[] = "X-Mailer: Monitor Email Sender";
        $str_header = implode("\n", $headers);

        $content = chunk_split(base64_encode($content));
        $bodyArray = array();
        $bodyArray[] = "--{$boundary}";
        $bodyArray[] = "Content-type: text/plain; charset=utf-8";
        $bodyArray[] = "Content-Transfer-Encoding: base64";
        $bodyArray[] = "\n{$content}\n";
        if (!empty($attachContent)) {
            if (empty($attachName))
                $attachName = 'attachment' . date('YmdHis');
            if (empty($attachMark))
                $attachMark = 'csv';
            $attachMark = trim($attachMark, '.');
            $attachPostfix = Common::obtainFilePostfix($attachMark);
            $attachMark = trim($attachPostfix, '.');
            $attachMimeType = Common::obtainHeaderContentType($attachMark);

            $attachContent = chr(0xEF) . chr(0xBB) . chr(0xBF) . $attachContent;
            $attachContent = chunk_split(base64_encode($attachContent));
            $bodyArray[] = "--{$boundary}";
            $bodyArray[] = "Content-type: {$attachMimeType};charset=utf-8;";
            $bodyArray[] = "Content-disposition:attachment; filename={$attachName}{$attachPostfix}";
            $bodyArray[] = "Content-Transfer-Encoding: base64";
            $bodyArray[] = "\n{$attachContent}\n";
        }
        $bodyArray[] = "--{$boundary}--";
        $body = implode("\r\n", $bodyArray);
        $title = "=?UTF-8?B?" . base64_encode($title) . "?=";

        return mail('', $title, $body, $str_header);
    }


}
?>