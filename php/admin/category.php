<?php
//? //? Intégration du contenu de mon fichier de connexion à la BDD dans le fichier actuel
require_once("../utils/db-connect.php");

//? Intégration du fichier qui contient mes fonctions
require("../utils/function.php");

//? Le reste du script ne va s'exécuter qui si et seulement si l'utilisateur est connécté et qu'il est administrateur
isConnected();
isAdmin();

//? Si ma méthode de requète est POST, alors j'affecte à ma variable $method le contenu de la superglobale $_POST, sinon je lui affecte celui de $_GET
if ($_SERVER["REQUEST_METHOD"] == "POST") $method = $_POST;
else $method = $_GET;

switch ($method["choice"]) {
    case "select":
        $req = $db->query("SELECT * FROM categories");

        if ($req) $categories = $req->fetchAll(PDO::FETCH_ASSOC);
        else $categories = [];
        
        echo json_encode(["success" => true, "catégories" => $categories]);
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

    case "insert":
        //? Si ma méthode de requète n'est pas POST, alors j'affiche un message d'erreur
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            echo json_encode(["success" => false, "error" => "Mauvaise méthode"]);
            die; //! J'arrête l'exécution du script
        }

        //? Si le paramètre "name" contenu dans ma méthode de requète n'existe pas ou est vide, alors j'affiche un message d'erreur
        if (!isset($method["name"]) || empty(trim($method["name"]))) {
            echo json_encode(["success" => false, "error" => "Le nom de la catégorie n'existe pas ou est vide"]);
            die; //! J'arrête l'exécution du script
        }

        $img = false;
        if (isset($_FILES["image"]["name"])) $img = upload($_FILES);

        $req = $db->prepare("INSERT INTO categories(name, image) VALUES (:name, :image)");

        $req->bindValue(":name", $method["name"]);
        if ($img) $req->bindValue(":image", $img);
        else $req->bindValue(":image", null);

        $req->execute();

        $category_id = $db->lastInsertId();

        echo json_encode(["success" => true]);
        break;

    case "update":
        //? Si ma méthode de requète n'est pas POST, alors j'affiche un message d'erreur
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            echo json_encode(["success" => false, "error" => "Mauvaise méthode"]);
            die; //! J'arrête l'exécution du script
        }

        //* Je crée une variable qui va contenir les paramètres à vérifier
        $parameters = ["id", "name"];
        
        foreach ($parameters as $parameter) {
            //? Si un de mes paramètres n'existe pas ou est vide, alors j'affiche un message d'erreur 
            if (!isset($method[$parameter]) || empty(trim($method[$parameter]))) {
                echo json_encode(["success" => false, "error" => "Paramètres manquants ou vides"]);
                die; //! J'arrête l'exécution du script
            }
        }

        $img = false;
        if (isset($_FILES["image"]['name'])) $img = upload($_FILES);

        $img_req = "";
        if ($img) $img_req  =  ", image = :image";

        $req = $db->prepare("UPDATE categories SET name=:name $img_req WHERE id=:id");

        $req->bindValue(":id", $method["id"]);
        $req->bindValue(":name", $method["name"]);
        if ($img) $req->bindValue(":image", $img);

        $req->execute();

        if ($req->rowCount()) echo json_encode(["success" => true]);
        else echo json_encode(["success" => false, "error" => "Erreur lors de la mise à jour"]);
        break;

    case "delete":
        //? Si ma méthode de requète n'est pas POST, alors j'affiche un message d'erreur
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            echo json_encode(["success" => false, "error" => "Mauvaise méthode"]);
            die; //! J'arrête l'exécution du script
        }

        $req = $db->prepare("DELETE FROM categories WHERE id = ?");

        $req->execute([$method["id"]]);

        if ($req->rowCount()) echo json_encode(["success" => true]);
        else json_encode(["success" => false, "error" => "Erreur mlors de la suppression"]);
        break;
    
    default:
        echo json_encode(["success" => false, "error" => "Ce choix est inexistant"]);
        break;
}
?>