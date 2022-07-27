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
     * Mostrando URLS do usuario autenticado
     */
    $router->get('/render-urls', "Web:renderUrls", true);

    /*
     * Crinado novas urls para o usuario
     */
    $router->post('/new-url', "Web:createNewUrl"); //['/', "Web:home", true] - ['url acessada', 'controlador', 'middleweare'];

    /*
     * Alterar dados das urls do usuário
     */
    $router->put('/alter-url', "Web:alterUrl", true);

    /*
     * Deleta uma url de um usuário altenticado
     */
    $router->delete('/delete-url', "Web:deleteUrl", true);

    /*
     * Criando nova conta de usuário
     */
    $router->post('/signup', "Web:createAccount");

    /*
     * Logando na conta de usuario
     */
    $router->post('/signin', "Web:loginAccount");


    $router->dispatch();
