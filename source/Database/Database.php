<?php

namespace Widepay\Scraping\Database;


class Database
{
    public $connect;
   
    public function __construct()
    {
        try {
            $this->connect = new \PDO(DSN, USER, PASSWD);
            $this->connect->prepare("SET NAMES utf8");
        } catch (PDOException $i) {
            die("Erro: <code>" . $i->getMessage() . "</code>");
        }
    }
}