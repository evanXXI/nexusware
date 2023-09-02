const urlParams = new URLSearchParams(window.location.search);
const productId = urlParams.get("id");

$.ajax({
    url: "/nexusWareProject/nexusware/php/products.php",
    type: "GET",
    dataType: "json",
    data: {
        choice: "select_id",
        id: productId
    },
    success: (res) => {
        if (res.success) {
            res.product.forEach(prod => {
                const img = $("<img>").addClass("img-fluid");
                img.attr("src", "/nexusWareProject/nexusware/assets/"+prod.image);
                img.attr("alt", prod.image);
                $("#imgCtn").append(img);

                $("#productName").text(prod.name);

                const price = $("<p></p>").addClass("fs-4 m-0");
                price.text(prod.price + "€");

                const stock = $("<small></small>").addClass("fw-bold mb-3");
                if (prod.stock >= 25) {
                    stock.addClass("text-success");
                    stock.text("En stock");
                }
                else if (prod.stock >= 1) {
                    stock.addClass("text-warning");
                    stock.text("Stock faible");
                }
                else {
                    stock.addClass("text-danger");
                    stock.text("En rupture de stock");
                }

                const desc = $("<p></p>").addClass("fs-6");
                desc.text(prod.description);

                const wireless = $("<button></button>").addClass("wireBtn rounded border-0 fw-bolder");
                if (prod.wireless == 1) wireless.text("Sans-fil");
                else wireless.text("Filaire");

                const categoryBtn = $("<button></button>").addClass("categoryBtn rounded border-0 fw-bolder ms-1").text(prod.category_name);

                const buttonDiv = $("<div><div>").addClass("d-flex");
                buttonDiv.append(wireless, categoryBtn);

                const addToCart = $("<button></button>").addClass("btn rounded border-0 mt-3 fw-bold");
                addToCart.text("Ajouter au panier");

                $("#productInfos").append(price, stock, desc, buttonDiv, addToCart);
            });
        }else alert(res.error);
    }
});