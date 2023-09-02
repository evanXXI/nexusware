const urlParams = new URLSearchParams(window.location.search);
const category = urlParams.get("category_id");
const byPriceAsc = urlParams.get("byPriceAsc");
const byPriceDesc = urlParams.get("byPriceDesc");
const byNewness = urlParams.get("byNewness");

let displayChoice = "select";

if (category) {
    displayChoice = "selectByCategory";
}

if (byPriceAsc) {
    displayChoice = "selectByPriceAsc";
}

if (byPriceAsc) {
    displayChoice = "selectByPriceAsc";
}

if (byNewness) {
    displayChoice = "selectByNewness";
}

$.ajax({
    url: "../php/category.php",
    type: "GET",
    dataType: "json",
    data: {
        choice: "select"
    },
    success: (res) => {
        if (res.success) {
            res.categories.forEach(cat => {
                const catLink = $("<a></a>").addClass("link stretched-link text-dark");
                catLink.attr("href", "products.html?category_id=" + cat.id);
                catLink.text(cat.name);

                const catName = $("<p></p>").addClass("p-0 m-0 me-1");
                catName.append(catLink);
                
                const listElement = $("<li></li>").addClass("list-group-item list-group-item-action d-flex flex-row");
                listElement.append(catName);

                $("div div div ul").append(listElement);
            });
        }
    }
});

function displayProduct(prod) {
    const card = $("<div></div>").addClass("card");
                
    const img = $("<img>").addClass("card-img-top img-fluid");
    img.attr("src", "../assets/" + prod.image);
    img.attr("alt", prod.image);    

    const cardTitle = $("<h2></h2>").addClass("card-title fs-6").text(prod.name);

    const cardPrice = $("<p></p>").addClass("card-text m-0").text(prod.price + "€");

    const cardLink = $("<a></a>").addClass("card-link stretched-link");
    cardLink.attr("href", "./product/product.html?id="+prod.id);
    
    const cardIcons = $("<div><div>").addClass("card-text d-flex");

    const favIcon = $("<i class='bi bi-heart'></i>");
    
    const categoryBtn = $("<button></button>").addClass("categoryBtn rounded border-0 fw-bold ms-1").text(prod.category_name);

    cardIcons.append(favIcon, categoryBtn);

    const newBtn = $("<button></button>").addClass("newBtn rounded border-0 fw-bold ms-1");
    newBtn.text("NEW !");

    var oneWeekAgo = new Date();
    oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);

    var addingDate = new Date(prod.adding_date);

    if (addingDate > oneWeekAgo) {
        cardIcons.append(newBtn);
    }

    const cardBody = $("<div></div>").addClass("card-body");

    cardBody.append(cardTitle, cardPrice, cardLink, cardIcons);

    const col = $("<div><div>").addClass("col");

    card.append(img, cardBody);
    col.append(card);
    $("#productRow").append(col);
}

$.ajax({
    url: "../php/products.php",
    type: "GET",
    dataType: "json",
    data: {
        choice: displayChoice,
        category_id: category
    },
    success: (res) => {
        if (res.success) {
            res.products.forEach(prod => {
            displayProduct(prod);
            });
        }
    }
});