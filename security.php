<?php

// Inclure la bibliothèque PHP-JWT
require_once 'vendor/autoload.php';

// Inclure la classe JWT
use \Firebase\JWT\JWT;

// Clé secrète(mot de passe) pour signer le token (à conserver en sécurité)
$secret_key = "Monmotdepasse2024!";

// Fonction pour générer un token JWT
function generateToken(){
    global $secret_key;
    $payload = [
        "user_id" => 1,
        "user_name" => "louis", // souvent un email 
        "user_role" => "ADMIN",
        "exp" => time() + (60*60) // time() correspond au temps actuel et 60*60 correspond au temps d'expiration du token qui s'exprime en secondes
    ];
    return JWT::encode($payload,$secret_key,"HS256"); // Cryptage du token est en HS256 
}

// Fonction pour valider et décoder un token JWT
$token = generateToken();
var_dump($token);

// Exemple d'utilisation : Authentification et génération du token

// Exemple d'utilisation : Validation du token et accès à l'API

?>
