$("header nav div #cartNavbarToggleDisplay").hide();

const cartBtn = $("<button></button>").addClass("btn");
cartBtn.attr("id", "cartBtn");
cartBtn.html("<i class='bi bi-cart'></i>");

$("header nav div .navbar-toggler").click(() => {
    $("header nav div #cartBtn").hide();
    $("header nav div #cartNavbarToggleDisplay").show();
    
    /* $("header nav div .navbar-toggler").click(() => {
        $("header nav div #cartBtn").show();
        $("header nav div #cartNavbarToggleDisplay").hide();
    }); */
});


$.ajax({
    url: "../php/category.php",
    type: "GET",
    dataType: "json",
    data: {
        choice:"select"
    },
    success: (res) => {
        if (res.success) {
            console.log(res.categories);

            res.categories.forEach(cat => {
                const cardCtn = $("<div></div>").addClass("card-group");
                const card = $("<div></div>").addClass("card text-white m-3");

                const img = $("<img>").addClass("card-img h-100 w-100 p-4");
                img.attr("src", "../assets/" + cat.image);
                img.attr("alt", cat.image);

                const cardTitle = $("<h2></h2>").addClass("card-title fs-6 w-100").text(cat.name);
                
                const cardOverlay = $("<div></div>").addClass("card-img-overlay");
                
                cardOverlay.append(cardTitle);
                card.append(img, cardOverlay);
                cardCtn.append(card);
                $("#categoryCtn").append(cardCtn);
            });
        }
    }
});