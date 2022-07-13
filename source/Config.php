<?php

    define ("URL_BASE", "http://localhost/Projects/project-wide-pay");

    define ("DATA_LAYER_CONFIG", [
        "driver" => "mysql" ,
        "host" => "localhost" ,
        "port" => "80" ,
        "dbname" => "soubr" ,
        "username" => "root" ,
        " passwd" => "" ,
        "opções" => [
            PDO :: MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" ,
            PDO :: ATTR_DEFAULT_FETCH_MODE => PDO :: FETCH_OBJ ,
            PDO :: ATTR_CASE => PDO :: CASE_NATURAL
        ]
    ]);