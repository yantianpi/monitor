<?php

class PdoMysql {
    private $host = "";
    private $database = "";
    private $user = "";
    private $password = "";
    private $sth = null;
    private $pdo = null;
    public $raiseErrorMode = DEBUG_MODE;

    public function __construct($database = PROD_DB_NAME, $host = PROD_DB_HOST, $user = PROD_DB_USER, $password = PROD_DB_PASS, $socket = "") {
        $this->host = $host;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;
        $this->socket = $socket;
        $this->connect();
    }

    public function connect() {
        if ($this->socket && ($this->host == "localhost" || $this->host == "127.0.01")) {
            $pdo = "mysql:unix_socket=" . $this->socket . ";host=" . $this->host . ";dbname=" . $this->database;
        } else {
            $pdo = "mysql:host=" . $this->host . ";dbname=" . $this->database;
        }

        try {
            $this->pdo = new PDO($pdo, $this->user, $this->password);
        } catch (PDOException $e) {
            self::raiseError("Connect failed, " . $this->host . ", " . $e->getMessage(), __FILE__, __LINE__);
        }

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if (defined('TIME_ZONE') && (!defined("IGNORE_MYSQL_TIMEZONE") || IGNORE_MYSQL_TIMEZONE == false)) {
            $this->pdo->exec("SET time_zone = '" . TIME_ZONE . "'");
        }
        if (defined('MYSQL_ENCODING')) {
            $this->pdo->exec("SET NAMES '" . MYSQL_ENCODING . "'");
        }
    }

    public function query($sql) {
        try {
            $this->sth = $this->pdo->query($sql);
        } catch (PDOException $e) {
            self::raiseError("Query failed, " . $sql . ", " . $e->getMessage() . ", " . $this->pdo->errorCode(), __FILE__, __LINE__);
        }
        return $this->sth;
    }

    public function getRow($sth, $fetchType = PDO::FETCH_ASSOC) {
        return $sth->fetch($fetchType);
    }

    public function getLastInsertId() {
        return $this->pdo->lastInsertId();
    }

    public function getFirstRow($sql) {
        $sth = $this->query($sql);
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        if (empty($row)) {
            $row = array();
        }
        $this->freeResult($sth);
        return $row;
    }

    public function getFirstRowColumn($sql, $column_number = 0) {
        $sth = $this->query($sql);
        $result = $sth->fetchColumn($column_number);
        $this->freeResult($sth);
        return $result;
    }

    public function close() {
        $this->pdo = null;
    }

