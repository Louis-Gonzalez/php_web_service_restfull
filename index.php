<?php 

// Inclure le fichier user.php
require "./users.php";

// Vérification dela partie .htaccess
// echo "<pre>";
// var_dump($users);
// echo "</pre>";

// Récupérer la méthode HTTP et l'uri

// echo "<pre>";
// var_dump($_SERVER);
// var_dump("uri:",$_SERVER['REQUEST_URI']);
// var_dump("method:",$_SERVER['REQUEST_METHOD']);
// string(4) "uri:"
// string(31) "/php_web_service_restfull/users"
// string(7) "method:"
// string(3) "GET"
// echo "</pre>";

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Ajouter le header "application/json" à destination des navigateurs web
header('Content-Type: application/json');

// Routeur pour les différentes opérations CRUD
switch ($method){
    case 'GET': // http://localhost/php_web_service_restfull/users
        preg_match("/^\/php_web_service_restfull\/users\/?(\d+)?$/", $uri, $matches);

        if (!empty($matches) && !array_key_exists(1, $matches)) { 
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

        break; 
    case 'POST': 
        $user = $_POST;
        preg_match("/^\/php_web_service_restfull\/users\/?(\d+)?$/", $uri, $matches);
        $user = createUser($user);
        echo json_encode($user);
        break;

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
