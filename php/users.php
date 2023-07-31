<?php

require_once("./utils/db-connect.php");

require("./utils/function.php");

isConnected();

if ($_SERVER["REQUEST_METHOD"] == "POST") $method = $_POST;
else $method = $_GET;

switch ($method["choice"]) {
    case "select_id":
        if (!isset($_SESSION["userd
        _id"]) || empty(trim($_SESSION["userd_id"]))) {
            echo json_encode(["success" => false, "error" => "L'id n'existe pas ou est vide"]);
            die;
        }

        $req = $db->prepare("SELECT firstname, lastname, birthdate, street_number, street_name, zip_code, country, email FROM users WHERE id = ?");
        $req->execute([$_SESSION["userd_id"]]);

        if($req) $user = $req->fetch(PDO::FETCH_ASSOC);
        else $user = [];
        
        echo json_encode(["success" => true, "user" => $user]);
        break;

    case "update":
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            echo json_encode(["success" => false, "error" => "Mauvaise méthode"]);
            die;
        }

        $parameters = ["id", "firstname", "lastname", "birthdate", "street_number", "street_name", "zip_code", "country", "email"];
        
        foreach ($parameters as $parameter) {
            if (!isset($method[$parameter]) || empty(trim($method[$parameter]))) {
                echo json_encode(["success" => false, "error" => "Paramètres manquants ou vides"]);
                die;
            }
        }

        $req = $db->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, birthdate = :birthdate, street_number = :street_number, street_name = :street_name, zip_code = :zip_code, country = :country, email = :email WHERE id = :id");

        $req->bindValue(":firstname", $method["firstname"]);
        $req->bindValue(":lastname", $method["lastname"]);
        $req->bindValue(":birthdate", $method["birthdate"]);
        $req->bindValue(":street_number", $method["street_number"]);
        $req->bindValue(":street_name", $method["street_name"]);
        $req->bindValue(":zip_code", $method["zip_code"]);
        $req->bindValue(":country", $method["country"]);
        $req->bindValue(":email", $method["email"]);
        $req->bindValue(":id", $method["id"]);
        $req->execute();

        if ($req->rowCount()) echo json_encode(["success" => true]);
        else echo json_encode(["success" => false, "error" => "Erreur lors de la mise à jour"]);
        break;

    default:
        echo json_encode(["success" => false, "error" => "Ce choix est inexictant"]);
        break;
}