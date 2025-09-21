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
    // Hapus kelas 'btn-primary' dari semua tombol ukuran
    document.querySelectorAll(".size-btn").forEach((btn) => {
        btn.classList.remove("btn-primary");
        btn.classList.add("btn-outline");
    });

    // Tambahkan kelas 'btn-primary' ke tombol yang dipilih
    button.classList.add("btn-primary");
    button.classList.remove("btn-outline");

    // Simpan ID ukuran yang dipilih di input tersembunyi
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

    // Simpan ID ukuran yang dipilih di input tersembunyi
    document.getElementById("size_stock_product_id").value = sizeStockId;
}
