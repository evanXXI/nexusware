$("header nav div #cartBtnBurger").hide();

$("header nav div .navbar-toggler").click(() => {
    $("header nav div #cartBtnBasic").hide();
    $("header nav div #cartBtnBurger").show();
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

                const cardLink = $("<a></a>").addClass("stretched-link");
                cardLink.attr("href", "homepage");
                const cardOverlay = $("<div></div>").addClass("card-img-overlay");
                
                cardOverlay.append(cardTitle);
                card.append(img, cardLink, cardOverlay);
                cardCtn.append(card);
                $("#categoryCtn").append(cardCtn);
            });
        }
    }
});