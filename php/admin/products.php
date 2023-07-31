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
        $req = $db->query("SELECT * FROM products");

        if ($req) $products = $req->fetchAll(PDO::FETCH_ASSOC);
        else $products = [];

        echo json_encode(["success" => true, "products" => $products]);
        break;

    case "select_id":
        if (!isset($method["id"]) || empty(trim($method["id"]))) {
            echo json_encode(["success" => false, "error" => "L'id n'existe pas ou est vide"]);
            die;
        }

        $req = $db->prepare("SELECT * FROM products WHERE id = ?");
        $req->execute([$method["id"]]);

        if ($req) $product = $req->fetch(PDO::FETCH_ASSOC);
        else $product =[];

        echo json_encode(["success" => true, "product" => $product]);
        break;

    case "sort":
        switch ($method["sort"]) {
            case "byPriceAsc":
                $req = $db->query("SELECT * FROM products ORDER BY price ASC");

                if ($req) $products = $req->fetchAll(PDO::FETCH_ASSOC);
                else $products = [];

                echo json_encode(["success" => true, "products" => $products]);
                break;
            
            case "byPriceDesc":
                $req = $db->query("SELECT * FROM products ORDER BY price DESC");

                if ($req) $products = $req->fetchAll(PDO::FETCH_ASSOC);
                else $products = [];

                echo json_encode(["success" => true, "products" => $products]);
                break;

            case "byNewness":
                $req = $db->query("SELECT * FROM products ORDER BY adding_date DESC");
                break;
        }
        break;
        
    case "insert":
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            echo json_encode(["success" => false, "error" => "Mauvaise méthode"]);
            die;
        }

        $parameters = ["name", "desc", "price", "stock", "wireless", "category_id"];
        
        foreach ($parameters as $parameter) {
            if (!isset($method[$parameter]) || empty(trim($method[$parameter]))) {
                echo json_encode(["success" => false, "error" => "Données manquantes ou vides"]);
                die;
            }
        }

        $img = false;
        if (isset($_FILES["image"]["name"])) $img = upload($_FILES);

        $req = $db->prepare("INSERT INTO products(name, description, price, stock, wireless, image, category_id) VALUES (:name, :desc, :price, :stock, :wireless, :image, :category_id)");

        $req->bindValue(":name", $method["name"]);
        $req->bindValue(":desc", $method["desc"]);
        $req->bindValue(":price", $method["price"]);
        $req->bindValue(":stock", $method["stock"]);
        $req->bindValue(":wireless", $method["wireless"]);
        $req->bindValue(":category_id", $method["category_id"]);
        if ($img) $req->bindValue(":image", $img);
        else $req->bindValue(":image", null);

        $req->execute();

        $product_id = $db->lastInsertId();

        if ($req->rowCount()) echo json_encode(["success" => true, "product_id" => $product_id]);
        else echo json_encode(["success" => false, "error" => "Erreur lors de l'insertion"]);
        break;

    case "update":
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            echo json_encode(["success" => false, "error" => "Mauvaise méthode"]);
            die;
        }

        $parameters = ["id", "name", "desc", "price", "stock", "sold_units", "wireless", "category_id"];
        
        foreach ($parameters as $parameter) {
            if (!isset($method[$parameter]) || empty(trim($method[$parameter]))) {
                echo json_encode(["success" => false, "error" => "Données manquantes ou vides"]);
                die;
            }
        }

        $img = false;
        if (isset($_FILES["image"]["name"])) $img = upload($_FILES);

        $img_req = "";
        if ($img) $img_req = ", image = :image";

        $req = $db->prepare("UPDATE products SET name = :name, description = :desc, price = :price, stock = :stock, sold_units = :sold_units, wireless = :wireless, category_id = :category_id $img_req WHERE id = :id");

        $req->bindValue(":name", $method["name"]);
        $req->bindValue(":desc", $method["desc"]);
        $req->bindValue(":price", $method["price"]);
        $req->bindValue(":stock", $method["stock"]);
        $req->bindValue(":sold_units", $method["sold_units"]);
        $req->bindValue(":wireless", $method["wireless"]);
        $req->bindValue(":category_id", $method["category_id"]);
        $req->bindValue(":id", $method["id"]);
        if ($img) $req->bindValue(":image", $img);
        else $req->bindValue(":image", null);

        $req->execute();

        $product_id = $db->lastInsertId();

        if($req->rowCount()) echo json_encode(["success" => true]);
        else echo json_encode(["success" => false, "error" => "Erreur lors de la modification"]);
        break;
        

    case "delete":
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            echo json_encode(["success" => false, "error" => "Mauvaise méthode"]);
            die;
        }

        if (!isset($method["id"]) || empty(trim($method["id"]))) {
            echo json_encode(["success" => false, "error" => "L'id n'existe pas ou est vide"]);
            die;
        }

        $req = $db->prepare("DELETE FROM products WHERE id = ?");
        $req->execute([$method["id"]]);

        if ($req->rowCount()) echo json_encode(["success" => true]);
        else echo json_encode(["success" => false, "error" => "Erreur lors de la suppression"]);
        break;

    default:
        echo json_encode(["success" => false, "error" => "Ce choix est inexistant"]);
        break;
}