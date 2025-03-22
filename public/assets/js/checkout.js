document.addEventListener("DOMContentLoaded", function () {
    const provinceSelect = document.getElementById("province");
    const citySelect = document.getElementById("city_id");
    const paymentMethod = document.getElementById("payment_method");
    const addressSection = document.getElementById("address_section");
    const buktiTrasaksi = document.getElementById("bukti_transaksi");
    const paymentImageSection = document.getElementById(
        "payment_image_section"
    );
    const checkoutButton = document.getElementById("pay-button");
    const paymentImage = document.getElementById("payment_image");
    const shippingPrice = document.getElementById("shipping_price");
    const shippingPriceTotal = document.getElementById("shipping_price_total");
    const totalPrice = document.getElementById("total_price");

    // [NEW] Voucher related elements
    const checkVoucherBtn = document.getElementById("check_voucher");
    const voucherInput = document.getElementById("voucher_input");
    const voucherMessage = document.getElementById("voucher_message");
    const voucherDiscountSection = document.getElementById(
        "voucher_discount_section"
    );
    const voucherDiscountSpan = document.getElementById("voucher_discount");
    let currentVoucherDiscount = 0;

    // Select province
    provinceSelect.addEventListener("change", function () {
        if (!provinceSelect.value) {
            citySelect.innerHTML = '<option value="">Pilih Kota</option>';
            citySelect.disabled = true;
            shippingPrice.innerText = 0;
            shippingPriceTotal.innerText = 0;
            updateTotalPrice();
            return;
        }

        citySelect.innerHTML = '<option value="">Pilih Kota</option>';
        citySelect.disabled = true;

        fetch(`/get-cities/${provinceSelect.value}`)
            .then((response) => response.json())
            .then((data) => {
                data.forEach((city) => {
                    let option = new Option(city.city_name, city.id);
                    option.dataset.shipping = city.shipping_price;
                    citySelect.appendChild(option);
                });
                citySelect.disabled = false;
            });
    });

    // [MODIFIED] City change handler to include voucher
    citySelect.addEventListener("change", function () {
        let selectedCity = citySelect.options[citySelect.selectedIndex];
        let shippingCost = selectedCity.dataset.shipping || 0;
        shippingPrice.innerText = shippingCost;
        shippingPriceTotal.innerText = shippingCost;
        updateTotalPrice();
    });

    // payment method
    paymentMethod.addEventListener("change", function () {
        let selectedPayment =
            paymentMethod.options[paymentMethod.selectedIndex];
        let paymentName = selectedPayment.dataset.name;
        // let paymentImg = selectedPayment.dataset.img;
        // let inputFile = document.getElementById("input_file");

        if (paymentName === undefined || paymentName === "") {
            addressSection.style.display = "none";
            shippingPrice.innerText = 0;
            shippingPriceTotal.innerText = 0;
            updateTotalPrice();
            // paymentImage.src = paymentImg;

            provinceSelect.value = "";
            citySelect.innerHTML = '<option value="">Pilih Kota</option>';
            citySelect.disabled = true;

            provinceSelect.removeAttribute("required");
            citySelect.removeAttribute("required");
            addressSection.removeAttribute("required");
        } else if (paymentName === "Ambil Ditempat") {
            addressSection.style.display = "none";
            shippingPrice.innerText = 0;
            shippingPriceTotal.innerText = 0;
            updateTotalPrice();
            // paymentImage.src = paymentImg;

            provinceSelect.value = "";
            citySelect.innerHTML = '<option value="">Pilih Kota</option>';
            citySelect.disabled = true;

            provinceSelect.removeAttribute("required");
            citySelect.removeAttribute("required");
            addressSection.removeAttribute("required");
            // if (buktiTrasaksi) buktiTrasaksi.removeAttribute("required");
        } else {
            addressSection.style.display = "block";
            // paymentImage.src = paymentImg;
            paymentImageSection.classList.remove("hidden");

            provinceSelect.setAttribute("required", "true");
            citySelect.setAttribute("required", "true");
            if (buktiTrasaksi) buktiTrasaksi.setAttribute("required", "true");
        }
    });

    // uppercase 
    voucherInput.addEventListener("input", function () {
        voucherInput.value = voucherInput.value.toUpperCase();
    });

    // [NEW] Voucher check handler
    checkVoucherBtn.addEventListener("click", function () {
        const voucherName = voucherInput.value.trim();
        if (!voucherName) {
            voucherMessage.textContent =
                "Masukkan kode voucher terlebih dahulu";
            voucherMessage.className = "text-sm mt-1 text-error";
            return;
        }

        fetch("/check-voucher", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify({ voucher_name: voucherName.toUpperCase() }), // Convert ke uppercase sebelum dikirim
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    voucherMessage.textContent = data.message;
                    voucherMessage.className = "text-sm mt-1 text-success";
                    currentVoucherDiscount = data.voucher.discount_voucher;
                    voucherDiscountSpan.textContent =
                        currentVoucherDiscount.toLocaleString("id-ID");
                    voucherDiscountSection.classList.remove("hidden");
                    updateTotalPrice();
                } else {
                    voucherMessage.textContent = data.message;
                    voucherMessage.className = "text-sm mt-1 text-error";
                    currentVoucherDiscount = 0;
                    voucherDiscountSection.classList.add("hidden");
                    updateTotalPrice();
                }
            });
    });

    // [NEW] Total price update function
    function updateTotalPrice() {
        const subtotal = parseInt(window.orderSubtotal) || 0;
        const shippingCost = parseInt(shippingPrice.textContent) || 0;
        const total = subtotal + shippingCost - currentVoucherDiscount;
        totalPrice.textContent = total.toLocaleString("id-ID");
    }

    // checkoutButton.addEventListener("click", function (event) {
    //     event.preventDefault();
    //         fetch("/get-midtrans-token", {
    //             method: "POST",
    //             headers: {
    //                 "Content-Type": "application/json",
    //                 "X-CSRF-TOKEN": document.querySelector(
    //                 'meta[name="csrf-token"]'
    //             ).content,
    //             },
    //             body: JSON.stringify({
    //                 total: window.orderSubtotal
    //             })
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.token) {
    //                 window.snap.pay(data.token, {
    //                     onSuccess: function(result) {
    //                         alert("Pembayaran berhasil!");
    //                         // window.location.href = "/checkout/success";
    //                     },
    //                     onPending: function(result) {
    //                         alert("Menunggu pembayaran!");
    //                     },
    //                     onError: function(result) {
    //                         alert("Pembayaran gagal!");
    //                     },
    //                     onClose: function() {
    //                         alert("Anda menutup pop-up pembayaran!");
    //                     }
    //                 });
    //             }
    //         })
    //         .catch(error => console.log("Error:", error));

    // });
});
