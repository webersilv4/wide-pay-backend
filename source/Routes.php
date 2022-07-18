<?php

    use CoffeeCode\Router\Router;
    
    $router = new Router(URL_BASE);

    /*
     * Conteollers
     */
    $router->namespace("Widepay\Scraping\App");

    /*
     * Controller HomePage
     */

    $router->group("api"); // Faz com que as rotas tenham um prefixo EX: (api/v1/[url])
    
    
    /*
     * Renderizando urls
     */
    $router->get('/', "Web:autoScrapingUrls", true); //['/', "Web:home", true] - ['url acessada', 'controlador', 'middleweare'];
    
    /*
     * Crinado novas urls para o usuario
     */
    $router->post('/new-url', "Web:createNewUrl"); //['/', "Web:home", true] - ['url acessada', 'controlador', 'middleweare'];

    /*
     * Criando nova conta de usuário
     */
    $router->post('/create-account', "Web:createAccount");


    /*
     * Logando na conta de usuario
     */
    $router->post('/login', "Web:loginAccount");


    $router->dispatch();


    if ($router->err()){
        $router->err();
    }