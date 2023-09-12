<?php
//? Intégration du contenu de mon fichier de connexion à la BDD dans le fichier actuel
require_once("./utils/db-connect.php");

//? Intégration du contenu de mon fichier d'envoi de mails
require_once("./mailer.php");

//? Si la méthode de requète est différente de POST, on envoie une réponse de non-succès et un message d'erreur 
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["success" => false, "error" => "Mauvaise méthode de requète des valeurs de champs"]);
    //! Arrêt de l'éxécution du script
    die;
}

//? Je crée un tableau dans lequel je vais stocker mes paramètres, qui sont ici les valeurs des champs à récupérer en entrée de l'utilisateur. Cela me permet de modifier facilement les champs en cas de changements futurs en BDD.
$parameters = ["firstname", "lastname", "birthdate", "street_number", "street_bis", "street_name", "zip_code", "country", "email", "pwd"];

//? Je parcoure le tableau contenant mes paramètres
foreach ($parameters as $parameter) {
    //? Si un de mes paramètres n'est pas récupéré ou est laissé vide, alors j'envoie une réponse de non-succès et un message d'erreur 
    if (!isset($_POST[$parameter]) || empty(trim($_POST[$parameter]))) {
        echo json_encode(["success" => false, "error" => "Données manquantes ou vides"]);
        die;
    }
}

//? La variable regex contient l'expression régulière qui régule le format de l'adresse mail de l'utilisateur 
$regex = "/^[a-zA-Z0-9-+._]+@[a-zA-Z0-9-]{2,}\.[a-zA-Z]{2,}$/";

//? Si l'email entré ne vérifie pas le format de l'expression régulière, alors j'envoie un message d'erreur
if (!preg_match($regex, $_POST["email"])) {
    echo json_encode(["success" => false, "error" => "L'email n'est pas au bon format"]);
    die;
}

$regex = "/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])[a-zA-Z0-9]{8,}$/";
if (!preg_match($regex, $_POST["pwd"])) {
    echo json_encode(["success" => false, "error" => "Le mot de passe n'est pas au bon format"]);
    die;
}

//? Le mot de passe entré est directement haché via une fonction, avant d'être stocké dans la BDD. PASSWORD_DEFAULT utilise l'algorythme bcrypt, qui est sujette à des mises à jours. Le nombre de caratères stocké peut donc changer. Il est conseillé de prévoir au moins 60 caractères en BDD. 
$hash = password_hash($_POST["pwd"], PASSWORD_DEFAULT);

//? Requète préparée d'insertion du nouvel utilisateur dans la BDD
$req = $db->prepare("INSERT INTO users(firstname, lastname, birthdate, street_number, street_name, zip_code, country, email, pwd) VALUES (:firstname, :lastname, :birthdate, :street_number, :street_name, :zip_code, :country, :email, :pwd)");

$req->bindValue(":firstname", $_POST["firstname"]);
$req->bindValue(":lastname", $_POST["lastname"]);
$req->bindValue(":birthdate", $_POST["birthdate"]);
$req->bindValue(":street_number", $_POST["street_number"]);
$req->bindValue(":street_name", $_POST["street_name"]);
$req->bindValue(":zip_code", $_POST["zip_code"]);
$req->bindValue(":country", $_POST["country"]);
$req->bindValue(":email", $_POST["email"]);
$req->bindValue(":pwd", $hash);
$req->execute();

echo json_encode(["success" => true]);

mailer($_POST["email"], "Bienvenue {$_POST["firstname"]}", "Bonjour {$_POST["firstname"]},\n\nToute l'équipe de NexusWare est ravie de vous accueillir sur notre plateforme de vente de hardware en ligne.\n\nNous mettons tout en oeuvre pour la satisfaction du client. Pour toute question ou réclammation en tout genre, vous pouvez nous contacter directement via les rubriques Contact et Messagerie, directement sur notre site internet, ou via l'adresse mail qui suit :\nevan.rekaty-cisse@imie-paris.fr\n\nNous vous souhaitons le meilleur.\n\nBien cordialement,\nNexusWare");
?>