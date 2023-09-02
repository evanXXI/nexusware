<?php
//? Intégration du contenu de mon fichier de connexion à la BDD dans le fichier actuel
require_once("./utils/db-connect.php");

//? Intégration du fichier qui contient mes fonctions
require("./utils/function.php");

//? Le reste du script ne va s'exécuter qui si et seulement si l'utilisateur est connecté
isConnected();

//? Si ma méthode de requète est POST, alors j'affecte à ma variable $method le contenu de la superglobale $_POST, sinon je lui affecte celui de $_GET
if ($_SERVER["REQUEST_METHOD"] == "POST") $method = $_POST;
else $method = $_GET;

switch ($method["choice"]) {
    case "select_object":
        if (!isset($method["user_id"]) || empty(trim($method["user_id"]))) {
            //? Si mon paramètre "user_id" est inexistant ou vide, alors j'envoie un message d'erreur
            echo json_encode(["success" => false, "error" => "Données manquantes ou vides"]);
            die; //! J'arrête l'exécution
        }

        if (isset($method["recipient_id"]) || !empty(trim($method["recipient_id"]))) {
            $req = $db->prepare("SELECT m.object, CONCAT(u.firstname, ' ', u.lastname) AS sender FROM messages m INNER JOIN users u ON m.recipient_id = u.id WHERE m.user_id = :user_id OR m.recipient_id = :recipient_id");

            $req->bindValue(":user_id", $method["user_id"]);
            $req->bindValue(":recipient_id", $method["recipient_id"]);

            $req->execute();
            $messages = $req->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(["success" => true, "messages" =>$messages]);
            break;

        } else {
            $req = $db->prepare("SELECT object FROM messages WHERE user_id = :user_id");
            $req->bindValue(":user_id", $method["user_id"]);

            $req->execute();
            $messages = $req->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(["success" => true, "messages" =>$messages]);
            break;
        }
    
        case "select_content":
            if ((isset($method["user_id"]) && !empty(trim($method["user_id"]))) || isset($method["recipient_id"]) || !empty(trim($method["recipient_id"]))) {
                $req = $db->prepare("SELECT m.content, CONCAT(u.firstname, ' ', u.lastname) AS sender FROM messages m INNER JOIN users u ON m.recipient_id = u.id WHERE m.user_id = :user_id OR m.recipient_id = :recipient_id;");
    
                $req->bindValue(":user_id", $method["user_id"]);
                $req->bindValue(":recipient_id", $method["recipient_id"]);
    
                $req->execute();
    
                if ($req) $messages = $req->fetchAll(PDO::FETCH_ASSOC);
                else $messages = "Aucun message";
    
                echo json_encode(["success" => true, "messages" => $messages]);
            } else {
                //? Si mon paramètre "user_id" ou "recipient_id" est existant et n'est pas vide, alors j'envoie un message d'erreur
                echo json_encode(["success" => false, "error" => "Données manquantes ou vides"]);
                die; //! J'arrête l'exécution
            }
            break;

    case "insert":
        //? Si ma méthode de requète n'est pas POST, alors j'affiche un message d'erreur
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            echo json_encode(["success" => false, "error" => "Mauvaise méthode"]);
            die; //! J'arrête l'exécution
        }

        //? Je crée un tableau dans lequel je vais stocker les paramètres que j'ai besoin de vérifier
        $parameters = ["object", "content", "user_id"];


        foreach ($parameters as $parameter) {
            //? Si un de mes paramètres n'existe pas ou est vide, alors j'affiche un message d'erreur 
            if (!isset($method[$parameter]) || empty(trim($method[$parameter]))) {
                echo json_encode(["success" => false, "error" => "Données manquantes ou vides"]);
                die; //! J'arrête l'exécution
            }
        }

        $req = $db->prepare("INSERT INTO messages(object, content, user_id) VALUES (:object, :content, :user_id)");

        $req->bindValue(":object", $method["object"]);
        $req->bindValue(":content", $method["content"]);
        print_r($method);
        $req->bindValue(":user_id", $method["user_id"]);

        $req->execute();

        $message_id = $db->lastInsertId();

        echo json_encode(["success" => true, "message_id" => $message_id]);
        break;

    case "delete":
        //? Je crée un tableau dans lequel je vais stocker les paramètres que j'ai besoin de vérifier
        $parameters = ["id", "user_id"];

        foreach ($parameters as $parameter) {
            //? Si mon paramètre "id" est inexistant ou inexistant, alors j'envoie un message d'erreur
            if ((!isset($method[$parameter]) || empty(trim($method[$parameter])))) {
                echo json_encode(["success" => false, "error" => "Données manquantes ou vides"]);
                die; //! J'arrête l'exécution
            }
        }

        //? Si l'utilisateur n'est pas l'auteur du message, j'affiche un message d'erreur
        if ($method["user_id"] != $_SESSION["user_id"]) {
            echo json_encode(["success" => false, "error" => "Vous n'êtes pas l'auteur de ce message, vous ne pouvez donc pas le supprimer"]);
            die; //! J'arrête l'exécution
        }
        
        $req = $db->prepare("DELETE FROM messages WHERE id = :id");
        $req->bindValue(":id", $method["id"]);
        $req->execute();

        if ($req) $messages = $req->fetchAll(PDO::FETCH_ASSOC);
        else $messages = "Aucun message";

        if ($req->rowCount()) echo json_encode(["success" => true, "messages" => $messages]);
        else echo json_encode(["success" => false, "Erreur lors de la suppression du message"]);
        break;

    default:
        echo json_encode(["success" => false, "error" => "Ce choix est inexictant"]);
        break;
}