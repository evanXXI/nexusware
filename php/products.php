<?php
//? Intégration du contenu de mon fichier de connexion à la BDD dans le fichier actuel
require_once("./utils/db-connect.php");

//? Intégration du fichier qui contient mes fonctions
require("./utils/function.php");

//? Si ma méthode de requète est POST, alors j'affecte à ma variable $method le contenu de la superglobale $_POST, sinon je lui affecte celui de $_GET
if ($_SERVER["REQUEST_METHOD"] == "POST") $method = $_POST;
else $method = $_GET;

switch ($method["choice"]) {
    case "select":
        $req = $db->query("SELECT name, price, wireless, image, category_id FROM products");

        if ($req) $products = $req->fetchAll(PDO::FETCH_ASSOC);
        else $products = [];

        echo json_encode(["success" => true, "products" => $products]);
        break;
    
    case "sort":
        switch ($method["sort"]) {
            case "byPriceAsc":
                $req = $db->query("SELECT name, price, wireless, image, category_id FROM products ORDER BY price ASC");

                if ($req) $products = $req->fetchAll(PDO::FETCH_ASSOC);
                else $products = [];

                echo json_encode(["success" => true, "products" => $products]);
                break;
            
            case "byPriceDesc":
                $req = $db->query("SELECT name, price, wireless, image, category_id FROM products ORDER BY price DESC");

                if ($req) $products = $req->fetchAll(PDO::FETCH_ASSOC);
                else $products = [];

                echo json_encode(["success" => true, "products" => $products]);
                break;

            case "byNewness":
                $req = $db->query("SELECT name, price, wireless, image, category_id FROM products ORDER BY adding_date DESC");
                break;
        }
        break;

    case "select_id":
        if (!isset($method["id"]) || empty(trim($method["id"]))) {
            echo json_encode(["success" => false, "error" => "L'id n'existe pas ou est vide"]);
            die;
        }

        $req = $db->prepare("SELECT name, description, price, stock, wireless, image, category_id FROM products WHERE id = ?");
        $req->execute([$method["id"]]);

        if ($req) $product = $req->fetch(PDO::FETCH_ASSOC);
        else $product =[];

        echo json_encode(["success" => true, "product" => $product]);
        break;

    case "isWireless":

    default:
        echo json_encode(["success" => false, "error" => "Ce choix est inexistant"]);
        break;
}