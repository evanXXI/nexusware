//* Je récupère les paramètres de l'url
const urlParams = new URLSearchParams(window.location.search);

//? Si j'ai le paramètre logout dans mon url, alors
if (urlParams.get("logout")) {
    //Je fais un appel AJAX à mon fichier de déconnexion
    $.ajax({
        url: "../php/logout.php",
        type: "GET",
        dataType: "json",
        success: () => {
            //! Je supprime l'utilisateur de mon stockage local, car il s'est déconnecté
            localStorage.removeItem("user");

            $("#profileBtn").hide();
            $("#logoutBtn").hide();
            
            $("#registerOption").show();
            $("#loginOption").show();
        }
    });
}

$("form").submit((e) => {
    e.preventDefault(); // J'empêche le comportement par défaut de la page. Ici, la soumission du formulaire ne rechargera pas la page

    $.ajax({
        url: "../php/login.php",
        type: "POST",
        dataType: "json",
        data: {
            email: $("#email").val(),
            pwd: $("#pwd").val()
        },
        success: (res) => {
            if (res.success) {
                //? Si la réponse est un succès, alors
                localStorage.setItem("user", JSON.stringify(res.user)); // J'ajoute les informations de mon utilisateur dans le stockage local
                window.location.replace("../homepage/index.html"); //Je redirige l'utilisateur vers la page d'accueil
            } else alert(res.error); //! Sinon, j'affiche une boîte de dialogue avec l'erreur
        }
    });
});


/* //? Icône pour montrer/ cacher le mot de passe
$("form div #pwdShow").click(() => {
    if ($("#pwd:password").lenght) {
        $("form div #pwdShow").removeClass("bi-eye");
        $("form div #pwdShow").addClass("bi-eye-slash");
        $("#pwd").attr("type", "text");
    } else {
        $("form div #pwdShow").removeClass("bi-eye-slash");
        $("form div #pwdShow").addClass("bi-eye");
        $("#pwd").attr("type", "password");
    }
}); */