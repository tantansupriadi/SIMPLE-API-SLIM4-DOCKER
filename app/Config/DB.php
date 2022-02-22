<?php

namespace App\Config;
use \PDO;

class DB
{
    private $host = 'db';
    private $user = 'root';
    private $pass = '12345';
    private $dbname = 'mini-wallet';

    public function connect()
    {
        $conn_str = "mysql:host=$this->host;dbname=$this->dbname";
        $conn = new PDO($conn_str, $this->user, $this->pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }
}