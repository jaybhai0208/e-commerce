<?php
require_once __DIR__ . '/includes/config.php';
$cartUser=current_user();
[$items, $subtotal] = cart_totals($cartUser ? (int)$cartUser['id'] : null);

$totalProducts = count($items);
$totalQuantity = 0;

foreach ($items as $item) {
    $totalQuantity += (int)($item['quantity'] ?? 0);
}

$shipping = 0;
$discount = 0;
$grandTotal = max(0, (float)$subtotal - $discount + $shipping);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Khaatu Foods</title>

    <?php include 'assets.php'; ?>

    <style>
        .khaatu-cart-page {
            padding-bottom: 80px
        }

        .khaatu-cart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px
        }

        .khaatu-cart-header-left {
            display: flex;
            align-items: center;
            gap: 12px
        }

        .khaatu-cart-header-left h4 {
            margin: 0
        }

        .khaatu-cart-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 28px;
            padding: 0 10px;
            border-radius: 20px;
            background: #f5f5f5;
            font-size: 13px;
            font-weight: 600
        }

        .khaatu-continue-shopping {
            white-space: nowrap
        }

        .khaatu-cart-box {
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 0px 15px gray;
        }

        .khaatu-cart-table {
            width: 100%;
            border-collapse: collapse
        }

        .khaatu-cart-table thead th {
            padding: 18px 20px;
            background: #fafafa;
            border-bottom: 1px solid #e9e9e9;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .3px;
            white-space: nowrap
        }

        .khaatu-cart-table tbody td {
            padding: 20px;
            border-bottom: 1px solid #eee;
            vertical-align: middle
        }

        .khaatu-cart-table tbody tr:last-child td {
            border-bottom: 0
        }

        .khaatu-cart-product {
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 280px
        }

        .khaatu-cart-product-image {
            width: 90px;
            height: 110px;
            flex: 0 0 90px;
            border-radius: 10px;
            overflow: hidden;
            background: #f7f7f7
        }

        .khaatu-cart-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block
        }

        .khaatu-cart-product-info {
            min-width: 0
        }

        .khaatu-cart-product-name {
            display: block;
            font-size: 16px;
            line-height: 1.45;
            font-weight: 600;
            margin-bottom: 7px
        }

        .khaatu-cart-product-id {
            font-size: 12px;
            color: #888
        }

        .khaatu-cart-price {
            font-size: 15px;
            font-weight: 600;
            white-space: nowrap
        }

        .khaatu-quantity-wrap {
            display: inline-flex;
            align-items: center;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            overflow: hidden;
            background: #fff
        }

        .khaatu-quantity-btn {
            width: 38px;
            height: 38px;
            padding: 0;
            border: 0;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
            line-height: 1;
            cursor: pointer;
            transition: .2s
        }

        .khaatu-quantity-btn:hover {
            background: #f5f5f5
        }

        .khaatu-quantity-btn:disabled {
            opacity: .5;
            cursor: not-allowed
        }

        .khaatu-quantity-input {
            width: 45px;
            height: 38px;
            border: 0;
            border-left: 1px solid #e5e5e5;
            border-right: 1px solid #e5e5e5;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            outline: none;
            background: #fff
        }

        .khaatu-quantity-input::-webkit-inner-spin-button,
        .khaatu-quantity-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0
        }

        .khaatu-quantity-input {
            -moz-appearance: textfield
        }

        .khaatu-update-text {
            display: none;
            margin-top: 7px;
            font-size: 11px;
            color: #888
        }

        .khaatu-line-total {
            font-size: 16px;
            font-weight: 700;
            white-space: nowrap
        }

        .khaatu-remove-btn {
            border: 0;
            background: transparent;
            color: #777;
            font-size: 13px;
            padding: 5px;
            cursor: pointer;
            transition: .2s
        }

        .khaatu-remove-btn:hover {
            color: #d32f2f
        }

        .khaatu-remove-btn:disabled {
            opacity: .5;
            cursor: not-allowed
        }

        .khaatu-summary {
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 14px;
            padding: 25px
        }

        .khaatu-summary-title {
            margin: 0 0 22px;
            font-size: 20px;
            font-weight: 700
        }

        .khaatu-summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 11px 0
        }

        .khaatu-summary-row p {
            margin: 0;
            color: #555
        }

        .khaatu-summary-value {
            font-weight: 600;
            white-space: nowrap
        }

        .khaatu-summary-divider {
            height: 1px;
            background: #eee;
            margin: 8px 0
        }

        .khaatu-summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 18px 0;
            font-size: 20px;
            font-weight: 700
        }

        .khaatu-summary-total span:last-child {
            white-space: nowrap
        }

        .khaatu-shipping-box {
            margin-top: 8px;
            padding: 14px;
            border-radius: 8px;
            background: #fafafa
        }

        .khaatu-shipping-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px
        }

        .khaatu-shipping-left {
            display: flex;
            align-items: center;
            gap: 9px
        }

        .khaatu-shipping-left input {
            width: 16px;
            height: 16px
        }

        .khaatu-shipping-price {
            font-weight: 600
        }

        .khaatu-terms {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin: 20px 0;
            font-size: 13px;
            line-height: 1.5
        }

        .khaatu-terms input {
            width: 16px;
            height: 16px;
            margin-top: 2px;
            flex: 0 0 16px
        }

        .khaatu-checkout-btn {
            width: 100%;
            min-height: 52px;
            border-radius: 8px;
            border: 0;
            cursor: pointer
        }

        .khaatu-continue-btn {
            display: block;
            text-align: center;
            margin-top: 16px
        }

        .khaatu-empty-cart {
            text-align: center;
            padding: 70px 25px
        }

        .khaatu-empty-cart-icon {
            width: 75px;
            height: 75px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #f7f7f7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px
        }

        .khaatu-empty-cart h3 {
            margin-bottom: 10px
        }

        .khaatu-empty-cart p {
            margin-bottom: 25px;
            color: #777
        }

        .khaatu-cart-row-loading {
            opacity: .65;
        }

        .khaatu-cart-row-loading .khaatu-quantity-btn {
            pointer-events: none;
        }

        .khaatu-cart-row-loading .khaatu-remove-btn {
            pointer-events: none;
        }

        @media(max-width:991px) {
            .khaatu-summary {
                margin-top: 25px
            }
        }

        @media(max-width:767px) {

            .khaatu-cart-page {
                padding-bottom: 50px
            }

            .khaatu-cart-header {
                align-items: flex-start;
                flex-direction: column
            }

            .khaatu-cart-table thead {
                display: none
            }

            .khaatu-cart-table,
            .khaatu-cart-table tbody,
            .khaatu-cart-table tr,
            .khaatu-cart-table td {
                display: block;
                width: 100%
            }

            .khaatu-cart-table tbody tr {
                position: relative;
                padding: 18px;
                border-bottom: 1px solid #eee
            }

            .khaatu-cart-table tbody td {
                padding: 7px 0;
                border: 0
            }

            .khaatu-cart-table tbody td.cart_product {
                padding-right: 35px
            }

            .khaatu-cart-table tbody td.cart_price::before {
                content: "Price";
                display: inline-block;
                width: 90px;
                color: #777;
                font-weight: 400
            }

            .khaatu-cart-table tbody td.cart_quantity::before {
                content: "Quantity";
                display: inline-block;
                width: 90px;
                color: #777;
                font-weight: 400;
                vertical-align: middle
            }

            .khaatu-cart-table tbody td.cart_total::before {
                content: "Total";
                display: inline-block;
                width: 90px;
                color: #777;
                font-weight: 400
            }

            .khaatu-cart-table tbody td.cart_remove {
                position: absolute;
                top: 15px;
                right: 12px
            }

            .khaatu-cart-product {
                min-width: 0
            }

            .khaatu-cart-product-image {
                width: 75px;
                height: 90px;
                flex-basis: 75px
            }

            .khaatu-cart-product-name {
                font-size: 14px
            }
        }

        @media(max-width:480px) {

            .khaatu-summary {
                padding: 18px
            }

            .khaatu-cart-box {
                border-radius: 10px
            }

            .khaatu-cart-product-image {
                width: 65px;
                height: 78px;
                flex-basis: 65px
            }
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="section-page-title">
        <div class="container">
            <div class="main-page-title">

                <div class="breadcrumbs">
                    <a href="index.php" class="text-caption-01 cl-text-3 link">
                        Home
                    </a>

                    <i class="icon icon-CaretRightThin cl-text-3"></i>

                    <p class="text-caption-01">
                        Shopping Cart
                    </p>
                </div>

                <h2 class="title">
                    Shopping Cart
                </h2>

            </div>
        </div>
    </div>

    <section class="section-shoping-cart khaatu-cart-page flat-spacing-2">

        <div class="container">

            <?php if (!$items): ?>

                <div class="khaatu-cart-box">

                    <div class="khaatu-empty-cart">

                        <div class="khaatu-empty-cart-icon">
                            <i class="icon icon-ShoppingCart"></i>
                        </div>

                        <h3>
                            Your Cart Is Empty
                        </h3>

                        <p>
                            You haven't added any products to your cart yet.
                        </p>

                        <a
                            href="index.php"
                            class="tf-btn type-xl btn-primary animate-btn">
                            Continue Shopping
                        </a>

                    </div>

                </div>

            <?php else: ?>

                <div class="khaatu-cart-header">

                    <div class="khaatu-cart-header-left">

                        <h4>
                            Your Cart
                        </h4>

                        <span
                            class="khaatu-cart-count"
                            id="cartItemCount">
                            <?= $totalQuantity ?>
                            <?= $totalQuantity == 1 ? 'Item' : 'Items' ?>
                        </span>

                    </div>

                    <a
                        href="products.php"
                        class="link khaatu-continue-shopping">
                        Continue Shopping
                    </a>

                </div>

                <div class="row">

                    <div class="col-lg-8">

                        <div class="khaatu-cart-box">

                            <div class="table-responsive">

                                <table class="khaatu-cart-table">

                                    <thead>

                                        <tr>
                                            <th class="text-start">
                                                Product
                                            </th>

                                            <th>
                                                Price
                                            </th>

                                            <th>
                                                Quantity
                                            </th>

                                            <th>
                                                Total
                                            </th>

                                            <th></th>
                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php foreach ($items as $i): ?>

                                            <?php
                                            $cartId = (int)($i['cart_id'] ?? 0);
                                            $productId = (int)($i['product_id'] ?? 0);
                                            $quantity = max(1, (int)($i['quantity'] ?? 1));
                                            $stock = (int)($i['stock'] ?? 0);
                                            $unitPrice = (float)($i['unit_price'] ?? 0);
                                            $lineTotal = $unitPrice * $quantity;

                                            $productImage = !empty($i['image'])
                                                ? 'admin/uploads-img/' . $i['image']
                                                : 'assets/pro/1.png';
                                            ?>

                                            <tr
                                                class="tf-cart_item each-prd"
                                                data-cart-id="<?= $cartId ?>"
                                                data-unit-price="<?= e($unitPrice) ?>">

                                                <td class="cart_product">

                                                    <div class="khaatu-cart-product">

                                                        <a
                                                            href="product.php?id=<?= $productId ?>"
                                                            class="khaatu-cart-product-image">

                                                            <img
                                                                loading="lazy"
                                                                src="<?= e($productImage) ?>"
                                                                alt="<?= e($i['name']) ?>">

                                                        </a>

                                                        <div class="khaatu-cart-product-info">

                                                            <a
                                                                href="product.php?id=<?= $productId ?>"
                                                                class="khaatu-cart-product-name link">
                                                                <?= e($i['name']) ?>
                                                            </a>

                                                            <span class="khaatu-cart-product-id">
                                                                Product #<?= $productId ?>
                                                            </span>

                                                        </div>

                                                    </div>

                                                </td>

                                                <td class="cart_price">

                                                    <span class="khaatu-cart-price">
                                                        <?= money($unitPrice) ?>
                                                    </span>

                                                </td>

                                                <td class="cart_quantity">

                                                    <form
                                                        action="cart-action.php"
                                                        method="post"
                                                        class="khaatu-update-cart-form">

                                                        <input
                                                            type="hidden"
                                                            name="csrf"
                                                            value="<?= e(csrf_token()) ?>">

                                                        <input
                                                            type="hidden"
                                                            name="action"
                                                            value="update">

                                                        <input
                                                            type="hidden"
                                                            name="cart_id"
                                                            value="<?= $cartId ?>">

                                                        <input type="hidden" name="product_id" value="<?= (int)$i['product_id'] ?>">

                                                        <div class="khaatu-quantity-wrap">

                                                            <button
                                                                type="button"
                                                                class="khaatu-quantity-btn khaatu-minus"
                                                                aria-label="Decrease quantity">
                                                                −
                                                            </button>

                                                            <input
                                                                class="khaatu-quantity-input"
                                                                type="number"
                                                                min="1"

                                                                <?php if ($stock > 0): ?>
                                                                max="<?= $stock ?>"
                                                                <?php endif; ?>

                                                                name="quantity"
                                                                value="<?= $quantity ?>"
                                                                autocomplete="off">

                                                            <button
                                                                type="button"
                                                                class="khaatu-quantity-btn khaatu-plus"
                                                                aria-label="Increase quantity">
                                                                +
                                                            </button>

                                                        </div>

                                                        <span class="khaatu-update-text">
                                                            Updating...
                                                        </span>

                                                    </form>

                                                </td>

                                                <td class="cart_total">

                                                    <span class="khaatu-line-total">
                                                        <?= money($lineTotal) ?>
                                                    </span>

                                                </td>


                                                <td class="cart_remove">

                                                    <form
                                                        action="cart-action.php"
                                                        method="post"
                                                        class="khaatu-remove-form">

                                                        <input
                                                            type="hidden"
                                                            name="csrf"
                                                            value="<?= e(csrf_token()) ?>">

                                                        <input
                                                            type="hidden"
                                                            name="action"
                                                            value="remove">

                                                        <input
                                                            type="hidden"
                                                            name="cart_id"
                                                            value="<?= $cartId ?>">

                                                        <input type="hidden" name="product_id" value="<?= (int)$i['product_id'] ?>">

                                                        <button
                                                            type="submit"
                                                            class="khaatu-remove-btn"
                                                            title="Remove product">
                                                            <i class="icon icon-Trash"></i>
                                                            Remove
                                                        </button>

                                                    </form>

                                                </td>



                                            </tr>

                                        <?php endforeach; ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4">

                        <div class="fl-sidebar-cart mt-lg-0 sticky-top">

                            <div class="khaatu-summary">

                                <h5 class="khaatu-summary-title">
                                    Order Summary
                                </h5>

                                <div class="khaatu-summary-row">

                                    <p>
                                        Products
                                    </p>

                                    <span
                                        class="khaatu-summary-value"
                                        id="cartProducts">
                                        <?= $totalProducts ?>
                                        <?= $totalProducts == 1 ? 'Product' : 'Products' ?>
                                    </span>

                                </div>

                                <div class="khaatu-summary-row">

                                    <p>
                                        Total Items
                                    </p>

                                    <span
                                        class="khaatu-summary-value"
                                        id="cartTotalItems">
                                        <?= $totalQuantity ?>
                                    </span>

                                </div>

                                <div class="khaatu-summary-divider"></div>

                                <div class="khaatu-summary-row">

                                    <p>
                                        Subtotal
                                    </p>

                                    <span
                                        class="khaatu-summary-value"
                                        id="cartSubtotal">
                                        <?= money($subtotal) ?>
                                    </span>

                                </div>

                                <div class="khaatu-summary-row">

                                    <p>
                                        Discount
                                    </p>

                                    <span class="khaatu-summary-value">
                                        <?= money($discount) ?>
                                    </span>

                                </div>

                                <div class="khaatu-summary-row">

                                    <p>
                                        Shipping
                                    </p>

                                    <span class="khaatu-summary-value">
                                        <?= $shipping > 0 ? money($shipping) : 'Free' ?>
                                    </span>

                                </div>

                                <div class="khaatu-shipping-box">

                                    <div class="khaatu-shipping-option">

                                        <div class="khaatu-shipping-left">

                                            <input
                                                type="radio"
                                                checked
                                                disabled>

                                            <span>
                                                Free Shipping
                                            </span>

                                        </div>

                                        <span class="khaatu-shipping-price">
                                            ₹0.00
                                        </span>

                                    </div>

                                </div>

                                <div class="khaatu-summary-divider"></div>

                                <div class="khaatu-summary-total">

                                    <span>
                                        Total
                                    </span>

                                    <span id="cartGrandTotal">
                                        <?= money($grandTotal) ?>
                                    </span>

                                </div>

                                <div class="khaatu-terms">

                                    <input
                                        type="checkbox"
                                        id="checkOutAgree">

                                    <label for="checkOutAgree">

                                        I agree with the

                                        <a
                                            href="#"
                                            class="fw-medium text-decoration-underline link">
                                            terms and conditions
                                        </a>

                                    </label>

                                </div>

                                <a
                                    href="checkout.php"
                                    id="checkout-btn"
                                    class="action-checkout tf-btn w-100 animate-btn khaatu-checkout-btn">

                                    <span class="fw-semibold">
                                        Process To Checkout
                                    </span>

                                </a>

                                <a
                                    href="index.php"
                                    class="link-underline link khaatu-continue-btn">

                                    <span class="fw-semibold">
                                        Or Continue Shopping
                                    </span>

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </section>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            function formatMoney(amount) {

                amount = Number(amount) || 0;

                return "₹" + amount.toLocaleString("en-IN", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }


            function refreshCartTotals() {

                const rows = document.querySelectorAll(
                    ".khaatu-cart-table tbody tr.each-prd"
                );

                let totalProducts = 0;
                let totalQuantity = 0;
                let subtotal = 0;


                rows.forEach(function(row) {

                    const input = row.querySelector(
                        ".khaatu-quantity-input"
                    );

                    if (!input) {
                        return;
                    }


                    let quantity = parseInt(
                        input.value,
                        10
                    );


                    if (!Number.isFinite(quantity) || quantity < 1) {
                        quantity = 1;
                        input.value = 1;
                    }


                    const unitPrice = parseFloat(
                        row.dataset.unitPrice
                    ) || 0;


                    const lineTotal = unitPrice * quantity;


                    totalProducts++;
                    totalQuantity += quantity;
                    subtotal += lineTotal;


                    const lineTotalElement = row.querySelector(
                        ".khaatu-line-total"
                    );


                    if (lineTotalElement) {

                        lineTotalElement.textContent =
                            formatMoney(lineTotal);

                    }

                });


                const shipping = 0;
                const discount = 0;

                const grandTotal = Math.max(
                    0,
                    subtotal - discount + shipping
                );


                const cartItemCount = document.getElementById(
                    "cartItemCount"
                );


                if (cartItemCount) {

                    cartItemCount.textContent =
                        totalQuantity +
                        " " +
                        (totalQuantity === 1 ?
                            "Item" :
                            "Items"
                        );

                }


                const cartProducts = document.getElementById(
                    "cartProducts"
                );


                if (cartProducts) {

                    cartProducts.textContent =
                        totalProducts +
                        " " +
                        (totalProducts === 1 ?
                            "Product" :
                            "Products"
                        );

                }


                const cartTotalItems = document.getElementById(
                    "cartTotalItems"
                );


                if (cartTotalItems) {

                    cartTotalItems.textContent =
                        totalQuantity;

                }


                const cartSubtotal = document.getElementById(
                    "cartSubtotal"
                );


                if (cartSubtotal) {

                    cartSubtotal.textContent =
                        formatMoney(subtotal);

                }


                const cartGrandTotal = document.getElementById(
                    "cartGrandTotal"
                );


                if (cartGrandTotal) {

                    cartGrandTotal.textContent =
                        formatMoney(grandTotal);

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Quantity AJAX
            |--------------------------------------------------------------------------
            */

            document.querySelectorAll(
                ".khaatu-update-cart-form"
            ).forEach(function(form) {

                const row = form.closest("tr");

                const minusBtn = form.querySelector(
                    ".khaatu-minus"
                );

                const plusBtn = form.querySelector(
                    ".khaatu-plus"
                );

                const input = form.querySelector(
                    ".khaatu-quantity-input"
                );


                if (!input) {
                    return;
                }


                function getMin() {

                    const min = parseInt(
                        input.getAttribute("min"),
                        10
                    );

                    if (
                        Number.isFinite(min) &&
                        min >= 1
                    ) {
                        return min;
                    }

                    return 1;
                }


                function getMax() {

                    const max = parseInt(
                        input.getAttribute("max"),
                        10
                    );

                    if (
                        Number.isFinite(max) &&
                        max > 0
                    ) {
                        return max;
                    }

                    return null;
                }


                function setLoading(status) {

                    if (row) {

                        row.classList.toggle(
                            "khaatu-cart-row-loading",
                            status
                        );

                    }


                    if (minusBtn) {
                        minusBtn.disabled = status;
                    }


                    if (plusBtn) {
                        plusBtn.disabled = status;
                    }

                }


                async function updateQuantity() {

                    let quantity = parseInt(
                        input.value,
                        10
                    );


                    const min = getMin();
                    const max = getMax();


                    if (
                        !Number.isFinite(quantity) ||
                        quantity < min
                    ) {

                        quantity = min;

                    }


                    if (
                        max !== null &&
                        quantity > max
                    ) {

                        quantity = max;

                    }


                    input.value = quantity;


                    refreshCartTotals();


                    setLoading(true);


                    try {

                        const formData =
                            new FormData(form);


                        const response =
                            await fetch(
                                form.action, {
                                    method: "POST",
                                    body: formData,
                                    credentials: "same-origin",

                                    headers: {
                                        "X-Requested-With": "XMLHttpRequest"
                                    },

                                    redirect: "follow"
                                }
                            );


                        if (!response.ok) {

                            throw new Error(
                                "Quantity update failed"
                            );

                        }


                    } catch (error) {

                        console.error(
                            "Cart quantity update error:",
                            error
                        );

                    } finally {

                        setLoading(false);

                    }

                }


                if (minusBtn) {

                    minusBtn.addEventListener(
                        "click",
                        function(event) {

                            event.preventDefault();


                            if (minusBtn.disabled) {
                                return;
                            }


                            let quantity = parseInt(
                                input.value,
                                10
                            );


                            if (!Number.isFinite(quantity)) {
                                quantity = getMin();
                            }


                            quantity--;


                            if (quantity < getMin()) {
                                quantity = getMin();
                            }


                            input.value = quantity;


                            updateQuantity();

                        }
                    );

                }


                if (plusBtn) {

                    plusBtn.addEventListener(
                        "click",
                        function(event) {

                            event.preventDefault();


                            if (plusBtn.disabled) {
                                return;
                            }


                            let quantity = parseInt(
                                input.value,
                                10
                            );


                            if (!Number.isFinite(quantity)) {
                                quantity = getMin();
                            }


                            quantity++;


                            const max = getMax();


                            if (
                                max !== null &&
                                quantity > max
                            ) {

                                quantity = max;

                            }


                            input.value = quantity;


                            updateQuantity();

                        }
                    );

                }


                input.addEventListener(
                    "change",
                    function() {

                        updateQuantity();

                    }
                );


                input.addEventListener(
                    "keydown",
                    function(event) {

                        if (event.key === "Enter") {

                            event.preventDefault();

                            updateQuantity();

                        }

                    }
                );

            });


            /*
            |--------------------------------------------------------------------------
            | REMOVE PRODUCT - FIXED
            |--------------------------------------------------------------------------
            */

            /*
            |--------------------------------------------------------------------------
            | REMOVE PRODUCT AJAX - FIXED
            |--------------------------------------------------------------------------
            */

            document.querySelectorAll(".khaatu-remove-form").forEach(function(form) {

                const removeButton = form.querySelector(".khaatu-remove-btn");

                if (!removeButton) {
                    return;
                }


                removeButton.addEventListener("click", async function(event) {

                    /*
                    | Form ko normal submit nahi hone dena
                    */
                    event.preventDefault();
                    event.stopPropagation();
                    event.stopImmediatePropagation();


                    const row = form.closest("tr.each-prd");

                    if (!row) {
                        return;
                    }


                    /*
                    | Already removing hai to dobara request nahi
                    */
                    if (row.dataset.removing === "1") {
                        return;
                    }


                    row.dataset.removing = "1";


                    /*
                    | Loading UI
                    */
                    row.classList.add("khaatu-cart-row-loading");

                    removeButton.disabled = true;

                    removeButton.innerHTML =
                        '<i class="icon icon-Trash"></i> Removing...';


                    try {

                        /*
                        | Form data
                        */
                        const formData = new FormData(form);


                        /*
                        | Make sure remove action ja raha hai
                        */
                        formData.set("action", "remove");


                        /*
                        | cart_id check
                        */
                        const cartIdInput = form.querySelector(
                            'input[name="cart_id"]'
                        );


                        if (!cartIdInput || !cartIdInput.value) {

                            throw new Error(
                                "Cart ID missing"
                            );

                        }


                        /*
                        | AJAX request
                        */
                        const response = await fetch(
                            "cart-action.php", {
                                method: "POST",

                                body: formData,

                                credentials: "same-origin",

                                headers: {
                                    "X-Requested-With": "XMLHttpRequest"
                                },

                                redirect: "follow",

                                cache: "no-store"
                            }
                        );


                        /*
                        | HTTP request fail
                        */
                        if (!response.ok) {

                            throw new Error(
                                "Remove request failed: " +
                                response.status
                            );

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | IMPORTANT
                        |--------------------------------------------------------------------------
                        | Server request complete hone ke baad
                        | product ko page se remove karo.
                        */

                        row.remove();


                        /*
                        | Cart totals update
                        */
                        if (typeof refreshCartTotals === "function") {

                            refreshCartTotals();

                        }


                        /*
                        | Remaining products check
                        */
                        const remainingRows =
                            document.querySelectorAll(
                                ".khaatu-cart-table tbody tr.each-prd"
                            );


                        /*
                        | Cart completely empty
                        */
                        if (remainingRows.length === 0) {

                            if (typeof showEmptyCart === "function") {

                                showEmptyCart();

                            }

                        }


                    } catch (error) {

                        console.error(
                            "Remove product error:",
                            error
                        );


                        /*
                        | Error par product ko wapas rakho
                        | aur koi alert/popup nahi.
                        */

                        row.dataset.removing = "0";

                        row.classList.remove(
                            "khaatu-cart-row-loading"
                        );


                        removeButton.disabled = false;

                        removeButton.innerHTML =
                            '<i class="icon icon-Trash"></i> Remove';

                    }

                }, true);

            });


            /*
            |--------------------------------------------------------------------------
            | EMPTY CART UI
            |--------------------------------------------------------------------------
            */

            function showEmptyCart() {

                const container =
                    document.querySelector(
                        ".section-shoping-cart .container"
                    );


                if (!container) {
                    return;
                }


                /*
                | Existing cart content ko completely replace
                | kar rahe hain.
                |
                | Isse removed .row ke andar cart-box
                | dhoondhne wali problem nahi hogi.
                */

                container.innerHTML = `

            <div class="khaatu-cart-box">

                <div class="khaatu-empty-cart">

                    <div class="khaatu-empty-cart-icon">
                        <i class="icon icon-ShoppingCart"></i>
                    </div>

                    <h3>
                        Your Cart Is Empty
                    </h3>

                    <p>
                        You haven't added any products to your cart yet.
                    </p>

                    <a
                        href="products.php"
                        class="tf-btn type-xl btn-primary animate-btn"
                    >
                        Continue Shopping
                    </a>

                </div>

            </div>

        `;

            }


            /*
            |--------------------------------------------------------------------------
            | Checkout Terms
            |--------------------------------------------------------------------------
            */

            const checkoutBtn =
                document.getElementById(
                    "checkout-btn"
                );


            const agreeCheckbox =
                document.getElementById(
                    "checkOutAgree"
                );


            if (
                checkoutBtn &&
                agreeCheckbox
            ) {

                checkoutBtn.addEventListener(
                    "click",
                    function(event) {

                        if (
                            !agreeCheckbox.checked
                        ) {

                            event.preventDefault();

                            agreeCheckbox.focus();

                            agreeCheckbox.style.outline =
                                "2px solid #d32f2f";


                            setTimeout(
                                function() {

                                    agreeCheckbox.style.outline =
                                        "";

                                },
                                1500
                            );

                        }

                    }
                );

            }

        });
    </script>

    <?php include 'footer.php'; ?>

</body>

</html>