<?php

// Inclure la bibliothèque PHP-JWT
require_once 'vendor/autoload.php';

// Inclure la classe JWT
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// Clé secrète(mot de passe) pour signer le token (à conserver en sécurité)
// $secret_key = "Monmotdepasse2024!";

// Fonction pour générer un token JWT
function generateToken(string $secret_key, string $email): string
{
    $payload = [
        "user_id" => 1,
        "email" => $email, // souvent un email 
        "user_role" => "ADMIN",
        "exp" => time() + (60 * 60) // time() correspond au temps actuel et 60*60 correspond au temps d'expiration du token qui s'exprime en secondes
    ];
    try {

        return JWT::encode($payload, $secret_key, "HS256"); // Cryptage du token est en HS256 
    }
    catch (\Throwable $th) { // est une class qui permet de transmette $th qui est la variable de stockage des erreurs (ex: problème payload, token, cryptage)
        throw $th;
    }
}

// Fonction pour valider et décoder un token JWT
// $token = generateToken(); // pour visualiser le test du token
// var_dump($token);

// Exemple d'utilisation : Authentification et génération du token
function validate_token($token, string $secret_key)
{
    try {
        return JWT::decode($token, new Key($secret_key, "HS256"), $headers);
    }
    catch (\Throwable $th) {
        throw $th;
    }
}

// $decode = validate_token($token); // pour visualiser le test du decode
// echo "<pre>";
// var_dump($decode);
// echo "</pre>";

// Exemple d'utilisation : Validation du token et accès à l'API
