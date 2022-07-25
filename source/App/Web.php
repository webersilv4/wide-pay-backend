<?php


    namespace Widepay\Scraping\App;

    use Widepay\Scraping\Models\Model;

    require __DIR__ . "/../Functions/Functions.php";

    class Web extends Model
    {
        /*
        * Classe que faz o scraping e rotorna os corpo HTML
        */
        public function scraping($url, $id)
        {
            try {
                $httpClient = new \GuzzleHttp\Client();
                $response = $httpClient->get($url);
                $htmlString = (string) $response->getBody();
                libxml_use_internal_errors(true); // Quando o HTML estiver instável, isso suprime os avisos.

                $this->myQuery('UPDATE urls SET status_code = ?, requisition_body = ?, updated_at = ? WHERE id = ?', [200, $htmlString, realDate(), $id]);

            } catch (\Throwable $e) { $this->myQuery('UPDATE urls SET status_code = ?, requisition_body = ?, updated_at = ? WHERE id = ?', [404, '', realDate(), $id]); }

        }

        /*
        * Cria um novo Url setado pelo usuário.
        */
        public function createNewUrl($data)
        {  
            $url = isset($data['url']) ? $data['url'] : '';

            if (\filter_var($url, FILTER_VALIDATE_URL)){
                http_response_code(201);
                $this->myQuery("INSERT INTO urls (url_website, usr_id, created_at, updated_at) VALUES (?, ?, ?, ?)", [$url, getDataToken()['usrId'], realDate(), realDate()]);
                $smtp = $this->myQuery("SELECT LAST_INSERT_ID()", []); //Gera um Array com o último ID inserido
                $id = intval($smtp->fetch(\PDO::FETCH_ASSOC)["LAST_INSERT_ID()"]); //Pega ID da última coluna inserida.

                $this->scraping($url, $id); // Passa a url e o id para fazer o scraping e atulizar a base de dados URLS.
                
                echo json_encode(['success'=> 'Dados inseridos com sucesso.']);
            }else{ err(406, 'URL inválida tente novamente.'); }
        }

        /*
        * Altera a Url setado pelo usuário autenticado.
        */
        public function alterUrl($data)
        {  
            $id = isset($data['id']) ? intval($data['id']) : '';
            $url = isset($data['url']) ? $data['url'] : '';
  
            if (\filter_var($url, FILTER_VALIDATE_URL) && is_numeric($id)){
                http_response_code(201);
                $this->myQuery('UPDATE urls SET url_website = ?, updated_at = ? WHERE id = ?', [$url, realDate(), $id]);
                $this->scraping($url, $id);
                
                echo json_encode(['success'=> 'Dados alterados com sucesso.']);
                
            }else{ err(406, 'URL ou ID inválidos tente novamente.'); }

        }

        /*
        * Deleta uma Url setado pelo usuário autenticado.
        */
        public function deleteUrl($data)
        {  
            $usrId = getDataToken()['usrId'];
            $id = isset($data['id']) ? $data['id'] : '';

            if (is_numeric($id)){
                $smtp = $this->myQuery('DELETE FROM urls WHERE id = ? AND usr_id = ?', [$id, $usrId]);
                if ($smtp->rowCount() > 0){
                    http_response_code(201);
                    echo json_encode(['success'=> 'Dado excluido com sucesso.']);
                }else { err(400, 'Não foi possivel excluir está linha tente novamente'); }
            }else{ err(406 ,'URL ou ID inválidos ou não existem tente novamente.'); }

        }

        /*
        * Faz a renderização das urls cadastradas pelo usuário.
        */
        public function renderUrls()
        {
            $usrId = getDataToken()['usrId'];

            $smtp = $this->myQuery("SELECT * FROM urls WHERE usr_id = ? ORDER BY id DESC", [$usrId]);
            
            if ($smtp->rowCount() > 0){
                echo json_encode($smtp->fetchAll(\PDO::FETCH_OBJ));
            }else{ err(404, 'Ooops, você não tem nenhuma URL cadastrada.'); }

        }

        /*
        * Criando uma nova conta de usuário.
        */
        public function createAccount($data)
        {
            
            $firstName = isset($data['firstName']) ? $data['firstName'] : '';
            $lastName = isset($data['lastName']) ? $data['lastName'] : '';
            $email = isset($data['email']) ? $data['email'] : '';
            $passwd = isset($data['passwd']) ? $data['passwd'] : '';
            $newPasswd = password_hash($passwd, PASSWORD_DEFAULT);

            $smtp = $this->myQuery("SELECT * FROM users WHERE email = ?", [$email]);
            
            // Verificando email já existe se não existir add novo usuario.
            if ($smtp->rowCount() == 0){

                $newUserId = \generateNewUserId($email);
                
                http_response_code(201);
                $smtp = $this->myQuery("INSERT INTO users (usr_id, first_name, last_name, email, passwd, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)", [$newUserId, $firstName, $lastName, $email, $newPasswd, realDate(), realDate()]);
                
                echo json_encode(['success'=> 'Dados inseridos com sucesso.', 'token' => generateNewJWT($email, $newUserId)]);
                
            }else{ err(400, 'Usuário já existe.'); }

        }

        /*
        * Fazendo autenticação do usuário.
        */
        public function loginAccount($data)
        {
            /*
            *  Tratamento simples para recebimento dos dados 
            */
            $email = isset($data['email']) ? $data['email'] : '';
            $passwd = isset($data['passwd']) ? $data['passwd'] : '';

            $smtp = $this->myQuery("SELECT * FROM users WHERE email = ?", [$email]);
            
            // Verificando email já existe se não existir add novo usuario.
            if ($smtp->rowCount() >= 1){

                $newUserId = generateNewUserId($email);

                if (password_verify($passwd, $smtp->fetch(\PDO::FETCH_OBJ)->passwd)) {
                    http_response_code(201);
                    echo json_encode(['token' => generateNewJWT($email, $newUserId)]);
                }
                
            }else{ err(400, 'Usuário ou senha inválidos.'); }
        }

    }
    