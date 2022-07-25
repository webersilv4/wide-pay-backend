<?php

    namespace Widepay\Scraping\Models;

    use  Widepay\Scraping\Database\Database;

    class Model extends Database
    {
        /*
         *  [SQL][bindValues] 
         *  select('SELECT * FROM users WHERE id = ? and hashUser = ?', [1,11]); 
         */
        public function myQuery($sql, $bind){
            
            $smtp = $this->connect->prepare("$sql");
            
            foreach ($bind as $bindV => $key) :
                $smtp->bindValue($bindV + 1, $key);
            endforeach;   
            
            $smtp->execute();
            
            return $smtp;
        }
    }