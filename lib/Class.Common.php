<?php

class Common {
    public static function isMagicOpen() {
        if(@get_magic_quotes_gpc())	{
            return true;
        } else {
            return false;
        }
    }

    public static function obtainVariable($name, $method) {
        $name = trim($name);
        $method = strtolower(trim($method));
        $supportMethodArray = array(
            'get' => 'get',
            'post' => 'post',
            'cookie' => 'cookie',
            'request' => 'request',
            'server' => 'server',
            'session' => 'session',
        );
        if (empty($name) || empty($method) || !isset($supportMethodArray[$method]))  {
            return '';
        } else {
            $tmpVar = '';
            switch ($method) {
                case 'get':
                    $tmpVar = isset($_GET[$name]) ? $_GET[$name] : '';
                    break;
                case 'post':
                    $tmpVar = isset($_POST[$name]) ? $_POST[$name] : '';
                    break;
                case 'cookie':
                    $tmpVar = isset($_COOKIE[$name]) ? $_COOKIE[$name] : '';
                    break;
                case 'request':
                    $tmpVar = isset($_REQUEST[$name]) ? $_REQUEST[$name] : '';
                    break;
                case 'server':
                    $tmpVar = isset($_SERVER[$name]) ? $_SERVER[$name] : '';
                    break;
                case 'session':
                    $tmpVar = isset($_SESSION[$name]) ? $_SESSION[$name] : '';
                    break;
                default:
                    ;
            }
            
            if(self::isMagicOpen())	 {
                $tmpVar = stripslashes($tmpVar);
            } else {
                // do nothing
            }
            return $tmpVar;
        }

    }

    /**
     * 返回两时间戳的差值格式化数据
     *
     * @param $time1 第一个时间戳
     * @param $time2 第二个时间戳
     * @return array 差值时间戳格式化数据数组
     */
    public static function timeDifference($time1,$time2) {
	    $cle = $time1 - $time2;
		$rtnArray = array(
            "minute" => 0,
            "hour" => 0,
            "day" => 0,
            "week" => 0,
            "month" => 0,
            "year" => 0,
            "firstbig" => false,
        );
	    if($cle > 0) {
            $rtnArray["firstbig"] = true;
        }
        $cle = abs($cle);
	    $cle_year = ceil($cle / 3600 / 24 / 365) - 1;
	    $cle_month = ceil($cle / 3600 / 24 / 30) - 1;
	    $cle_week = ceil($cle / 3600 / 24 / 7) - 1;
	    $cle_day = ceil($cle / 3600 / 24) - 1;
	    $cle_hour = ceil(($cle % (3600 * 24)) / 3600) - 1;
	    $cle_minute = ceil(($cle % (3600)) / 60) - 1;
	    
	    if ($cle_year > 0) {
            $rtnArray["year"] = $cle_year;
        }
	    if ($cle_month > 0) {
            $rtnArray["month"] = $cle_month;
        }
		if ($cle_week > 0) {
            $rtnArray["week"] = $cle_week;
        }
		if ($cle_day > 0) {
            $rtnArray["day"] = $cle_day;
        }
		if ($cle_hour > 0) {
            $rtnArray["hour"] = $cle_hour;
        }
		if ($cle_minute > 0) {
            $rtnArray["minute"] = $cle_minute;
        }
		return $rtnArray;
    }

    /**
     * 获取xml的数组表示形式
     *
     * @param $strXml
     * @return array
     */
    public static function getXmlData($strXml) {
        $arrayCode = array();
        $pos = strpos($strXml, 'xml');
        if ($pos) {
            $xmlCode = simplexml_load_string($strXml, 'SimpleXMLElement', LIBXML_NOCDATA);
            $arrayCode = self::convertObject($xmlCode);
        } else {
            // do nothing
        }
        return $arrayCode;
    }

    /**
     * 将对象的数组表示形式
     *
     * @param $obj 传入对象
     * @return array
     */
    public static function convertObject($obj) {
        $returnArray = array();
        if (is_object($obj)) {
            $returnArray = get_object_vars($obj);
        } elseif (is_array($obj)) {
            foreach ($obj as $key => $value) {
                $returnArray[$key] = self::convertObject($value);
            }
        } else {
            // do nothing
        }
        return $returnArray;
    }

