window.addEventListener("load", () => {
    let items = document.querySelectorAll(".show-items");
    let products = document.querySelectorAll(".products");

    console.log(items);
    for (let i = 0; i < items.length; i++) {
        let item = items[i];
        item.addEventListener("click", function() {
            let product = products[i];
            product.classList.toggle("hidden");
        })
    }
})