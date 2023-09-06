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
        $sort = "";
        $wireless = "";
        
        if (isset($method["wire"]) && !empty(trim($method["wire"])) && $method["wire"] == "isWireless") $wireless = "WHERE wireless = 1";

        if (isset($method["sort"]) && !empty(trim($method["sort"]))) {
            if ($method["sort"] == "byPriceAsc") {
                $sort = "ORDER BY price ASC";
            } else if ($method["sort"] == "byPriceDesc") {
                $sort = "ORDER BY price DESC";
            } else if ($method["sort"] == "byNewness") {
                $sort = "ORDER BY adding_date DESC";
            }
        }

        $sortTest = $method["sort"];
        $wireTest = $method["wire"];
        
        $req = $db->query("SELECT p.id, p.name, p.price, p.description, p.wireless, p.image, p.adding_date, c.name AS category_name FROM products p INNER JOIN categories c ON p.category_id = c.id $wireless $sort");

        if ($req) $products = $req->fetchAll(PDO::FETCH_ASSOC);
        else $products = [];

        echo json_encode(["success" => true, "products" => $products, "Req" => $req, "sortTest" => $sortTest, "wireTest" => $wireTest]);
        break;
    
    case "selectByCategory":
        if (!isset($method["category_id"]) || empty(trim($method["category_id"]))) {
            echo json_encode(["success" => false, "error" => "La catégorie n'existe pas ou est vide"]);
            die;
        }

        $sort = "";
        $wireless = "";
        
        if (isset($method["wire"]) && !empty(trim($method["wire"])) && $method["wire"] == "isWireless") $wireless = "AND WHERE p.wireless = 1";

        if (isset($method["sort"]) && !empty(trim($method["sort"]))) {
            if ($method["sort"] == "byPriceAsc") {
                $sort = "ORDER BY price ASC";
            } else if ($method["sort"] == "byPriceDesc") {
                $sort = "ORDER BY price DESC";
            } else if ($method["sort"] == "byNewness") {
                $sort = "ORDER BY adding_date DESC";
            }
        }

        $sortTest = $method["sort"];
        $wireTest = $method["wire"];

        $req = $db->prepare("SELECT p.id, p.name, p.price, p.wireless, p.image, p.adding_date, c.name AS category_name FROM products p INNER JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? $wireless $sort");

        $req->execute([$method["category_id"]]);

        if ($req) $products = $req->fetchAll(PDO::FETCH_ASSOC);
        else $products =[];

        echo json_encode(["success" => true, "products" => $products]);
        break;

    case "select_id":
        if (!isset($method["id"]) || empty(trim($method["id"]))) {
            echo json_encode(["success" => false, "error" => "L'id n'existe pas ou est vide"]);
            die;
        }

        $req = $db->prepare("SELECT p.*, c.name AS category_name FROM products p INNER JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
        $req->execute([$method["id"]]);

        if ($req) $product = $req->fetchALl(PDO::FETCH_ASSOC);
        else $product =[];

        echo json_encode(["success" => true, "product" => $product]);
        break;

    default:
        echo json_encode(["success" => false, "error" => "Ce choix est inexistant"]);
        break;
}