<?php

namespace CoffeeCode\Router;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getToken(): string
{
    $token = apache_request_headers();
    $token = preg_replace('/Bearer /i', '', isset($token['Authorization'])? $token['Authorization'] : '');

    isset($token)? $token: '';
    
    return $token;
}

/**
 * Class CoffeeCode Router
 *
 * @author Robson V. Leite <https://github.com/robsonvleite>
 * @package CoffeeCode\Router
 */
class Router extends Dispatch
{
    /**
     * Router constructor.
     *
     * @param string $projectUrl
     * @param null|string $separator
     */
    public function __construct(string $projectUrl, ?string $separator = ":")
    {
        parent::__construct($projectUrl, $separator);
        $this->setOrigins();
    }

    /**
     * Router Set origin.
     *
     */
    public function setOrigins()
    {
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");         

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }

        header("Access-Control-Allow-Origin: *");
    }

    /**
     * @param string $route
     * @param $handler
     * @param string|null $name
     */
    public function post(string $route, $handler, bool $middleweare = false): void
    {
        $arrayRoute = [];
        $newArrayRoute = array_push($arrayRoute, $route);
        $new = explode('/', $_SERVER['REQUEST_URI']);

        foreach ($arrayRoute as $key => $value) {
            $lastParamRequest = end($new);
            $value = str_replace("/", "", $value);
            
            if ($value === $lastParamRequest) {
                 if ($middleweare === false){
                    $this->addRoute("POST", $route, $handler, $middleweare);
                }else{

                    try {
                        if (!empty(JWT::decode(getToken(), new Key(JWT_KEY, 'HS256')))){
                            $this->addRoute("POST", $route, $handler, $middleweare);
                        }else{$this->err(401, 'Você precisa estar autenticado para acessar essa rota.');}
                    } catch (\Throwable $e) {
                        $this->err(401, 'Token inválido.');
                    }

                }
            }
    
        }
    }

    /**
     * @param string $route
     * @param $handler
     * @param string|null $name
     */
    public function get(string $route, $handler, bool $middleweare = false): void
    {   

        $arrayRoute = [];
        $newArrayRoute = array_push($arrayRoute, $route);
        $new = explode('/', $_SERVER['REQUEST_URI']);

        foreach ($arrayRoute as $key => $value) {
            $lastParamRequest = end($new);
            $value = str_replace("/", "", $value);
            
            if ($value === $lastParamRequest) {
                 if ($middleweare === false){
                $this->addRoute("GET", $route, $handler, $middleweare);
                }else{

                    try {
                        if (!empty(JWT::decode(getToken(), new Key(JWT_KEY, 'HS256')))){
                            $this->addRoute("GET", $route, $handler, $middleweare);
                        }else{$this->err(401, 'Você precisa estar autenticado para acessar essa rota.');}
                    } catch (\Throwable $e) {
                        $this->err(401, 'Token inválido.');
                    }

                }
            }
    
        }
        
        
    }

    /**
     * @param string $route
     * @param $handler
     * @param string|null $name
     */
    public function put(string $route, $handler, bool $middleweare = false): void
    {
        $arrayRoute = [];
        $newArrayRoute = array_push($arrayRoute, $route);
        $new = explode('/', $_SERVER['REQUEST_URI']);

        foreach ($arrayRoute as $key => $value) {
            $lastParamRequest = end($new);
            $value = str_replace("/", "", $value);
            
            if ($value === $lastParamRequest) {
                 if ($middleweare === false){
                $this->addRoute("PUT", $route, $handler, $middleweare);
                }else{

                    try {
                        if (!empty(JWT::decode(getToken(), new Key(JWT_KEY, 'HS256')))){
                            $this->addRoute("PUT", $route, $handler, $middleweare);
                        }else{$this->err(401, 'Você precisa estar autenticado para acessar essa rota.');}
                    } catch (\Throwable $e) {
                        $this->err(401, 'Token inválido.');
                    }

                }
            }
    
        }
    }

    /**
     * @param string $route
     * @param $handler
     * @param string|null $name
     */
    public function patch(string $route, $handler, bool $middleweare = false): void
    {
        $arrayRoute = [];
        $newArrayRoute = array_push($arrayRoute, $route);
        $new = explode('/', $_SERVER['REQUEST_URI']);

        foreach ($arrayRoute as $key => $value) {
            $lastParamRequest = end($new);
            $value = str_replace("/", "", $value);
            
            if ($value === $lastParamRequest) {
                 if ($middleweare === false){
                $this->addRoute("PATCH", $route, $handler, $middleweare);
                }else{

                    try {
                        if (!empty(JWT::decode(getToken(), new Key(JWT_KEY, 'HS256')))){
                            $this->addRoute("PATCH", $route, $handler, $middleweare);
                        }else{$this->err(401, 'Você precisa estar autenticado para acessar essa rota.');}
                    } catch (\Throwable $e) {
                        $this->err(401, 'Token inválido.');
                    }

                }
            }
    
        }
    }

    /**
     * @param string $route
     * @param $handler
     * @param string|null $name
     */
    public function delete(string $route, $handler, bool $middleweare = false): void
    {
        $arrayRoute = [];
        $newArrayRoute = array_push($arrayRoute, $route);
        $new = explode('/', $_SERVER['REQUEST_URI']);

        foreach ($arrayRoute as $key => $value) {
            $lastParamRequest = end($new);
            $value = str_replace("/", "", $value);
            
            if ($value === $lastParamRequest) {
                 if ($middleweare === false){
                $this->addRoute("DELETE", $route, $handler, $middleweare);
                }else{

                    try {
                        if (!empty(JWT::decode(getToken(), new Key(JWT_KEY, 'HS256')))){
                            $this->addRoute("DELETE", $route, $handler, $middleweare);
                        }else{$this->err(401, 'Você precisa estar autenticado para acessar essa rota.');}
                    } catch (\Throwable $e) {
                        $this->err(401, 'Token inválido.');
                    }

                }
            }
    
        }
    }
}