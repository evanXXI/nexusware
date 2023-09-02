//* Affichage des infos personnelles
$.ajax({
    url: "../php/users.php",
    type: "GET",
    dataType: "json",
    data: {
        choice: "select_id",
        id: user.id
    },
    success: (res) => {
        if (res.success) {
            $("#lastname").val(res.user.lastname);
            $("#firstname").val(res.user.firstname);
            $("#birthdate").val(res.user.birthdate);
            
            $("#street_no").val(res.user.street_number);
            $("#street_extension").val(res.user.street_bis);
            $("#street_name").val(res.user.street_name);
            $("#zip_code").val(res.user.zip_code);
            $("#country").val(res.user.country);
            $("#email").val(res.user.email);
        }else{
            console.log(res.error);
        }
    }
});

//* Modification des infos personnelles
$("#modify").click(() => {
    
    $.ajax({
        url: "../php/users.php",
        type: "POST",
        dataType: "json",
        data: {
            choice: "update",
            id: user.id
        },
        success: (res) => {
            if (res.success) {
                console.log(res);
            }else console.log(res.error);
        }
    });
});

//** NAVIGATION **//
$("div div div ul li a[href='profile.html?favorites']").click(e => {
    e.preventDefault();
    $("#userForm").addClass("d-none");
    console.log("Ok click !!!")
});

$("div div div ul li a[href='profile.html?orders']").click(e => {
    e.preventDefault();
    $("#userForm").addClass("d-none");
    console.log("Ok click !!!")
});

$("div div div ul li a[href='profile.html?messages']").click(e => {
    e.preventDefault();
    $("#userForm").addClass("d-none");
    console.log("Ok click !!!");
});

$("div div div ul li a[href='profile.html?infos']").click(e => {
    e.preventDefault();
    $("#userForm").removeClass("d-none");
    $("#userForm").addClass("d-block");
    console.log("Ok click !!!");
});

