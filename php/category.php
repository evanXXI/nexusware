<?php
//? //? Intégration du contenu de mon fichier de connexion à la BDD dans le fichier actuel
require_once("./utils/db-connect.php");

//? Intégration du fichier qui contient mes fonctions
require("./utils/function.php");

//? Si ma méthode de requète est POST, alors j'affecte à ma variable $method le contenu de la superglobale $_POST, sinon je lui affecte celui de $_GET
if ($_SERVER["REQUEST_METHOD"] == "POST") $method = $_POST;
else $method = $_GET;

switch ($method["choice"]) { 
    case "select":
        $req = $db->query("SELECT * FROM categories");

        if ($req) $categories = $req->fetchAll(PDO::FETCH_ASSOC);
        else $categories = [];
        
        echo json_encode(["success" => true, "categories" => $categories]);
        break;
    
    case "select_id":
        //? Si dans ma méthode de requète le paramètre "id" n'existe pas ou est vide, alors j'affiche un message d'erreur
        if (!isset($method["id"]) || empty(trim($method["id"]))) {
            echo json_encode(["success" => false, "error" => "La catégorie recherchée n'existe pas"]);
            die; //! J'arrête l'éxécution du script
        }

        $req = $db->prepare("SELECT * FROM categories WHERE id = ?");
        $req->execute([$method["id"]]);

        //? Si la requète renvoie un résutat, stocke tout son contenu dans une variable, sinon on y stocke un tableau vide
        if ($req) $category = $req->fetch(PDO::FETCH_ASSOC);
        else $category = [];
        
        echo json_encode(["success" => true, "category" => $category]);
        break;
    
    default:
        echo json_encode(["success" => false, "error" => "Ce choix est inexistant"]);
        break;
}
?>