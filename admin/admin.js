//** NAVIGATION **//
$("div div div ul li a[href='admin.html?orders']").click(e => {
    e.preventDefault();

    $("h2").text("Gestion des commandes");

    $("canvas").addClass("d-none");
    $("#productsCtn").addClass("d-none");
    $("#categoriesCtn").addClass("d-none");
    $("#usersCtn").addClass("d-none");
    $("#messagingCtn").addClass("d-none");
    
    $("#ordersCtn").removeClass("d-none");
    $("#ordersCtn").addClass("d-block");
});

$("div div div ul li a[href='admin.html?product_management']").click(e => {
    e.preventDefault();

    $("h2").text("Gestion des produits");

    $("canvas").addClass("d-none");
    $("#ordersCtn").addClass("d-none");
    $("#categoriesCtn").addClass("d-none");
    $("#usersCtn").addClass("d-none");
    $("#messagingCtn").addClass("d-none");
    
    $("#productsCtn").removeClass("d-none");
    $("#productsCtn").addClass("d-block");
});

$("div div div ul li a[href='admin.html?category_management']").click(e => {
    e.preventDefault();

    $("h2").text("Gestion des catégories de produits");

    $("canvas").addClass("d-none");
    $("#ordersCtn").addClass("d-none");
    $("#productsCtn").addClass("d-none");
    $("#usersCtn").addClass("d-none");
    $("#messagingCtn").addClass("d-none");
    
    $("#categoriesCtn").removeClass("d-none");
    $("#categoriesCtn").addClass("d-block");
});

$("div div div ul li a[href='admin.html?users_management']").click(e => {
    e.preventDefault();

    $("h2").text("Gestion des utilisateurs");

    $("canvas").addClass("d-none");
    $("#ordersCtn").addClass("d-none");
    $("#productsCtn").addClass("d-none");
    $("#categoriesCtn").addClass("d-none");
    $("#messagingCtn").addClass("d-none");
    
    $("#usersCtn").removeClass("d-none");
    $("#usersCtn").addClass("d-block");
});

$("div div div ul li a[href='admin.html?stats']").click(e => {
    e.preventDefault();

    $("h2").text("Statistiques des ventes");

    $("#usersCtn").addClass("d-none");
    $("#ordersCtn").addClass("d-none");
    $("#productsCtn").addClass("d-none");
    $("#categoriesCtn").addClass("d-none");
    $("#messagingCtn").addClass("d-none");
    
    $("canvas").removeClass("d-none");
    $("canvas").addClass("d-block");
});


$("div div div ul li a[href='admin.html?messaging_management']").click(e => {
    e.preventDefault();

    $("h2").text("Gestion de la messagerie");

    $("canvas").addClass("d-none");
    $("#usersCtn").addClass("d-none");
    $("#ordersCtn").addClass("d-none");
    $("#productsCtn").addClass("d-none");
    $("#categoriesCtn").addClass("d-none");
    
    $("#messagingCtn").removeClass("d-none");
    $("#messagingCtn").addClass("d-block");
});

//** GESTION DES COMMANDES **//
$.ajax({
    url: "../php/admin/orders.php",
    type: "GET",
    dataType: "json",
    data: {
        choice: "select"
    },
    success: (res) => {
        if (res.success) {
            res.orders.forEach(order => {
            const row = $("<tr></tr>");

            const order_no = $("<td></td>").text(order.order_id);

            const order_tm = $("<td></td>").text(order.order_time);

            const price = $("<td></td>").text(order.total_price);
            price.addClass("d-none d-lg-table-cell");

            const statusCtn = $("<td></td>");
            const statusForm = $("<form></form>");

            const statusInput = $("<input class='form-control'>").val(order.order_status);

            const statusBtn = $("<button></button>").addClass("btn");
            statusBtn.html("<i class='bi bi-pencil'></i>");

            statusForm.append(statusInput, statusBtn);
            statusCtn.append(statusForm);

            const user = $("<td></td>").text(order.username);
            user.addClass("d-none d-lg-table-cell");

            const address = $("<td></td>").text(order.user_address);
            address.addClass("d-none d-lg-table-cell");

            row.append(order_no, order_tm, price, statusCtn, user, address);

            $("#ordersCtn tbody").append(row);
            });
        }else alert(res.error);
    }
});


//** GESTION DES PRODUITS **/
$.ajax({
    url: "../php/admin/products.php",
    type: "GET",
    dataType: "json",
    data: {
        choice: "select"
    },
    success: (res) => {
        if (res.success) {
            res.products.forEach(prod => { 
                const row = $("<tr></tr>");

                const productName = $("<td></td>").text(prod.name);
                const desc = $("<td></td>").text(prod.description);
                desc.addClass("d-none d-lg-table-cell");
                const price = $("<td></td>").text(prod.price);
                const stock = $("<td></td>").text(prod.stock);
                stock.addClass("d-none d-lg-table-cell");
                const sold = $("<td></td>").text(prod.sold_units);
                sold.addClass("d-none d-lg-table-cell");

                const dateTime = $("<td></td>").text(prod.adding_date);
                dateTime.addClass("d-none d-lg-table-cell");

                const wire = $("<td></td>").addClass("d-none d-lg-table-cell");
                if (prod.wireless == 1) wire.text("Oui");
                else wire.text("Non");

                const img = $("<td></td>").addClass("d-none d-lg-table-cell");
                if (prod.image != "") img.text(prod.image);
                else img.text("Aucune image");

                const category = $("<td></td>").text(prod.category_name);

                row.append(productName, desc, price, stock, sold, dateTime, wire, img, category);

                $("#productsCtn tbody").append(row);
            });
        }else alert(res.error);
    }
});


