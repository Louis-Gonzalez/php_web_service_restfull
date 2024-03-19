<?php 

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

// Fonction pour afficher les utilisateurs
function getAll(){
    global $users;
    return $users;
}

// Fonction pour trouver un utilisateur par son identifiant
function getUserById(int $id){
    global $users;
    foreach ($users as $user) {
        if ($user['id'] === $id) {
            return $user;
        }
    }
    return null;
}

// Fonction pour créer un nouvel utitlisateur
function createUser(array $user){
    global $users;
    $users[] = $user;
    return $user; // convention on renvoie le nouvel utilisateur soit $user
}

// Fonction pour modifier un utilisateur
function updateUser(int $id, $updates){
    global $users;
    foreach ($users as $key => $user) {
        if ($user['id'] === $id) {
            foreach ($updates as $key2 => $update) {
                $users[$key][$key2] = $update;
            }
            return $user; // convention on renvoie le nouvel utilisateur soit $user
        }
    }
    // return null;
}


// fonction pour supprimer un utilisateur
function deleteUser(int $id){
    global $users;
    foreach ($users as $key => $user) {
        if ($user['id'] === $id) {
            unset($users[$key]);
            return "l'utilisateur avec l'id $id a bien été supprimé";
        }
    }
    return null;
}

?>