    /**
     * 获取访问者ip地址信息
     *
     * @return bool|string 获取到ip,返回ip,否则，返回false
     */
    public static function getIpAddress() {
        $onlineip = '';
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $onlineip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
        $onlineip = $onlineipmatches[0] ? $onlineipmatches[0] : false;
        return $onlineip;
    }

    /**
     * 随机数生成器
     *
     * @param $length 最终生成的随机数长度
     * @param boolean $isNumeric 是否生成数字
     * @return mixed 返回生成的随机数
     */
    public static function random($length, $isNumeric = true) {
        if (PHP_VERSION < '4.2.0') {
            mt_srand((double) microtime() * 1000000);
        }
        $hash = '';
        $length = intval($length);
        if ($isNumeric) {
            $hash = sprintf('%0' . $length . 'd', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
            $max = strlen($chars) - 1;
            for ($i = 0; $i < $length; $i++) {
                $index = mt_rand(0, $max);
                $hash .= isset($chars[$index]) ? $chars[$index] : $chars[0];
            }
        }
        return $hash;
    }

    /**
     * 生成图片
     *
     * @param $srcFile
     * @param string $dstFile 生成图片地址
     * @param $dstW 生成图片的宽
     * @param $dstH 生成图片的高
     * @param int $fillblack
     * @param string $resample
     */
    public static function makeThumb($srcFile, $dstFile="null", $dstW, $dstH, $fillblack=0, $resample="null") {
        $data = getimagesize($srcFile);
        switch ($data[2]) {
            case 1:
                $im = @ImageCreateFromGIF($srcFile);
                break;
            case 2:
                $im = @ImageCreateFromJpeg($srcFile);
                break;
            case 3:
                $im = @ImageCreateFromPNG($srcFile);
                break;
            default:
                $im = @imagecreatefromgd($srcFile);
                break;
        }
        $srcW = $data[0]; //src width
        $srcH = $data[1]; //src height
        $dstX = 0; //dst x
        $dstY = 0; //dst y
        $x_ratio = $dstW / $srcW;
        $y_ratio = $dstH / $srcH;
        if (($srcW <= $dstW) && ($srcH <= $dstH)) {
            $fdstW = $srcW;
            $fdstH = $srcH;
        } elseif (($x_ratio * $srcH) < $dstH) {
            $fdstH = ceil($x_ratio * $srcH);
            $fdstW = $dstW;
        } else {
            $fdstW = ceil($y_ratio * $srcW);
            $fdstH = $dstH;
        }
        if ($fillblack == 0) {
            $dstW = $fdstW;
            $dstH = $fdstH;
            $dstX = 0;
            $dstY = 0;
        }
        $ni = ImageCreateTrueColor($dstW, $dstH); // 创建一个图片
        $white = ImageColorAllocate($ni, 255, 255, 255); // 为图片分配rgb颜色，返回颜色值
        ImageFilleDrectangle($ni, 0, 0, $dstW, $dstH, $white); // 图像填充矩形
        imagecolortransparent($ni, $white); // 图像设置透明色
        if (function_exists("ImageCopyResampled")) {
            ImageCopyResampled($ni, $im, $dstX, $dstY, 0, 0, $fdstW, $fdstH, $srcW, $srcH);
        } else {
            ImageCopyResample($ni, $im, $dstX, $dstY, 0, 0, $fdstW, $fdstH, $srcW, $srcH, $resample);
        }

        ImagePng($ni, $dstFile);

        ImageDestroy($ni);
        ImageDestroy($im);
        if (empty($dstFile))
            Header("Content-type: image/jpg");
    }

    /**
     * debug and die
     *
     * @param $data
     */
    public static function debugDump($data) {
        echo "<pre>";
        print_r($data);
        exit;
    }

    /**
     * debug and no die
     *
     * @param $data
     */
    public static function debugDumpNoDie($data) {
        echo "<pre>";
        print_r($data);
    }

    /**
     * 根据传入的表名和字段名，返回字段名对应的枚举列表
     *
     * @param string $table 表名
     * @param string $column 字段名
     * @return array 枚举列表
     */
    public static function obtainEnumList($table = '', $column = '') {
        global $objMysql;
        if (!is_object($objMysql) || empty($objMysql)) {
            Common::debugDump('connect database error');
        }
        $returnArray = array();
        if (empty($table) || empty($column)) {
            // do nothing
        } else {
            $sql = "SHOW COLUMNS FROM `{$table}` WHERE FIELD ='{$column}'";
            $columnInfo = $objMysql->getFirstRow($sql);
            $pattern = '/^enum\(/i';
            if (!empty($columnInfo) && isset($columnInfo['Type']) && preg_match($pattern, $columnInfo['Type']) == 1) {
                $enumStr = str_ireplace('enum(', '', $columnInfo['Type']);
                $enumStr = rtrim($enumStr, ')');
                $enumArray = explode(',', $enumStr);
                foreach ($enumArray as &$enumItem) {
                    $enumItem = trim($enumItem, "'");
                }
                unset($enumItem);
                $returnArray = $enumArray;
            }
        }
        return $returnArray;
    }


    /**
     * 传入一维数组，返回新数组。新数组的键名和键值相同，取值参考一维数组的键值
     *
     * @param array $tmpArray 一维数组
     * @return array 新数组
     */
    public static function mappingValueArray($tmpArray = array()) {
        $returnArray = array();
        if (is_array($tmpArray) && !empty($tmpArray)) {
            foreach ($tmpArray as $item) {
                $tmpItem = trim($item);
                $returnArray[$tmpItem] = $tmpItem;
            }
        }
        return $returnArray;
    }


    /**
     * 公用下载模块
     *
     * @param string $path 文件路径
     * @param string $contentTypeMark 文件标识
     * @return bool 无用
     */
    public static function exportGeneral($path = '', $contentTypeMark = '') {
        if (!file_exists($path)) {
            return false;
        } else {
            $contentType = self::obtainHeaderContentType($contentTypeMark);
            $userId = isset($_SESSION['userId']) ? intval($_SESSION['userId']) : 0;
            $prefix = '';
            $baseName = basename($path);
            $fileName = $prefix . $userId . time() . rand(10, 99) . $baseName;
            header("Content-Disposition:attachment;filename={$fileName}");
            header('Pragma:no-cache');
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
            header('Expires:0');
            header('Content-Description: File Transfer');
            header("Content-Type: {$contentType}");
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($path));
            @readfile($path);
            return true;
        }
    }

