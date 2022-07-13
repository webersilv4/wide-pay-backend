<?php

    namespace Source\App;

    use Src\Models\User;

    class Web
    {
        function home($data)
        {
            echo "Hello Zizal";
        }

        function read($data)
        {
            $user = new User();
            $list = $user->find()->fetch(true);

            var_dump($user);
        }
    }

