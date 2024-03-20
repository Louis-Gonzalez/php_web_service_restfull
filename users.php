<?php

// Définir un modèle pour les utilisateurs
$model = ['id', 'lastname', 'firstname', 'email', 'password', 'role']; // on peut aussi ajouter le typage des valeurs

// Donnée simulées pour les utilisateurs
$users =
    [
        [
            'id' => 1,
            'lastname' => 'Doe',
            'firstname' => 'John',
            'email' => 'john.doe@cci.com',
            'password' => 'john1234',
            'role' => 'admin'
        ],
        [
            'id' => 2,
            'lastname' => 'Doe',
            'firstname' => 'Jane',
            'email' => 'jane.doe@cci.com',
            'password' => 'jane1234',
            'role' => 'user'
        ],
        [
            'id' => 3,
            'lastname' => 'Marcellus',
            'firstname' => 'Wallace',
            'email' => 'marcellus.wallace@cci.com',
            'password' => 'marcellus1234',
            'role' => 'user'
        ],
        [
            'id' => 4,
            'lastname' => 'Uma',
            'firstname' => 'Turnman',
            'email' => 'turnman.uma@cci.com',
            'password' => 'uma1234',
            'role' => 'user'
        ],
        [
            'id' => 5,
            'lastname' => 'kantin',
            'firstname' => 'tarentino',
            'email' => 'tarentino.kantin@cci.com',
            'password' => 'kantin1234',
            'role' => 'user'
        ]
    ];

// Fonction pour afficher les utilisateurs par la méthode GET
function getAll()
{
    global $users;
    if (empty($users)) {
        return  [
            "code" => 200,
            "message" => "aucun utilisateur trouvé"
        ];
    }
    return $users;
}

// Fonction pour trouver un utilisateur par son identifiant par la méthode GET
function getUserById(int $id)
{
    global $users;
    foreach ($users as $user) {
        if ($user['id'] === $id) {
            return $user;
        }
    }
    return  [
        "code" => 200,
        "message" => "aucun utilisateur trouvé avec l'id : $id "
    ];
}

// Fonction pour créer un nouvel utitlisateur par la méthode POST
function createUser(array $user)
{
    global $users;
    global $model;
    $keys = array_keys($user);
    $diff = array_diff($model, $keys);

    if (empty($user)) {
        http_response_code(404);
        return [
            "code" => 400,
            "message" => "aucune data utilisateur n'a été envoyée"
        ];
    }

    if (count($diff) > 0) {
        $message = "Il manque les cles suivantes dans le tableau : ";
        $i = 0;
        foreach ($diff as $key => $value) { // on parcourt le tableau $diff et on récupère les $clés de $diff 
            if ($key === array_keys($model, $value)[0] && $i === 0) { // on compare les $clés de $diff avec les $clés de $model

                $message .= " $value"; // pour la première itération on ajoute un espace
                $i++; // on ajoute 1 au compteur
                continue;
            }
            $message .= ", $value"; // pourles autres  itérations on ajoute une virgule
        }
        http_response_code(404);
        return [
            "code" => 400,
            "message" => $message
        ];
    }
    // var_dump(array_diff($model, $keys)); // on compare les cles de $model et de $user le premier argument est le tableau $model et le deuxième est le tableau $user
    // die();
    http_response_code(200);
    $users[] = $user;
    return $user; // convention on renvoie le nouvel utilisateur soit $user
}

// Fonction pour modifier un utilisateur par son identifiant en méthode PATCH
function updateUser(int $id, $updates)
{
    global $users;
    foreach ($users as $key => $user) {
        if ($user['id'] === $id) {
            foreach ($updates as $key2 => $update) {
                $users[$key][$key2] = $update;
            }
            http_response_code(200);
            return $users; // convention on renvoie le nouvel utilisateur soit $user mais pour l'update on va renvoyer un tableau des users
        }
    }
    http_response_code(404);
        return [ // on gère le cas ou l'utilisateur n'existe pas et on renvoie un message d'erreur condition du if à la ligne 122
            "code" => 404,
            "message" => "l'utilisateur avec l'id $id n'existe pas !"
        ];
}

// Fonction pour remplacer un utilisateur par son identifiant en méthode PUT
function replaceUser(int $id, array $puts){
    global $users;
    global $model;

    $keys= array_keys($puts);
    $diff = array_diff($model, $keys);

    if (empty($puts)) {
        http_response_code(404);
        return [
            "code" => 400,
            "message" => "aucune data utilisateur n'a été envoyée"
        ];
    }

    if (count($diff) > 0) {
        $message = "Il manque les cles suivantes dans le tableau : ";
        $i = 0;
        foreach ($diff as $key => $value) { // on parcourt le tableau $diff et on récupère les $clés de $diff 
            if ($key === array_keys($model, $value)[0] && $i === 0) { // on compare les $clés de $diff avec les $clés de $model

                $message .= " $value"; // pour la première itération on ajoute un espace
                $i++; // on ajoute 1 au compteur
                continue;
            }
            $message .= ", $value"; // pourles autres  itérations on ajoute une virgule
        }
        http_response_code(404);
        return [
            "code" => 400,
            "message" => $message
        ];
    }
    foreach ($users as $key => $user) {
        
        if ($user['id'] === $id) {
            $users[$key] = $puts;
            http_response_code(200);
            return $users; // convention on renvoie le nouvel utilisateur soit $user mais pour l'update on va renvoyer un tableau des users
        }
        http_response_code(404);
        return [ // on gère le cas ou l'utilisateur n'existe pas et on renvoie un message d'erreur condition du if à la ligne 144
            "code" => 404,
            "message" => "l'utilisateur avec l'id $id n'existe pas !"
        ];
    }
    return null;
}

// fonction pour supprimer un utilisateur par son identifiant en méthode DELETE
function deleteUser(int $id)
{
    global $users;
    foreach ($users as $key => $user) {
        if ($user['id'] === $id) {
            unset($users[$key]);
            http_response_code(200);
            return ["l'utilisateur avec l'id $id a bien été supprimé", $users];
        }
    }
    http_response_code(404);
    return [ // on gère le cas ou l'utilisateur n'existe pas et on renvoie un message d'erreur condition du if à la ligne 144
        "code" => 404,
        "message" => "l'utilisateur avec l'id $id n'existe pas !"
    ];
}
