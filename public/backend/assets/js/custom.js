document.addEventListener("DOMContentLoaded", function () {
    let productSearchInput = document.getElementById("product_search");
    let warehouseDropdown = document.getElementById("warehouse_id");
    let product_list = document.getElementById("product_list");
    let warehouseError = document.getElementById("warehouse_error");
    let orderItemsTableBody = document.querySelector(
        "table.table-bordered tbody",
    );

    let inputDiscount = document.getElementById("inputDiscount");
    let inputShipping = document.getElementById("inputShipping");
    let displayDiscount = document.getElementById("displayDiscount");
    let shippingDisplay = document.getElementById("shippingDisplay");
    let grandTotalDisplay = document.getElementById("grandTotal");
    let grandTotalHidden = document.querySelector('input[name="grand_total"]');

    let form = document.querySelector("form");

    // ============================
    // SEARCH PRODUCT
    // ============================
    productSearchInput.addEventListener("keyup", function () {
        let query = this.value.trim();
        let warehouse_id = warehouseDropdown.value;

        if (!warehouse_id) {
            warehouseError.classList.remove("d-none");
            product_list.innerHTML = "";
            return;
        } else {
            warehouseError.classList.add("d-none");
        }

        if (query.length > 1) {
            fetchProduct(query, warehouse_id);
        } else {
            product_list.innerHTML = "";
        }
    });

    // disable search kapag walang warehouse pinili
    warehouseDropdown.addEventListener("change", function () {
        if (this.value) {
            warehouseError.classList.add("d-none");

            // kung may laman na yung search box, mag-search ulit gamit ang bagong warehouse
            let query = productSearchInput.value.trim();
            if (query.length > 1) {
                fetchProduct(query, this.value);
            } else {
                product_list.innerHTML = "";
            }
        } else {
            product_list.innerHTML = "";
        }
    });

    function fetchProduct(query, warehouse_id) {
        fetch(
            productSearchUrl +
                "?query=" +
                encodeURIComponent(query) +
                "&warehouse_id=" +
                encodeURIComponent(warehouse_id),
        )
            .then((response) => response.json())
            .then((data) => {
                product_list.innerHTML = "";

                if (data.length > 0) {
                    data.forEach((product) => {
                        let item = document.createElement("a");
                        item.href = "#";
                        item.className =
                            "list-group-item list-group-item-action product-item";
                        item.dataset.id = product.id;
                        item.dataset.code = product.code;
                        item.dataset.name = product.product_name;
                        item.dataset.cost = product.price;
                        item.dataset.stock = product.product_quantity;
                        item.dataset.discount = product.discount; // ⬅️ FIX: kunin ang discount mula sa response
                        item.innerHTML = `<span class="mdi mdi-text-search"></span> ${product.code} - ${product.product_name}`;

                        product_list.appendChild(item);
                    });

                    document.querySelectorAll(".product-item").forEach((item) =>
                        item.addEventListener("click", function (e) {
                            e.preventDefault();
                            addProductToTable(this);
                        }),
                    );
                } else {
                    product_list.innerHTML =
                        '<p class="text-muted p-2">No Product Found</p>';
                }
            })
            .catch((err) => {
                console.error(err);
                product_list.innerHTML =
                    '<p class="text-danger p-2">Error searching product.</p>';
            });
    }

    // ============================
    // ADD PRODUCT TO ORDER TABLE
    // ============================
    function addProductToTable(el) {
        let id = el.dataset.id;

        // if product already added, just increase qty instead of duplicating
        let existingRow = orderItemsTableBody.querySelector(
            `tr[data-id="${id}"]`,
        );
        if (existingRow) {
            let qtyInput = existingRow.querySelector(".qty-input");
            qtyInput.value = parseInt(qtyInput.value) + 1;
            updateRowSubtotal(existingRow);
            calculateTotals();
            product_list.innerHTML = "";
            productSearchInput.value = "";
            return;
        }

        let name = el.dataset.name;
        let code = el.dataset.code;
        let cost = parseFloat(el.dataset.cost) || 0;
        let stock = parseFloat(el.dataset.stock) || 0;
        let discount = parseFloat(el.dataset.discount) || 0; // ⬅️ FIX: gamitin ang discount mula sa dataset

        let row = document.createElement("tr");
        row.setAttribute("data-id", id);

        row.innerHTML = `
    <td>
        ${code} - ${name}
        <input type="hidden" name="product_id[]" value="${id}">
    </td>

    <td>
        <input
            type="number"
            class="form-control cost-input"
            name="unit_cost[]"
            value="${cost.toFixed(2)}"
            min="0"
            step="0.01">
    </td>

    <td class="stock-cell">
        ${stock}
    </td>

    <td>
        <div class="input-group input-group-sm" style="width:120px">

            <button type="button" class="btn btn-outline-secondary qty-minus">
                -
            </button>

            <input
                type="number"
                class="form-control text-center qty-input"
                name="quantity[]"
                value="1"
                min="1"
                readonly>

            <button type="button" class="btn btn-outline-secondary qty-plus">
                +
            </button>

        </div>
    </td>

    <td>
        <input
            type="number"
            class="form-control discount-input"
            name="item_discount[]"
            value="${discount.toFixed(2)}"
            min="0"
            step="0.01">
    </td>

    <td class="subtotal-cell">
        Php ${cost.toFixed(2)}
    </td>

    <td>
        <button type="button" class="btn btn-sm btn-danger remove-item">
            <i class="fas fa-trash"></i>
        </button>
    </td>
`;

        orderItemsTableBody.appendChild(row);

        // ⬅️ FIX: i-compute agad ang subtotal (kasama ang discount) bago i-total
        updateRowSubtotal(row);
        calculateTotals();

        product_list.innerHTML = "";
        productSearchInput.value = "";
    }

    // ============================
    // ROW EVENTS (qty / cost / discount change, remove row)
    // ============================
    orderItemsTableBody.addEventListener("input", function (e) {
        if (
            e.target.classList.contains("qty-input") ||
            e.target.classList.contains("cost-input") ||
            e.target.classList.contains("discount-input")
        ) {
            let row = e.target.closest("tr");

            if (e.target.classList.contains("qty-input")) {
                if (parseFloat(e.target.value) < 1 || isNaN(e.target.value)) {
                    e.target.value = 1;
                }
            }

            updateRowSubtotal(row);
            calculateTotals();
        }
    });

    orderItemsTableBody.addEventListener("click", function (e) {
        // ===========================
        // REMOVE ITEM
        // ===========================
        let removeBtn = e.target.closest(".remove-item");
        if (removeBtn) {
            removeBtn.closest("tr").remove();
            calculateTotals();
            return;
        }

        // ===========================
        // PLUS BUTTON
        // ===========================
        let plusBtn = e.target.closest(".qty-plus");
        if (plusBtn) {
            let row = plusBtn.closest("tr");
            let qtyInput = row.querySelector(".qty-input");
            let stock =
                parseFloat(row.querySelector(".stock-cell").textContent) || 0;

            let qty = parseInt(qtyInput.value) || 1;

            if (qty < stock) {
                qtyInput.value = qty + 1;
                updateRowSubtotal(row);
                calculateTotals();
            }

            return;
        }

        // ===========================
        // MINUS BUTTON
        // ===========================
        let minusBtn = e.target.closest(".qty-minus");
        if (minusBtn) {
            let row = minusBtn.closest("tr");
            let qtyInput = row.querySelector(".qty-input");

            let qty = parseInt(qtyInput.value) || 1;

            if (qty > 1) {
                qtyInput.value = qty - 1;
                updateRowSubtotal(row);
                calculateTotals();
            }

            return;
        }
    });

    function updateRowSubtotal(row) {
        let cost = parseFloat(row.querySelector(".cost-input").value) || 0;
        let qty = parseFloat(row.querySelector(".qty-input").value) || 0;
        let discount =
            parseFloat(row.querySelector(".discount-input").value) || 0;

        let subtotal = cost * qty - discount;
        if (subtotal < 0) subtotal = 0;

        row.querySelector(".subtotal-cell").textContent =
            "Php " + subtotal.toFixed(2);
        row.dataset.subtotal = subtotal;
    }

    // ============================
    // CALCULATE GRAND TOTAL
    // ============================
    function calculateTotals() {
        let rows = orderItemsTableBody.querySelectorAll("tr");
        let itemsTotal = 0;
        let itemDiscountsTotal = 0; // ⬅️ bagong variable, total ng lahat ng item discounts

        rows.forEach((row) => {
            itemsTotal += parseFloat(row.dataset.subtotal || 0);

            // ⬅️ kunin ang discount ng kada row at idagdag sa total
            let rowDiscountInput = row.querySelector(".discount-input");
            itemDiscountsTotal += parseFloat(rowDiscountInput.value) || 0;
        });

        let orderDiscount = parseFloat(inputDiscount.value) || 0;
        let shipping = parseFloat(inputShipping.value) || 0;

        // ⬅️ pinagsama ang item discounts + order-level discount
        let combinedDiscount = itemDiscountsTotal + orderDiscount;

        let grandTotal = itemsTotal - orderDiscount + shipping;
        if (grandTotal < 0) grandTotal = 0;

        displayDiscount.textContent = "Php " + combinedDiscount.toFixed(2); // ⬅️ ipinapakita na ang total
        shippingDisplay.textContent = "Php " + shipping.toFixed(2);
        grandTotalDisplay.textContent = "Php " + grandTotal.toFixed(2);
        grandTotalHidden.value = grandTotal.toFixed(2);
    }

    inputDiscount.addEventListener("input", calculateTotals);
    inputShipping.addEventListener("input", calculateTotals);

    // ============================
    // VALIDATE BEFORE SUBMIT
    // ============================
    form.addEventListener("submit", function (e) {
        if (orderItemsTableBody.querySelectorAll("tr").length === 0) {
            e.preventDefault();
            alert("Please add at least one product to Order Items.");
            return false;
        }

        if (!warehouseDropdown.value) {
            e.preventDefault();
            alert("Please select a warehouse.");
            return false;
        }

        calculateTotals();
    });
});
