function changeImage(imageUrl) {
    document.getElementById("mainImage").src = imageUrl;
}

function updateQuantity(change) {
    const quantityElement = document.getElementById("quantity");
    let quantity = parseInt(quantityElement.textContent);
    quantity = Math.max(1, quantity + change);
    quantityElement.textContent = quantity;
}

function selectVariant(button, sizeStockId) {
    document.querySelectorAll(".size-btn").forEach((btn) => {
        btn.classList.remove("btn-primary");
        btn.classList.add("btn-outline");
    });

    button.classList.add("btn-primary");
    button.classList.remove("btn-outline");

    const stock = button.getAttribute("data-stock");

    // Pengecekan apakah stok kosong
    if (parseInt(stock) === 0) {
        Swal.fire({
            icon: "error",
            title: "Stok Habis",
            text: "Ukuran ini tidak tersedia, silakan pilih ukuran lain.",
            confirmButtonText: "OK",
        });
        return;
    }

    document.getElementById("size_stock_product_id").value = sizeStockId;
}
