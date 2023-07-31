<?php
//? Intégration du contenu de mon fichier de connexion à la BDD dans le fichier actuel
require_once("../utils/db-connect.php");

//? Intégration du fichier qui contient mes fonctions
require("../utils/function.php");

//? Le reste du script ne va s'exécuter qui si et seulement si l'utilisateur est connecté et est admin
isConnected();
isAdmin();

//? Si ma méthode de requète est POST, alors j'affecte à ma variable $method le contenu de la superglobale $_POST, sinon je lui affecte celui de $_GET
if ($_SERVER["REQUEST_METHOD"] == "POST") $method = $_POST;
else $method = $_GET;

switch ($method["choice"]) {
    case "select":
        $req = $db->query("SELECT * FROM messages");

        if ($req) $messages = $req->fetchAll(PDO::FETCH_ASSOC);
        else $messages = [];

        echo json_encode(["success" => true, "messages" => $messages]);

        break;

    case "select_id":
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            echo json_encode(["success" => false, "error" => "Mauvaise méthode"]);
            die;
        }

        if (!isset($method["id"]) || empty(trim($method["id"]))) {
            echo json_encode(["success" => false, "error" => "L'id n'existe pas ou est vide"]);
            die;
        }

        $req = $db->prepare("SELECT * FROM messages WHERE id = ?");
        $req->execute([$method["id"]]);

        if ($req) $message = $req->fetch(PDO::FETCH_ASSOC);
        else $message = [];

        echo json_encode(["success" => true]);
        break;

    default:
        echo json_encode(["success" => false, "error" => "Ce choix est inexictant"]);
        break;
}