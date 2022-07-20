<?php


    namespace Widepay\Scraping\App;

    use Widepay\Scraping\Models\Model;

    require __DIR__ . "/../Functions/Functions.php";

    class Web extends Model
    {

        /*
        * Cria um novo Url setado pelo usuário.
        */
        public function createNewUrl()
        {  
            $url = isset($_POST['url']) ? $_POST['url'] : '';

            if (\filter_var($url, FILTER_VALIDATE_URL)){
                http_response_code(201);
                $this->myQuery("INSERT INTO urls (url_website, usr_id, created_at, updated_at) VALUES (?, ?, ?, ?)", [$url, getDataToken()['usrId'], realDate(), realDate()]);
                echo json_encode(['success'=> 'Dados inseridos com sucesso.']);
            }else{ echo json_encode(['error'=> 'URL inválida tente novamente.']); }

        }

        /*
        * Faz a renderização das urls cadastradas pelo usuário.
        */
        public function renderUrls()
        {
            $smtp = $this->myQuery("SELECT * FROM urls WHERE usr_id = ?", [getDataToken()['usrId']]);
            
            if ($smtp->rowCount() > 0){
                echo json_encode($smtp->fetchAll(\PDO::FETCH_OBJ));
            }else{
                http_response_code(404);
                echo json_encode(['error'=> 'Ooops, nenhum dados foi encontrado.']);
            }

        }

        /*
        * Criando uma nova conta de usuário.
        */
        public function createAccount()
        {
            
            $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
            $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $passwd = isset($_POST['passwd']) ? $_POST['passwd'] : '';
            $newPasswd = password_hash($passwd, PASSWORD_DEFAULT);

            $smtp = $this->myQuery("SELECT * FROM users WHERE email = ?", [$email]);
            
            // Verificando email já existe se não existir add novo usuario.
            if ($smtp->rowCount() == 0){

                $newUserId = \generateNewUserId($email);
                
                http_response_code(201);
                $smtp = $this->myQuery("INSERT INTO users (usr_id, first_name, last_name, email, passwd, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)", [$newUserId, $firstName, $lastName, $email, $newPasswd, realDate(), realDate()]);
                
                echo json_encode(['success'=> 'Dados inseridos com sucesso.', 'token' => generateNewJWT($email, $newUserId)]);
                
            }else{
                http_response_code(400);
                echo json_encode(['error'=> 'Ooops, usuario já existe.']);
            }

        }

        /*
        * Fazendo autenticação do usuário.
        */
        public function loginAccount()
        {
            /*
            *  Tratamento simples para recebimento dos dados 
            */
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $passwd = isset($_POST['passwd']) ? $_POST['passwd'] : '';

            $smtp = $this->myQuery("SELECT * FROM users WHERE email = ?", [$email]);
            
            // Verificando email já existe se não existir add novo usuario.
            if ($smtp->rowCount() >= 1){

                $newUserId = generateNewUserId($email);

                if (password_verify($passwd, $smtp->fetch(\PDO::FETCH_OBJ)->passwd)) {
                    
                    http_response_code(201);
                    echo json_encode(['success'=> 'Dados inseridos com sucesso.', 'token' => generateNewJWT($email, $newUserId)]);
                }
                
            }else{
                http_response_code(400);
                echo json_encode(['error'=> 'Ooops, usuario já existe.']);
            }

        }

    }
    