    /**
     * 返回指定mark的mime映射
     *
     * @param $mark
     * @return string mark对应mime映射
     */
    public static function obtainHeaderContentType($mark = '') {
        $mimetypes = array(
            'ez' => 'application/andrew-inset',
            'hqx' => 'application/mac-binhex40',
            'cpt' => 'application/mac-compactpro',
            'doc' => 'application/msword',
            'bin' => 'application/octet-stream',
            'dms' => 'application/octet-stream',
            'lha' => 'application/octet-stream',
            'lzh' => 'application/octet-stream',
            'exe' => 'application/octet-stream',
            'svg' => 'application/octet-stream',
            'class' => 'application/octet-stream',
            'so' => 'application/octet-stream',
            'dll' => 'application/octet-stream',
            'oda' => 'application/oda',
            'pdf' => 'application/pdf',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            'smi' => 'application/smil',
            'smil' => 'application/smil',
            'mif' => 'application/vnd.mif',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'wbxml' => 'application/vnd.wap.wbxml',
            'wmlc' => 'application/vnd.wap.wmlc',
            'wmlsc' => 'application/vnd.wap.wmlscriptc',
            'bcpio' => 'application/x-bcpio',
            'vcd' => 'application/x-cdlink',
            'pgn' => 'application/x-chess-pgn',
            'cpio' => 'application/x-cpio',
            'csh' => 'application/x-csh',
            'dcr' => 'application/x-director',
            'dir' => 'application/x-director',
            'dxr' => 'application/x-director',
            'dvi' => 'application/x-dvi',
            'spl' => 'application/x-futuresplash',
            'gtar' => 'application/x-gtar',
            'hdf' => 'application/x-hdf',
            'js' => 'application/x-javascript',
            'skp' => 'application/x-koan',
            'skd' => 'application/x-koan',
            'skt' => 'application/x-koan',
            'skm' => 'application/x-koan',
            'latex' => 'application/x-latex',
            'nc' => 'application/x-netcdf',
            'cdf' => 'application/x-netcdf',
            'sh' => 'application/x-sh',
            'shar' => 'application/x-shar',
            'swf' => 'application/x-shockwave-flash',
            'sit' => 'application/x-stuffit',
            'sv4cpio' => 'application/x-sv4cpio',
            'sv4crc' => 'application/x-sv4crc',
            'tar' => 'application/x-tar',
            'tcl' => 'application/x-tcl',
            'tex' => 'application/x-tex',
            'texinfo' => 'application/x-texinfo',
            'texi' => 'application/x-texinfo',
            't' => 'application/x-troff',
            'tr' => 'application/x-troff',
            'roff' => 'application/x-troff',
            'man' => 'application/x-troff-man',
            'me' => 'application/x-troff-me',
            'ms' => 'application/x-troff-ms',
            'ustar' => 'application/x-ustar',
            'src' => 'application/x-wais-source',
            'xhtml' => 'application/xhtml+xml',
            'xht' => 'application/xhtml+xml',
            'zip' => 'application/zip',
            'au' => 'audio/basic',
            'snd' => 'audio/basic',
            'mid' => 'audio/midi',
            'midi' => 'audio/midi',
            'kar' => 'audio/midi',
            'mpga' => 'audio/mpeg',
            'mp2' => 'audio/mpeg',
            'mp3' => 'audio/mpeg',
            'aif' => 'audio/x-aiff',
            'aiff' => 'audio/x-aiff',
            'aifc' => 'audio/x-aiff',
            'm3u' => 'audio/x-mpegurl',
            'ram' => 'audio/x-pn-realaudio',
            'rm' => 'audio/x-pn-realaudio',
            'rpm' => 'audio/x-pn-realaudio-plugin',
            'ra' => 'audio/x-realaudio',
            'wav' => 'audio/x-wav',
            'pdb' => 'chemical/x-pdb',
            'xyz' => 'chemical/x-xyz',
            'bmp' => 'image/bmp',
            'gif' => 'image/gif',
            'ief' => 'image/ief',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'jpe' => 'image/jpeg',
            'png' => 'image/png',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'djvu' => 'image/vnd.djvu',
            'djv' => 'image/vnd.djvu',
            'wbmp' => 'image/vnd.wap.wbmp',
            'ras' => 'image/x-cmu-raster',
            'pnm' => 'image/x-portable-anymap',
            'pbm' => 'image/x-portable-bitmap',
            'pgm' => 'image/x-portable-graymap',
            'ppm' => 'image/x-portable-pixmap',
            'rgb' => 'image/x-rgb',
            'xbm' => 'image/x-xbitmap',
            'xpm' => 'image/x-xpixmap',
            'xwd' => 'image/x-xwindowdump',
            'igs' => 'model/iges',
            'iges' => 'model/iges',
            'msh' => 'model/mesh',
            'mesh' => 'model/mesh',
            'silo' => 'model/mesh',
            'wrl' => 'model/vrml',
            'vrml' => 'model/vrml',
            'css' => 'text/css',
            'html' => 'text/html',
            'htm' => 'text/html',
            'asc' => 'text/plain',
            'txt' => 'text/plain',
            'rtx' => 'text/richtext',
            'rtf' => 'text/rtf',
            'sgml' => 'text/sgml',
            'sgm' => 'text/sgml',
            'tsv' => 'text/tab-separated-values',
            'wml' => 'text/vnd.wap.wml',
            'wmls' => 'text/vnd.wap.wmlscript',
            'etx' => 'text/x-setext',
            'xsl' => 'text/xml',
            'xml' => 'text/xml',
            'mpeg' => 'video/mpeg',
            'mpg' => 'video/mpeg',
            'mpe' => 'video/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            'mxu' => 'video/vnd.mpegurl',
            'avi' => 'video/x-msvideo',
            'movie' => 'video/x-sgi-movie',
            'ice' => 'x-conference/x-cooltalk',
            'csv' => 'text/csv',
        );
        $defaultmimetype = 'application/octet-stream';
        $mark = strtolower(trim($mark));
        if (isset($mimetypes[$mark])) {
            return trim($mimetypes[$mark]);
        } else {
            return $defaultmimetype;
        }
    }

