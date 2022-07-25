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

    /*
     * Função que retorna erros da aplicação
     */
    function err(?int $code, ?string $message = '')
    {   
        if (is_numeric($code) && !empty($message)){
            http_response_code($code);
            echo json_encode(['error'=> $message]);
        }
        return;
    }

    /*
     * Função que faz o recebimento e tratamento dos dados vindo do front-end
     */
    function getDataMethod(?array $data)
    {
        $newData = [];

        foreach ($data as $key => $value) {
            $d=preg_replace('/[^a-z0-9]/i', "", $key);
            $e=preg_replace('/[^a-z0-9]/i', "", $value);
            $newData = [$d => $e];
        }

        return $newData;
    }