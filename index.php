<?php 

// Inclure le fichier user.php
require "./users.php";
require "./security.php";

// Vérification dela partie .htaccess
// echo "<pre>";
// var_dump($users);
// echo "</pre>";

// Récupérer la méthode HTTP et l'uri

// echo "<pre>";
// var_dump($_SERVER);
// var_dump(getallheaders());
// var_dump("uri:",$_SERVER['REQUEST_URI']);
// var_dump("method:",$_SERVER['REQUEST_METHOD']);
// string(4) "uri:"
// string(31) "/php_web_service_restfull/users"
// string(7) "method:"
// string(3) "GET"
// echo "</pre>";

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$check = false;

// Ajouter le header "application/json" à destination des navigateurs web
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Fonction de vérification du JWT = token
function verify(){
    global $check;
    // Récupération du token dans le header
    // echo json_encode(getallheaders());
    // die();
    $headers = getallheaders();
    $token = explode(' ', $headers['Authorization'])[1];
    $secret_key = base64_encode("john1234");
    // var_dump($token);
    // die();
    try {
        $check = validate_token($token, $secret_key); // le $secret_key à la valeur en string "123" que nous avons déclarer dans postman
    }
    catch (\Throwable $th) {
        echo json_encode([
            "code" => 401,
            "message" => "Authentification invalide"
        ]);
        die();
    }
}

// Routeur pour les différentes opérations CRUD
switch ($method){
    case 'GET': // http://localhost/php_web_service_restfull/users
    global $check;
    verify();
        if($check){
            preg_match("/^\/php_web_service_restfull\/users\/?(\d+)?$/", $uri, $matches);
            if (!empty($matches) && !array_key_exists(1, $matches)) { 
                echo "<pre>";
                var_dump($users);
                echo "</pre>";
                die();

                $users = getAll();
                // var_dump("getAll", $matches);
                // echo "<pre>";
                // var_dump($users);
                // echo "</pre>";
                echo json_encode($users);
                break;
            }
            if (!empty($matches) && array_key_exists(1, $matches)) {
                $user = getUserById((int)$matches[1]);
                // var_dump("getUserById", $matches);
                // echo "<pre>";
                // var_dump($user);
                // echo "</pre>";
                echo json_encode($user);
                break;
            }
        }
        echo json_encode([
            "code" => 403,
            "message" => "accès non autorisé"
        ]);
        break; 

    case 'POST': 
        $user = $_POST;
        // var_dump($user);
        // var_dump($user['password']);
        // modifier le regex pour les routes login et users et on récupère la corrrepondance de $matches[1] soit users pour créer soit login pour se connecter
        preg_match("/^\/php_web_service_restfull\/(users||register||login)\/?(\d+)?$/", $uri, $matches); 

        if($matches[1] === "users"){
            $user = createUser($user);
            echo json_encode($user);
        }
        if ($matches[1] === "register") {
            $passwordEncoded = base64_encode($user['password']);
            // var_dump("password encodé",$passwordEncoded);

            try {
                $token = generateToken($passwordEncoded, $user['email']);
                // var_dump($token);
                // die();
                http_response_code(200);
                echo json_encode($token);
            }
            catch (\Throwable $th) {
                echo json_encode([
                    "code" => 401,
                    "message" => "Authentification invalide"
                ]);
                die();
            }
        }
        if ($matches[1] === "login") { // 
            foreach ($users as $item) {
                if ($item['email'] === $user['email']) {
                if ($item['password'] === $user['password']) {
                        $passwordEncoded = base64_encode($user['password']);
                    }
                }
            }
            // var_dump("password encodé",$passwordEncoded);
            try {
                $token = generateToken($passwordEncoded, $user['email']);
                // var_dump($token);
                // die();
                http_response_code(200);
                echo json_encode($token);
            }
            catch (\Throwable $th) {
                echo json_encode([
                    "code" => 401,
                    "message" => "Authentification invalide"
                ]);
                die();
            }
        }
        break;

        // preg_match("/^\/php_web_service_restfull\/login\/?(\d+)?$/", $uri, $matches);


    case 'PATCH': 
        // modifié le regex pour trouver le bon chemin ? http://localhost/php_web_service_restfull/users/1
        preg_match("/^\/php_web_service_restfull\/users\/?(\d+)?$/", $uri, $matches); 
        // var_dump($matches);
        $id = (int)$matches[1];
        $updates = file_get_contents("php://input"); // c'est l'endroit ou on recupere les informations venant de l'utilisateur
        $items = explode('&', $updates);
        $data = [];
        foreach ($items as $item) {
            $inputs = explode('=', $item);
            $data[$inputs[0]] = $inputs[1];
        }
        // var_dump($data);
        $result = updateUser($id, $data);
        echo json_encode($result);
        break;

    case 'PUT':
        preg_match("/^\/php_web_service_restfull\/users\/?(\d+)?$/", $uri, $matches);
        // var_dump($matches);
        $id = (int)$matches[1];
        $puts = file_get_contents("php://input"); // c'est l'endroit ou on recupere les informations venant de l'utilisateur
        $items = explode('&', $puts);
        $data = [];
        foreach ($items as $item) {
            $inputs = explode('=', $item);
            if ( $inputs[0] !== ""){
                $data[$inputs[0]] = $inputs[1];
            }
        }
        // var_dump($data);
        $result = replaceUser($id, $data);
        echo json_encode($result);
        break;

    case 'DELETE': 
        preg_match("/^\/php_web_service_restfull\/users\/?(\d+)?$/", $uri, $matches);
        // var_dump($matches); // id ok
        $id = (int)$matches[1];
        // var_dump($id); // id ok
        // var_dump($users[$id = 3]);
        $result = deleteUser($id); 
        echo json_encode($result); // penser a le renvoyer au format json pour afficher le résultat
        break;

    default: 
        http_response_code(404); 
        echo "Ressource introuvable"; 
        break;
}


?>
