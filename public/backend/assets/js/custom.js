document.addEventListener("DOMContentLoaded", function () {
    let productSearchInput = document.getElementById("product_search");
    let warehouseDropdown = document.getElementById("warehouse_id");
    let product_list = document.getElementById("product_list");
    let warehouseError = document.getElementById("warehouse_error");
    let orderItemsTableBody = document.querySelector("#purchaseTable tbody");

    let inputDiscount = document.getElementById("inputDiscount");
    let inputShipping = document.getElementById("inputShipping");
    let displayDiscount = document.getElementById("displayDiscount");
    let shippingDisplay = document.getElementById("shippingDisplay");
    let grandTotalDisplay = document.getElementById("grandTotal");
    let grandTotalHidden = document.querySelector('input[name="grand_total"]');

    let form = document.querySelector("form");
    let selectedWarehouse = "";
    let warehouseLocked = false;

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

    warehouseDropdown.addEventListener("change", function () {
        if (warehouseLocked && this.value !== selectedWarehouse) {
            this.value = selectedWarehouse;

            Swal.fire({
                icon: "warning",
                title: "Warehouse Locked",
                text: "You cannot change the warehouse after adding products. Please remove all products first if you want to select another warehouse.",
                confirmButtonText: "OK",
            });

            return;
        }

        selectedWarehouse = this.value;

        warehouseError.classList.add("d-none");
        product_list.innerHTML = "";
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
                        item.dataset.discount = product.discount;
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

    function addProductToTable(el) {
        let id = el.dataset.id;

        let existingRow = orderItemsTableBody.querySelector(
            `tr[data-id="${id}"]`,
        );

        if (existingRow) {
            let qtyInput = existingRow.querySelector(".qty-input");
            let stock =
                parseFloat(
                    existingRow.querySelector(".stock-cell").textContent,
                ) || 0;
            let newQty = parseInt(qtyInput.value) + 1;

            if (newQty > stock) {
                Swal.fire({
                    icon: "warning",
                    title: "Insufficient Stock",
                    text: `Only ${stock} unit(s) available for this product.`,
                });
            } else {
                qtyInput.value = newQty;
                updateRowSubtotal(existingRow);
                calculateTotals();
            }

            product_list.innerHTML = "";
            productSearchInput.value = "";

            return;
        }

        let name = el.dataset.name;
        let code = el.dataset.code;
        let cost = parseFloat(el.dataset.cost) || 0;
        let stock = parseFloat(el.dataset.stock) || 0;
        let discount = parseFloat(el.dataset.discount) || 0;

        if (stock < 1) {
            Swal.fire({
                icon: "warning",
                title: "Out of Stock",
                text: "This product has no available stock in the selected warehouse.",
            });

            product_list.innerHTML = "";
            productSearchInput.value = "";
            return;
        }

        let row = document.createElement("tr");

        row.setAttribute("data-id", id);

        row.innerHTML = `
        <td>
            ${code} - ${name}

            <input
                type="hidden"
                name="product_id[]"
                value="${id}">
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

                <button
                    type="button"
                    class="btn btn-outline-secondary qty-minus">
                    -
                </button>

                <input
                    type="number"
                    class="form-control text-center qty-input"
                    name="quantity[]"
                    value="1"
                    min="1"
                    max="${stock}"
                    readonly>

                <button
                    type="button"
                    class="btn btn-outline-secondary qty-plus">
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
            <button
                type="button"
                class="btn btn-sm btn-danger remove-item">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

        if (!warehouseLocked) {
            selectedWarehouse = warehouseDropdown.value;
            warehouseLocked = true;
        }

        orderItemsTableBody.appendChild(row);

        updateRowSubtotal(row);
        calculateTotals();

        product_list.innerHTML = "";
        productSearchInput.value = "";
    }

    orderItemsTableBody.addEventListener("input", function (e) {
        if (
            e.target.classList.contains("qty-input") ||
            e.target.classList.contains("cost-input") ||
            e.target.classList.contains("discount-input")
        ) {
            let row = e.target.closest("tr");

            if (e.target.classList.contains("qty-input")) {
                let stock =
                    parseFloat(row.querySelector(".stock-cell").textContent) ||
                    0;
                let val = parseInt(e.target.value);

                if (isNaN(val) || val < 1) {
                    e.target.value = 1;
                } else if (val > stock) {
                    e.target.value = stock;
                    Swal.fire({
                        icon: "warning",
                        title: "Insufficient Stock",
                        text: `Only ${stock} unit(s) available.`,
                    });
                }
            }

            updateRowSubtotal(row);
            calculateTotals();
        }
    });

    orderItemsTableBody.addEventListener("click", function (e) {
        let removeBtn = e.target.closest(".remove-item");
        if (removeBtn) {
            removeBtn.closest("tr").remove();

            calculateTotals();

            if (orderItemsTableBody.querySelectorAll("tr").length === 0) {
                selectedWarehouse = "";
                warehouseLocked = false;
            }

            return;
        }

        let plusBtn = e.target.closest(".qty-plus");
        if (plusBtn) {
            let row = plusBtn.closest("tr");
            let qtyInput = row.querySelector(".qty-input");
            let stock =
                parseFloat(row.querySelector(".stock-cell").textContent) || 0;

            let qty = parseInt(qtyInput.value) || 1;

            if (qty + 1 > stock) {
                Swal.fire({
                    icon: "warning",
                    title: "Insufficient Stock",
                    text: `Only ${stock} unit(s) available.`,
                });
            } else {
                qtyInput.value = qty + 1;
                updateRowSubtotal(row);
                calculateTotals();
            }

            return;
        }

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

    function calculateTotals() {
        let rows = orderItemsTableBody.querySelectorAll("tr");
        let itemsTotal = 0;
        let itemDiscountsTotal = 0;

        rows.forEach((row) => {
            itemsTotal += parseFloat(row.dataset.subtotal || 0);

            let rowDiscountInput = row.querySelector(".discount-input");
            itemDiscountsTotal += parseFloat(rowDiscountInput.value) || 0;
        });

        let orderDiscount = parseFloat(inputDiscount.value) || 0;
        let shipping = parseFloat(inputShipping.value) || 0;

        let combinedDiscount = itemDiscountsTotal + orderDiscount;

        let grandTotal = itemsTotal - orderDiscount + shipping;
        if (grandTotal < 0) grandTotal = 0;

        displayDiscount.textContent = "Php " + combinedDiscount.toFixed(2);
        shippingDisplay.textContent = "Php " + shipping.toFixed(2);
        grandTotalDisplay.textContent = "Php " + grandTotal.toFixed(2);
        grandTotalHidden.value = grandTotal.toFixed(2);
    }

    inputDiscount.addEventListener("input", calculateTotals);
    inputShipping.addEventListener("input", calculateTotals);

    orderItemsTableBody.querySelectorAll("tr").forEach((row) => {
        updateRowSubtotal(row);
    });

    calculateTotals();

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

        let toWarehouse = document.getElementById("to_warehouse_id");
        if (!toWarehouse.value) {
            e.preventDefault();
            alert("Please select a destination warehouse.");
            return false;
        }

        if (toWarehouse.value === warehouseDropdown.value) {
            e.preventDefault();
            alert("From and To warehouse cannot be the same.");
            return false;
        }

        calculateTotals();
    });
});
