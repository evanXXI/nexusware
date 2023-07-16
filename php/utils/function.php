<?php
session_start();

function isConnected() {
    //? Si la clé "connected" n'existe pas dans la  superglobale SESSION ou que la valeur de "connected" dans $_SESSION n'est pas vraie, alors
    if (!isset($_SESSION["connected"]) || !$_SESSION["connected"]) {
        //J'envoie une réponse avec un success dalse et un message d'erreur
        echo json_encode(["success" => false, "error" => "Vous n'êtes pas connecté"]);
        die; //! J'arrête l'éxecution du script
    }
}

function isAdmin() {
    if (!isset($_SESSION["admin"]) || !$_SESSION["admin"]) {
        echo json_encode(["success" => false, "error" => "Vous n'êtes pas admin"]);
        die; //! J'arrête l'éxecution du script
    }
}

function upload($file) {
    if (isset($file["image"]["name"])) {
        $filename = $file["image"]["name"];

        $location = __DIR__."/../../assets/$filename";

        $extension = pathInfo($location,PATHINFO_EXTENSION);
        $extension = strtolower($extension);

        $validExtensions = ["jpg", "jpeg", "png", "gif", "svg"];

        if (in_array($extension, $validExtensions)) {
            if (move_uploaded_file($file["image"]["tmp_name"], $location)) {
                echo "ok!";
                return $filename;
            }
            else return false;
        }else return false;
    }else return false;
}
?>