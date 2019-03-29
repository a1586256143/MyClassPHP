<?php

namespace system;
class Log {

    protected static $file;
    // 日志内容器
    protected static $logs;

    public function __construct($config = array()) {
        self::$file = new File();
    }

    public static function addRecord($msg = null, $time = false) {
        if ($time) {
            $msg = Date::setDate(null, time()) . ' --- ' . $msg;
        }
        self::$logs[] = $msg;
    }

    public static function generator() {
        $logDir = Config('LOGDIR');
        var_dump(self::$logs);
    }
}