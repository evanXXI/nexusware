//* Authentification de l'utilisateur
let user = JSON.parse(localStorage.getItem("user"));


//* Affichage du footer dans chaque page
$(() => {
    $("footer").load("/nexusware/utils/footer.html");
});


//* Création du bouton "Admin" dans la navbar
const adminLink = $("<a></a>").addClass("nav-link");
adminLink.attr("href", "../admin/admin.html");
adminLink.text("Administrateur");

const admin = $("<li></li>").addClass("nav-item");
admin.attr("id", "adminBtn");
admin.append(adminLink);

//* Création des boutons "Profil" et "Déconnexion" dans la navbar
const profileLink = $("<a></a>").addClass("nav-link");
profileLink.attr("href", "../profile/profile.html");
profileLink.text("Profil");

const profile = $("<li></li>").addClass("nav-item");
profile.attr("id", "profileBtn");
profile.append(profileLink);

const logoutLink = $("<a></a>").addClass("nav-link");
logoutLink.attr("href", "../login/login.html?logout=true");
logoutLink.text("Déconnexion");
const logout = $("<li></li>").addClass("nav-item");
logout.attr("id", "logoutBtn");
logout.append(logoutLink);

if (user && user.admin != 0) {
    $("#navOptionList").append(admin);
}

if (user) {
    $("#registerOption").addClass("d-none");
    $("#loginOption").addClass("d-none");

    if (user.admin != 0) $("#navOptionList").append(admin);
    $("#navOptionList").append(profile, logout);
} else {   
    $("#registerOption").remove(profile);
    $("#loginOption").remove(logout);
    $("#navOptionList").remove(admin);
    
    $("#registerOption").removeClass("d-none");
    $("#loginOption").removeClass("d-none");
}