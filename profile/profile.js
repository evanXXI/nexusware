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
            alert(res.error);
        }
    }
});

//* Modification des infos personnelles
$("#modify").click(e => {
    e.preventDefault();

    $.ajax({
        url: "../php/users.php",
        type: "POST",
        dataType: "json",
        data: {
            choice: "update",
            id: user.id,
            firstname: $("#firstname").val(),
            lastname: $("#lastname").val(),
            birthdate: $("#birthdate").val(),
            street_number: $("#street_no").val(),
            street_bis: $("#street_extension").val(),
            street_name: $("#street_name").val(),
            zip_code: $("#zip_code").val(),
            country: $("#country").val(),
            email: $("#email").val()
        },
        success: (res) => {
            if (res.success) {
                alert("Modification réussie !");
            }else alert(res.error);
        }
    });
});

//** Affichage des commandes de l'utilisateur **//
$.ajax({
    url: "../php/orders.php",
    type: "GET",
    dataType: "json",
    data: {
        choice: "select",
        user_id: user.id
    },
    success: (res) => {
        if (res.success) {
            res.myOrders.forEach(order => {
                const row = $("<tr></tr>");
    
                const order_no = $("<td></td>").text(order.order_id);
    
                const order_tm = $("<td></td>").text(order.order_time);
    
                const price = $("<td></td>").text(order.total_price);
                price.addClass("d-none d-lg-table-cell");
    
                const statusCtn = $("<td></td>").text(order.order_status);
    
                const address = $("<td></td>").text(order.user_address);
                address.addClass("d-none d-lg-table-cell");
    
                row.append(order_no, order_tm, price, statusCtn, user, address);
    
                $("#ordersCtn tbody").append(row);
                });
        }else alert(res.error);
    }
});

//** NAVIGATION **//
$("div div div ul li a[href='profile.html?favorites']").click(e => {
    e.preventDefault();
    $("#userForm").addClass("d-none");
});

$("div div div ul li a[href='profile.html?orders']").click(e => {
    e.preventDefault();
    $("#userForm").addClass("d-none");
    $("#ordersCtn").removeClass("d-none");

    $("#ordersCtn").removeClass("d-block");
});

$("div div div ul li a[href='profile.html?messages']").click(e => {
    e.preventDefault();
    $("#userForm").addClass("d-none");
});

$("div div div ul li a[href='profile.html?infos']").click(e => {
    e.preventDefault();
    $("#ordersCtn").addClass("d-none");

    $("#userForm").removeClass("d-none");
    $("#userForm").addClass("d-block");
});