    public function getRows($sql, $keyname = "", $foundrows = false) {
        if ($foundrows && strpos(substr($sql, 0, 30), "SQL_CALC_FOUND_ROWS") === false) {
            if (stripos($sql, "select") === 0) {
                $sql = "select SQL_CALC_FOUND_ROWS" . substr($sql, 6);
            }
        }
        $sth = $this->query($sql);
        $arr_return = array();
        if ($keyname) {
            $keys = explode(",", $keyname);
        }

        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            if ($keyname) {
                $arr_temp = array();
                foreach ($keys as $key) {
                    $arr_temp[] = $row[$key];
                }
                $key_value = implode("\t", $arr_temp);
                $arr_return[$key_value] = $row;
            } else {
                $arr_return[] = $row;
            }
        }
        $sth->closeCursor();
        return $arr_return;
    }

    public function getNumRows($qryId = "") {
        if (is_resource($qryId)) {
            return @mysql_num_rows($qryId);
        }
        return @mysql_num_rows($this->queryID);
    }

    public function getAffectedRows() {
        return $this->sth->rowCount();
    }

    public function freeResult(&$sth) {
        $sth->closeCursor();
    }

    public function getFoundRows() {
        $this->FOUND_ROWS = $this->getFirstRowColumn("SELECT FOUND_ROWS()");
        if (!is_numeric($this->FOUND_ROWS)) {
            $this->FOUND_ROWS = 0;
        }
        return $this->FOUND_ROWS;
    }

    public function getCreateTableSql($_table_name) {
        $sql = "SHOW CREATE TABLE `$_table_name`";
        return $this->getFirstRowColumn($sql, 1);
    }

    public function moveTable($_table_name_from, $_table_name_to, $_table_name_backup = "") {
        $arr_sql_rename = array();
        if ($this->isTableExisting($_table_name_to)) {
            if ($_table_name_backup) {
                $this->dropTable($_table_name_backup);
                $arr_sql_rename[] = "`$_table_name_to` to `$_table_name_backup`";
            } else {
                $this->dropTable($_table_name_to);
            }
        }
        $arr_sql_rename[] = "`$_table_name_from` to `$_table_name_to`";
        $sql = "rename table " . implode(",", $arr_sql_rename);
        return $this->query($sql);
    }

    public function swapTable($_table_name_1, $_table_name_2, $_table_name_swap = "") {
        //the max length of mysql table name is 64
        if (!$_table_name_swap) {
            $_table_name_swap = "swap_" . $_table_name_1 . "_" . $_table_name_2;
            if (strlen($_table_name_swap) > 50) {
                $_table_name_swap = substr($_table_name_swap, 0, 50);
            }
            $_table_name_swap .= "_" . time();
        }

        $this->dropTable($_table_name_swap);

        $arr_sql_rename = array();
        $arr_sql_rename[] = "`$_table_name_1` to `$_table_name_swap`";
        $arr_sql_rename[] = "`$_table_name_2` to `$_table_name_1`";
        $arr_sql_rename[] = "`$_table_name_swap` to `$_table_name_2`";
        $sql = "rename table " . implode(",", $arr_sql_rename);
        return $this->query($sql);
    }

    public function isTableExisting($_table_name) {
        $sql = "SHOW TABLES LIKE '$_table_name'";
        if ($this->getFirstRowColumn($sql)) {
            return true;
        }
        return false;
    }

    public function showTables($_table_names) {
        $arr_return = array();
        $sql = "SHOW TABLES LIKE '$_table_names'";
        $arr = $this->getRows($sql);
        foreach ($arr as $row) {
            $arr_return[] = current($row);
        }
        return $arr_return;
    }

    public function dropTables($_table_names) {
        //support % in table name
        $tables = $this->showTables($_table_names);
        $this->dropTable($tables);
    }

    public function getTableIndex($_table_name) {
        //Table,Non_unique,Key_name,Seq_in_index,Column_name,Index_type,Sub_part
        $arr_return = array();
        $sql = "SHOW INDEX FROM `$_table_name`";
        $arrRow = $this->getRows($sql);
        foreach ($arrRow as $row) {
            $index_name = $row["Key_name"]; //PRIMARY,index1,index2...
            $seq_in_index = $row["Seq_in_index"]; //1,2,3,4,...
            $arr_return[$index_name]["details"][$seq_in_index - 1] = $row;
            $arr_return[$index_name]["Index_type"] = $row["Index_type"]; //BTREE,FULLTEXT
            $arr_return[$index_name]["Non_unique"] = $row["Non_unique"]; //0:unique index,1:normal index

            $column_with_sub_part = "`" . $row["Column_name"] . "`";
            if (is_numeric($row["Sub_part"])) {
                $column_with_sub_part .= "(" . $row["Sub_part"] . ")";
            }

            $arr_return[$index_name]["arr_column"][] = $row["Column_name"];
            $arr_return[$index_name]["arr_column_with_sub_part"][] = $column_with_sub_part;
        }

        foreach ($arr_return as $index_name => $col_index) {
            $columns = implode(",", $col_index["arr_column_with_sub_part"]);
            if ($index_name == "PRIMARY") {
                $arr_return[$index_name]["dropsql"] = "DROP PRIMARY KEY";
                $arr_return[$index_name]["addsql"] = "ADD PRIMARY KEY ($columns)";
            } elseif ($col_index["Index_type"] == "FULLTEXT") {
                $arr_return[$index_name]["dropsql"] = "DROP KEY `$index_name`";
                $arr_return[$index_name]["addsql"] = "ADD FULLTEXT `$index_name` ($columns)";
            } elseif ($col_index["Non_unique"] == 1) {
                $arr_return[$index_name]["dropsql"] = "DROP KEY `$index_name`";
                $arr_return[$index_name]["addsql"] = "ADD INDEX `$index_name` ($columns)";
            } else {
                $arr_return[$index_name]["dropsql"] = "DROP KEY `$index_name`";
                $arr_return[$index_name]["addsql"] = "ADD UNIQUE `$index_name` ($columns)";
            }
        }

        if (!empty($arr_return)) {
            $this->lastTableIndexInfo[$_table_name] = $arr_return;
        }
        return $arr_return;
    }

    public function dropAllIndex($_table_name, $_index_info = "") {
        return $this->dropIndex($_table_name, $_index_info);
    }

    public function addAllIndex($_table_name, $_index_info = "") {
        return $this->addIndex($_table_name, $_index_info);
    }

    public function dropIndex($_table_name, $_index_info = "", $_arr_index_name = array()) {
        return $this->dropOrAddIndex("drop", $_table_name, $_index_info, $_arr_index_name);
    }

    public function addIndex($_table_name, $_index_info = "", $_arr_index_name = array()) {
        return $this->dropOrAddIndex("add", $_table_name, $_index_info, $_arr_index_name);
    }

    public function dropOrAddIndex($_act, $_table_name, $_index_info = "", $_arr_index_name = array()) {
        if ($_index_info === "") {
            $_index_info = $this->getTableIndex($_table_name);
        }
        if (empty($_index_info)) {
            return true;
        }
        $arr_drop_sql = array();
        foreach ($_index_info as $index_name => $index) {
            if (empty($_arr_index_name)) {
                $arr_drop_sql[] = $index[$_act . "sql"];
            } else {
                if (in_array($index_name, $_arr_index_name)) {
                    $arr_drop_sql[] = $index[$_act . "sql"];
                }
            }

        }
        $sql = "ALTER TABLE `$_table_name` " . implode(",", $arr_drop_sql);
        $this->query($sql);
    }

    /**
     * 创建表$_table_name_2,表定义与$_table_name_1一样
     *
     * @param $_table_name_1
     * @param $_table_name_2
     */
    public function duplicateTable($_table_name_1, $_table_name_2) {
        $sql = $this->getCreateTableSql($_table_name_1);
        $search_text = "CREATE TABLE `$_table_name_1`";
        $pos = strpos($sql, $search_text);
        if ($pos === false || $pos > 0) {
            die("something not right here:$sql");
        }
        $sql = "CREATE TABLE `$_table_name_2`" . substr($sql, strlen($search_text));
        $this->query($sql);
    }

    public function dropTable($_table_name, $_is_temp_table = false) {
        if (is_array($_table_name)) {
            $_table_name = implode(",", $_table_name);
        }
        $str_temp_table = $_is_temp_table ? "TEMPORARY" : "";
        $sql = "drop $str_temp_table table if exists $_table_name";
        $this->query($sql);
    }

    public function getFieldNames($_table_name) {
        $sql = "desc $_table_name";
        $fields = $this->getRows($sql, "Field");
        $arr = array();
        foreach ($fields as $key => $val) {
            $arr[$key] = $key;
        }
        return $arr;
    }

    public function raiseError($errorMsg = "", $scripts = __FILE__, $line = __LINE__) {
        $this->errorMsg = $errorMsg;
        $this->time = date("Y-m-d H:i:s");
        $this->scriptName = $scripts;
        $this->line = $line;
        self::setErrorLog();

        if ($this->raiseErrorMode) {
            echo "ErrorMsg: {$this->errorMsg}\n";
            echo "Time: {$this->time}\n";
            echo "ScriptName: {$this->scriptName}\n";
            echo "BackTrace: \n" . implode("\n", self::getDebugBacktrace("\t"));

            exit;
        } else {
            echo "<b>500 Internal Error</b>";
            exit;
        }
    }

    public function setErrorLog() {
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $req = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        $logString = "{$this->time}\t{$this->scriptName}\t{$this->line}\t$ip\t$req\t$ref\t{$this->errorMsg}\n";
        if (defined("LOG_LOCATION")) {
            @error_log($logString, 3, LOG_LOCATION . "sqlerror.log");
        }
    }

    public function getDebugBacktrace($prefix = "") {
        $debug_backtrace = debug_backtrace();
        krsort($debug_backtrace);
        foreach ($debug_backtrace as $k => $v) {
            if ($v["function"] != __FUNCTION__) {
                $result[] = $prefix . $v["file"] . " => " . $v["class"] . " => " . $v["function"] . " => " . $v["line"];
            }
        }
        return $result;
    }
}

?>