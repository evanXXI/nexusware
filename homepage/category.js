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
                const cardCtn = $("<div></div>").addClass("card bg-dark text-white m-3");

                const img = $("<img>").addClass("card-img p-5");
                img.attr("src", "../assets/" + cat.image);
                img.attr("alt", cat.image);

                const cardTitle = $("<h2></h2>").addClass("card-title fs-4").text(cat.name);
                
                const cardOverlay = $("<div></div>").addClass("card-img-overlay");
                
                cardOverlay.append(cardTitle);
                cardCtn.append(img, cardOverlay);
                $("#categoryCtn").append(cardCtn);
            });
        }
    }
});