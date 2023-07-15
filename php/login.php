<?php
//? Permet de démarrer la session sur ce fichier. Des infos seront donc stockées dans la superglobale $_SESSION 
session_start();

//? J'intègre UNE SEULE FOIS le fichier de connexion à ma BDD dans le fichier actuel
require_once("./utils/db-connect.php");

//? Si les paramètres récupérés en entrée utilisateur ne sont pas "email" et "pwd", alors j'envoie un message d'erreur
if(!isset($_POST["email"], $_POST["pwd"])) {
    echo json_encode(["success" => false, "error" => "Données manquantes"]);
    die; //! Arrêt de l'éxcécution du script
}

//? Si les paramètres "email" et "pwd" récupéres en entrée sont vides, alors j'envoie un message d'erreur
if (empty(trim($_POST["email"])) || empty(trim($_POST["pwd"]))){
    echo json_encode(["success" => false, "error" => "Données vides"]);
    die; //! Arrêt de l'éxécution du script
}

//? Requète préparée: Je selectionne tous les utilisateurs dont l'email est égal à celui récupéré en entrée utilisateur
$req = $db->prepare("SELECT * FROM users WHERE email = ?");
$req->execute([$_POST["email"]]);  

//? J'affecte à ma varialbe $user le résultat de ma requète. Le résultat peut très bien être vide 
$user = $req->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($_POST["pwd"], $user["pwd"])) {
    $_SESSION["connected"] = true;
    $_SESSION["userd_id"] = true;
    $_SESSION["admin"] = $user["admin"];

    //! Je retire la donnée hash du mot de passe dans ma variable $user
    unset($user["pwd"]);

    echo json_encode(["success" => true, "user" => $user]);
}else {
    $_SESSION = [];
    session_destroy();

    echo json_encode(["success" => false, "error" => "Aucun utilisateur"]);
}
?>