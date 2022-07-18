<?php

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    /*
     * Retorna dados do token
     */
    function getDataToken(): array 
    {
        $Authorization = apache_request_headers();
        $Authorization = preg_replace('/Bearer /i', '', $Authorization['Authorization']);
        
        $decoded = JWT::decode($Authorization, new Key(JWT_KEY, 'HS256'));
        $decodedArray = (array) $decoded;
        
        return $decodedArray;
    }

    /*
     * Gera um novo id para o usuario
     */
    function generateNewUserId($hash): string 
    { return \md5($hash); }

    /*
     * Gera um novo Token JWT com o email e o id do usuário
     */
    function generateNewJWT($email, $usrId): string 
    { 
        $payload = ['email' => $email, 'usrId' => $usrId]; //Payload JWT
        return JWT::encode($payload, JWT_KEY, 'HS256'); // Retorna token JWT
    }

    /*
     * Verifica se o token é valido e retorna Payload com dados informados.
     */
    function decodedJWTToken($token) 
    { return JWT::decode($token, new Key(JWT_KEY, 'HS256')); }


    /*
     * Verifica se o token é valido e retorna Payload com dados informados.
     */
    function realDate(): string 
    {  date_default_timezone_set('America/Sao_Paulo'); return date("Y-m-d H:i:s"); }