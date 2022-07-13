<?php

    
    require __DIR__ . "/vendor/autoload.php";
    require __DIR__ . '/source/App/Api/Web.php';

    use CoffeeCode\Router\Router;
    
    $router = new Router(URL_BASE);

    /*
     * Conteollers
     */
    $router->namespace("Source\App");

    /*
     * Controller HomePage
     */
    $router->group(null);
    $router->get('/api', "Web:home");


    // $router->post('/apis', function ($data)
    // {
    //     $data = ["realHttp" => $_SERVER["REQUEST_METHOD"]] + $data;
    //     echo json_encode($data);
    // });


    $router->dispatch();


    if ($router->error()) {
        http_response_code(404);
        echo json_encode(array("error"=>"Not found"));
    }