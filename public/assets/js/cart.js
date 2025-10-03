document.addEventListener("DOMContentLoaded", () => {
    const cartQuantitiesInput = document.getElementById("cart-quantities");
    const checkboxes = document.querySelectorAll(".cart-checkbox");
    const grandTotalElement = document.getElementById("grand-total");
    const cartItemsContainer = document.getElementById("cart-items");

    function number_format(number) {
        return new Intl.NumberFormat("id-ID").format(number);
    }

    // Calculate and update individual item total
    function updateItemTotal(itemId, quantity, price) {
        const itemTotalElement = document.querySelector(
            `.item-total-price[data-id='${itemId}'] .total-price`
        );
        if (itemTotalElement) {
            const itemTotal = quantity * price;
            itemTotalElement.textContent = number_format(itemTotal);
        }
    }

    function isCartEmpty() {
        return !cartItemsContainer || cartItemsContainer.children.length === 0;
    }

    function updateGrandTotal() {
        let grandTotal = 0;

        checkboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                const price = parseInt(checkbox.dataset.price);
                const quantityElement = checkbox.closest(".card").querySelector(".quantity");
                const quantity = parseInt(quantityElement ? quantityElement.dataset.qty : 1);
                grandTotal += price * quantity;
            }
        });

        grandTotalElement.textContent = `Rp ${number_format(grandTotal)}`;
    }

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", updateGrandTotal);
    });

    function updateQuantity(button) {
        const itemId = button.dataset.id;
        const action = button.dataset.action;
        const stock = parseInt(button.dataset.stock);
        const price = parseInt(button.dataset.price);
        const quantityElement = document.querySelector(
            `.quantity[data-id='${itemId}']`
        );
        let quantity = parseInt(quantityElement.dataset.qty);

        if (action === "increase") {
            if (quantity < stock) {
                quantity++;
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Stok Habis",
                    text: "Jumlah tidak bisa melebihi stok tersedia!",
                });
                return;
            }
        } else if (action === "decrease" && quantity > 1) {
            quantity--;
        }

        quantityElement.dataset.qty = quantity;
        quantityElement.textContent = quantity;

        updateItemTotal(itemId, quantity, price);

        // Update localStorage
        const cartData = JSON.parse(localStorage.getItem("cartQuantities")) || {};
        cartData[itemId] = quantity;
        localStorage.setItem("cartQuantities", JSON.stringify(cartData));

        updateGrandTotal();
    }

    // Load quantities and totals from storage
    function loadQuantitiesFromStorage() {
        // Jika keranjang kosong, hapus data yang tersimpan
        if (isCartEmpty()) {
            localStorage.removeItem('cartGrandTotal');
            localStorage.removeItem('cartQuantities');
            localStorage.removeItem('selectedCartIds');
            if (grandTotalElement) grandTotalElement.textContent = 'Rp 0';
            return;
        }
    
        const cartData = JSON.parse(localStorage.getItem("cartQuantities")) || {};
        const selectedCartIds = JSON.parse(localStorage.getItem("selectedCartIds")) || [];
        document.querySelectorAll(".quantity").forEach((qtyElement) => {
            const itemId = qtyElement.dataset.id;
            if (cartData[itemId]) {
                qtyElement.dataset.qty = cartData[itemId];
                qtyElement.textContent = cartData[itemId];
    
                // Update total harga per item
                const price = parseInt(
                    qtyElement.closest(".card").querySelector(".btn-qty").dataset.price
                );
                updateItemTotal(itemId, cartData[itemId], price);
            }
        });
    
        // Set kembali checkbox yang sebelumnya dipilih
        document.querySelectorAll('.cart-checkbox').forEach((checkbox) => {
            checkbox.checked = selectedCartIds.includes(checkbox.value);
        });
        updateGrandTotal();
    
        document.getElementById("cart-quantities").value = JSON.stringify(cartData);
        document.getElementById("selectedCartIds").value = JSON.stringify(selectedCartIds);
    }
    

    if (cartItemsContainer) {
        cartItemsContainer.addEventListener("click", (event) => {
            if (event.target.classList.contains("btn-qty")) {
                updateQuantity(event.target);
            }
        });
    }
    
    // checkbox item cart
    document.querySelectorAll('.cart-checkbox').forEach((checkbox) => {
        checkbox.addEventListener('change', function() {
            let selectedCartIds = [];
            
            document.querySelectorAll('.cart-checkbox:checked').forEach((checkedBox) => {
                selectedCartIds.push(checkedBox.value);
            });
    
            localStorage.setItem('selectedCartIds', JSON.stringify(selectedCartIds));
            document.getElementById("selectedCartIds").value = JSON.stringify(selectedCartIds);
        });
    });
    
    
    // form checkout
    const checkoutForm = document.querySelector('form[action*="formCheckout"]');
    if (checkoutForm) {
        checkoutForm.addEventListener("submit", (e) => {
            const cartData = JSON.parse(localStorage.getItem("cartQuantities")) || {};
            const selectedCartIds = JSON.parse(localStorage.getItem("selectedCartIds")) || [];
    
            document.getElementById("cart-quantities").value = JSON.stringify(cartData);
            document.getElementById("selectedCartIds").value = JSON.stringify(selectedCartIds);
        });
    }
    
    loadQuantitiesFromStorage();
    
});