//? Si l'utilisateur est connecté, alors j'affiche ses infos dans les champs, et je les désactive
if (user) {
    $("#lastname").val(user.lastname);
    $("#lastname").prop("disabled", true);

    $("#firstname").val(user.firstname);
    $("#firstname").prop("disabled", true);

    $("#email").val(user.email);
    $("#email").prop("disabled", true);
}

//? Au click du bouton submit, je fais un appel AJAX à mon fichier d'ajout de messages
$("#msgSubmit").click((e) => {
    e.preventDefault();

    $.ajax({
        url: "../php/messages.php",
        type: "POST",
        datatype: "json",
        data: {
            choice: "insert",
            object: $("#object").val(),
            content: $("#content").val(),
            user_id: user.id
        },
        success: (res) => {
            console.log(res);
            if (res.success) console.log("ok !")
            else alert(res.error);
        }
    });
});

//? Si l'utilisateur sélectionne l'option "Autre" dans l'objet, alors
/* $("select option[value=Autre]").each(function() {
    if($(this).is(":selected")) {
        $("select").removeAttr(id);

        const object = $("<input type='text' id='object' class='form-control'>"); 
        $("#objectField").append(object);
    }
    else console.log("not ok...");
}); */

/* if($("select option:selected").text() == "Autre") {
    $("select").removeAttr("id");

    const object = $("<input type='text' id='object' class='form-control'>");
    const label = $("<label for='object' class='form-label'>Objet</label>");
    $("#objectField").append(object, label);
}
else console.log("not ok..."); */
