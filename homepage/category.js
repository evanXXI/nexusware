$.ajax({
    url: "../php/category.php",
    type: "GET",
    dataType: "json",
    data: {
        choice:"select"
    },
    success: (res) => {
        if (res.success) {
            res.categories.forEach(cat => {
                const cardCtn = $("<div></div>").addClass("card-group");
                
                const card = $("<div></div>").addClass("card text-white m-3");
                
                const img = $("<img>").addClass("card-img h-100 p-4");
                img.attr("src", "../assets/" + cat.image);
                img.attr("alt", cat.image);

                const cardTitle = $("<h2></h2>").addClass("card-title fs-6 w-100").text(cat.name);

                const cardLink = $("<a></a>").addClass("card-link stretched-link");
                cardLink.attr("href", "/nexusWareProject/nexusware/products/products.html?category_id=" + cat.id);
                const cardOverlay = $("<div></div>").addClass("card-img-overlay");
                
                cardOverlay.append(cardTitle);
                card.append(img, cardLink, cardOverlay);
                cardCtn.append(card);
                $("#categoryCtn").append(cardCtn);
            });
        }else alert(res.error);
    }
});