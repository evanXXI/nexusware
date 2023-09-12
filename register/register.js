$("form").submit(e => {
    e.preventDefault(); // J'empêche le comportement par défaut de l'événement. Ici la soumission du formulaire recharge la page

    $.ajax({
        url: "../php/register.php",
        type: "POST",
        dataType: "json",
        data: {
            firstname: $("#firstname").val(),
            lastname: $("#lastname").val(),
            birthdate: $("#birthdate").val(),
            street_number: $("#street_no").val(),
            street_bis: $("#street_extension").val(),
            street_name: $("#street_name").val(),
            zip_code: $("#zip_code").val(),
            country: $("#country").val(),
            email: $("#email").val(),
            pwd: $("#pwd").val()
        },
        success: (res) => {
            if (res.success) {
                if (confirm("Inscription réussie !\nDésirez-vous vous connecter ?\nSi oui, cliquez sur Yes, sinon vous serez redirrigé vers la page d'accueil.")) window.location.replace("../homepage/index.html");
            }else alert(res.error);
        }
    });
});