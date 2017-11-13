<?php

class Result
{

    protected static $result;
    protected static $error;

    public static function setResult($key, $value)
    {
        self::$result[$key] = $value;
    }

    public static function setError($result)
    {
        self::$error = $result;
    }

    public static function showResult()
    {
        $object = new stdClass;
        $object->result = self::$result;
        $object->error = self::$error;
        echo json_encode($object);
        self::$result = null;
        self::$error = null;
        die;
    }

}
