<?php
//* Paramètres de connexion à la BDD 
$host  = "localhost";
$username = "root";
$password = "";

$dbname = "nexusware";

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (ErrorException $e) {
    //? La méthode catch permet ici d'afficher des erreurs si la connexion à la BDD n'a pas fonctionné
    echo $e;
}
?>