    /**
     * 返回指定mark的文件后缀映射
     *
     * @param string $mark
     * @return string mark对应后缀映射
     */
    public static function obtainFilePostfix($mark = '') {
        $postfixArray = array(
            'csv' => '.csv',
            'doc' => '.doc',
            'txt' => '.txt',
            'zip' => '.zip',
        );
        $defaultPostfix = '.txt';
        $mark = strtolower(trim($mark));
        if (isset($postfixArray[$mark])) {
            return trim($postfixArray[$mark]);
        } else {
            return $defaultPostfix;
        }
    }

    /**
     * 产生随机数并返回
     *
     * @param int $mix 下限
     * @param int $max 上限
     * @param int $default 默认值
     * @return int 随机数
     */
    public static function obtainRandomDigit($mix = 0, $max = 20, $default = 10) {
        $mix = intval($mix);
        $max = intval($max);
        $returnInt = mt_rand($mix, $max);
        if ($returnInt < $mix || $returnInt > $max) {
            $returnInt = intval($default);
        } else {
            // do nothing
        }
        return $returnInt;
    }

    /**
     * 获取指定目录下所有文件路劲信息
     *
     * @param $dirPath
     * @param bool $recursive 是否检索子目录
     * @param bool $noFolder 返回结果集是否不包含子目录信息
     * @param bool $returnAbsolutePath 返回结果集值是否使用绝对路径
     * @param int $depth 递归层级
     * @return array
     */
    public static function listDir($dirPath, $recursive = true, $noFolder = true, $returnAbsolutePath = false, $depth=0) {
        static $topPath;
        $resultArray = array();
        $maxDepth = 3;
        if ($depth > $maxDepth) {
            return $resultArray;
        }
        $dirPath = (string) $dirPath;
        $dirPath = realpath($dirPath);
        $dirPath = str_replace('\\', '/', $dirPath);
        $topPath = ($depth === 0 || empty($topPath)) ? $dirPath : $topPath;
        if (is_dir($dirPath)) {
            $tmpArray = scandir($dirPath);
            foreach ($tmpArray as $fileName) {
                if ($fileName == '.' || $fileName == '..') {
                    continue;
                } else {
                    $fp = rtrim($dirPath, '/') . '/' . trim($fileName, '/');
                    if(!is_readable($fp)){
                        continue;
                    } else {
                        if (is_dir($fp)) {
                            $fp .= '/';
                            if($noFolder !== true){
                                $resultArray[$fp] = $returnAbsolutePath?$fp:ltrim(str_replace($topPath,'',$fp),'/');
                            }
                            if($recursive !== true){
                                continue;
                            }
                            $subFolderFiles = self::listDir($fp, $recursive, $noFolder, $returnAbsolutePath, $depth+1);
                            if(is_array($subFolderFiles) && !empty($subFolderFiles)) {
                                $resultArray = array_merge($resultArray, $subFolderFiles);
                            }
                        } else {
                            $resultArray[$fp] = $returnAbsolutePath ? $fp : ltrim(str_replace($topPath, '', $fp), '/');
                        }
                    }
                }
            }
        }
        return $resultArray;
    }

