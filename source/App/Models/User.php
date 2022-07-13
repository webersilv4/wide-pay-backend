<?php

    namespace Src\Models;

    use CoffeeCode\DataLayer\DataLayer;

    class ClassName extends DataLayer
    {
        function __construct () {
            parent::__construct("users", ["fisrt_name", "last_name", "email", "password"]);
        }
    }
    