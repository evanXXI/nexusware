<?php
//? Intégration du contenu de mon fichier de connexion à la BDD
require_once("./utils/db-connect.php");

//? Intégration du fichier qui contient mes fonctions
require("./utils/function.php");

//? La suite du script ne s'exécute que si et seulement si mon l'utilisateur est connecté
isConnected(); 

//? Si ma méhode de requète est POST, alors j'affecte à ma variable $method le contenu de la superglobale $_POST, sinon je lui affecte celui de $_GET
if ($_SERVER["REQUEST_METHOD"] == "POST") $method = $_POST;
else $method = $_GET;

switch ($method["choice"]) {
    case "select":
        $req = $db->query("");
        break;

    case "select_id":
        break;

    case "insert":
        //? Si ma méthode de requète n'est pas "POST", alors j'affiche un message d'erreur
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            echo json_encode(["success" => false, "error" => "Mauvaise méthode"]);
            die; //! J'arrête l'exécution
        }

        //? Je crée un tableau qui va contenir les paramètres que je veux vérifier
        $parameters = ["order_status", "user_id"];

        foreach ($parameters as $parameter) {
            if (!isset($method[$parameter]) || empty(trim($method[$parameter]))) {
                echo json_encode(["success" => false, "error" => "Les données de la commandes sont manquantes ou vides"]);
                die; //! J'arrête l'exécution
            }
        }

        $req = $db->prepare("INSERT INTO orders(order_status, user_id) VALUES(:order_status, :user_id)");

        $req->bindValue(":order_status", $method["order_status"]);
        $req->bindValue(":user_id", $method["user_id"]);

        $req->execute();

        $order_id = $db->lastInsertId();

        $total_price = 0;

        foreach (json_decode($method["products"]) as $product) {
            $req = $db->prepare("INSERT INTO ispartof(order_id, product_id, qte_produit) VALUES (:order_id, :product_id)");

            $req->bindValue(":order_id", $order_id);
            $req->bindValue(":product_id", $product["product_id"]);
            $req->bindValue(":product_qty", $product["product_qty"]);
            $req->execute();

            $part_id = $db->lastInsertId();
            if ($req->rowCount()) echo json_encode(["success" => true, "part_id" => $part_id]);
            else json_encode(["success" => false, "error" => "Erreur lors de l'insertion de la jonction commande/produit"]);

            $req = $db->prepare("SELECT p.price FROM products p INNER JOIN ispartof i ON p.id = i.product_id"); 
            $req->execute();

            $total_price += $req->fetch(PDO::FETCH_ASSOC);
        }

        /* $req-> $db->prepare("UPDATE orders SET total_price = :total_price WHERE order_id = $order_id");

        $req->bindValue(":total_price", $total_price); */

        if ($req->rowCount()) echo json_encode(["success" => true, "order_id" => $order_id]);
        else json_encode(["success" => false, "error" => "Erreur lors de l'insertion"]);
        break;

    case "delete":
        break;
}