    /**
     * 远程服务器以某用户身份执行命令
     *
     * @param string $serverName 服务器名
     * @param $port 端口号
     * @param string $userName 用户名
     * @param string $password 密码
     * @param $command 命令
     */
    public static function execCommand($serverName = '', $port, $userName = '', $password = '', $command) {

        $connection = ssh2_connect($serverName, $port);
        $auth = ssh2_auth_password($connection, $userName, $password);
        $stream = ssh2_exec($connection, $command);
    }

    /**
     * 日志记录
     *
     * @param string $type
     * @param string $name
     * @param string $action
     * @param string $content
     * @param bool $hasAlert
     */
    public static function recordLog($type = '', $genre = '', $mapId = 0, $name = '', $action = '', $content = '', $hasAlert = 'NO') {
        global $objMysql;
        $tmpArray = self::obtainEnumList('log_list', 'Type');
        $logTypeArray = self::mappingValueArray($tmpArray);
        $tmpArray = self::obtainEnumList('log_list', 'Genre');
        $genreArray = self::mappingValueArray($tmpArray);
        $type = addslashes(trim($type));
        $genre = addslashes(trim($genre));
        $mapId = intval($mapId);
        $name = addslashes(trim($name));
        $action = addslashes(trim($action));
        $content = addslashes(trim($content));
        $hasAlert = $hasAlert == 'YES' ? 'YES' : 'NO';
        if (isset($logTypeArray[$type]) && isset($genreArray[$genre])) {
            $now = date('Y-m-d H:i:s');
            $sql = "INSERT IGNORE 
                    INTO log_list 
                    SET `Genre` = '{$genre}',
                    `MapId` = '{$mapId}',
                    `Type` = '{$type}',
                    `Name` = '{$name}',
                    `Action` = '{$action}',
                    Content = '{$content}',
                    HasAlert = '{$hasAlert}',
                    AddTime = '{$now}',
                    UpdateTime = '{$now}';";
            @$objMysql->query($sql);
        }
    }
}
?>
