<?php

class DB
{

    public $connection;
    protected $last_id;

    public function __construct($host, $user, $password, $db_name)
    {
        try
        {
            $this->connection = new PDO("mysql:host={$host};dbname={$db_name}", $user, $password);
            $this->connection->exec("set names utf8");
        }
        catch (PDOException $e)
        {
            Result::setError($e->getTraceAsString());
            Result::showResult();
        }
    }

}