//** GESTION DES CATÉGORIES **/
//** AJOUT D'UNE CATÉGORIE **/
function addCategory(cat) {
    const row = $("<tr></tr>").attr("id", "cat" + cat.id);
    const id = $("<td></td>").addClass("d-none d-lg-table-cell");
    id.text(cat.id);
    id.attr("id", "cat_no" + cat.id);
    const name = $("<td></td>").text(cat.name);
    name.attr("id", "name_no" + cat.id);

    const img = $("<td></td>");
    if(cat.image) {
        img.text(cat.image);
        img.attr("id", "img_no" + cat.id);
    }else img.text("Aucune image");

    const updateCatBtn = $("<td></td>").html("<button class='btn catUpdate'><i class='bi bi-pencil'></i></button>"); 

    updateCatBtn.click(() => {
        wantToUpdateCategory(cat.id);
    });
    
    const deleteCatBtn = $("<td></td>").html("<button class='btn'><i class='bi bi-trash'></i></button>");

    deleteCatBtn.click(() => {
        if(confirm("Voulez-vous vraiment supprimer cette catégorie de produits ? Cette action est irréversible.")) deleteCategory(cat.id);
    });

    row.append(id, name, img, updateCatBtn, deleteCatBtn);
    $("#categoriesCtn tbody").append(row);
}

function insertCategory (name, image) {
    const fd = new FormData();
    fd.append("choice", "insert");
    fd.append("name", name);
    fd.append("image", image);

    $.ajax({
        url: "../php/admin/category.php",
        type: "POST",
        dataType: "json",
        contentType: false,
        processData: false, 
        cache: false,
        data: fd,
        success: (res) => {
            if (res.success) {
                console.log(res);
                addCategory({
                    id: res.id,
                    name: res.name,
                    image: res.image 
                });
            }else alert(res.error);
        }
    });
}

$("#addCategory").click(e => {
    e.preventDefault();

    const catId = $("#catId").val();
    const catName = $("#catName").val();
    const catImage = $("#catImage").val();

    if (catId) updateCategory(catId, catName, catImage);
    else insertCategory(catName, catImage);
});

//** AFFICHAGE DES CATÉGORIES **/
$.ajax({
    url: "../php/admin/category.php",
    type: "GET",
    dataType: "json",
    data: {
        choice: "select"
    },
    success: (res) => {
        if (res.success) {
            res.categories.forEach(cat => { 
                addCategory(cat);
            });
        }else alert(res.error);
    } 
});

//** METTRE À JOUR UNE CATÉGORIE **//
function wantToUpdateCategory(id) {
    $.ajax({
        url: "../php/admin/category.php",
        type: "GET",
        dataType: "json",
        data: {
            choice: "select_id",
            id: id
        },
        success: (res) => {
            if (res.success) {
                $("h3").text("Mise à jour d'une catégorie");
                $("#addCategory").text("Modifier");

                $("form #catId").val(res.category.id);
                $("form #catName").val(res.category.name);
                $("form #catImage").val(res.category.image);
            }else alert(res.error);
        }
    }); 
}

function updateCategory(id, name, image) {

    const fd = new FormData();
    fd.append("choice", "update");
    fd.append("id", id);
    fd.append("name", name);
    fd.append("image", image);

    $.ajax({
        url: "../php/admin/category.php",
        type: "POST",
        dataType: "json",
        contentType: false,
        processData: false, 
        cache: false,
        data: fd,
        success: (res) => {
            if (res.success) {
                $("#game_" + id).text(res.game);
                $("#desc_" + id).text(res.desc);
                $("#image_" + id).text(res.image);
            }else alert(res.error);
        }
    });
}

//** SUPPRESSION D'UNE CATÉGORIE **//
function deleteCategory (id) {
    $.ajax({
        url: "../php/admin/category.php",
        type: "POST",
        dataType: "json",
        data: {
            choice: "delete",
            id
        },
        success: (res) => {
            if (res.success) $("#cat" + id).remove();
            else alert(res.error);
        }
    });
}

//** GESTION DES UTILISATEURS **/
$.ajax({
    url: "../php/admin/users.php",
    type: "GET",
    dataType: "json",
    data: {
        choice: "select"
    },
    success: (res) => {
        if (res.success) {
            res.users.forEach(user => { 
                const row = $("<tr></tr>");

                const id = $("<td></td>").addClass("d-none d-lg-table-cell");
                id.text(user.id);

                const fullName = $("<td></td>").text(user.firstname + " " + user.lastname);

                const admin = $("<td></td>").addClass("d-none d-lg-table-cell");
                const deleteBtn = $("<td></td>");

                if (user.admin == 1) admin.text("Oui");
                else {
                    admin.text("Non");
                    deleteBtn.html("<button class='btn'><i class='bi bi-trash'></i></button>");
                }

                const email = $("<td></td>").html(user.email);

                row.append(id, fullName, admin, email, deleteBtn);

                $("#usersCtn tbody").append(row);
            });
        }else alert(res.error);
    }
});

//** Gestion des produits - Tableaux de données **/
const lineChart = $("canvas");

new Chart(lineChart, {
    type: 'line',
    data: {
    labels: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Décembre"],
    datasets: [{
        label: 'HyperX Cloud II',
        data: [12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3],
        borderWidth: 1
    },
    {
        label: 'beyerdynamic DT770 PRO',
        data: [1, 2, 3, 4, 5, 6, 7 , 8, 9, 10, 11, 12],
        borderWidth: 1
    }]
    
    },
    options: {
        scales: {
            y: {
            beginAtZero: true
            }
        }
    }
});