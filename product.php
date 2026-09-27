<?php
require_once __DIR__ . '/includes/config.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    http_response_code(404);
    exit('Product not found');
}

$sql = "
    SELECT
        p.*,
        c.name AS category_name,
        d.short_description AS detail_short_description,
        d.description AS detail_description,
        d.specifications
    FROM products p
    LEFT JOIN categories c ON c.id = p.category_id
    LEFT JOIN product_details d ON d.product_id = p.id
    WHERE p.id = ?
    AND p.status = 1
    LIMIT 1
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Database query failed.');
}

$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$p = $result->fetch_assoc();
$stmt->close();

if (!$p) {
    http_response_code(404);
    exit('Product not found');
}

$productName = $p['name'] ?? 'Product';

$categoryName = !empty($p['category_name'])
    ? $p['category_name']
    : 'Products';

$image = !empty($p['image'])
    ? 'admin/uploads-img/' . $p['image']
    : 'assets/pro/1.png';

$price = (float)(
    !empty($p['offerprice']) && $p['offerprice'] > 0
    ? $p['offerprice']
    : ($p['price'] ?? 0)
);

$oldPrice = (float)($p['price'] ?? 0);

$discount = 0;
if ($oldPrice > 0 && $price < $oldPrice) {
    $discount = round((1 - ($price / $oldPrice)) * 100, 2);
}


$shortDescriptionRaw = !empty($p['detail_short_description'])
    ? $p['detail_short_description']
    : ($p['small_description'] ?? '');

$descriptionRaw = !empty($p['detail_description'])
    ? $p['detail_description']
    : ($p['long_description'] ?? '');

$specificationsRaw = $p['specifications'] ?? '';

$shortDescription = trim(strip_tags((string)$shortDescriptionRaw));
$description = trim(strip_tags((string)$descriptionRaw));
$specifications = trim(strip_tags((string)$specificationsRaw));

$imageUrl = e($image);
$pageTitle = e($productName) . ' - Khaatu Foods';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <?php include 'assets.php'; ?>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="section-page-title-single flat-spacing-3">
        <div class="container">
            <div class="main-page-title">
                <div class="breadcrumbs">
                    <a href="index.php" class="text-caption-01 cl-text-3 link">Home</a>
                    <i class="icon icon-CaretRightThin cl-text-3"></i>
                    <a href="products.php" class="text-caption-01 cl-text-3 link">Products</a>
                    <i class="icon icon-CaretRightThin cl-text-3"></i>
                    <p class="text-caption-01"><?= e($productName) ?></p>
                </div>
            </div>
        </div>
    </div>

    <section class="section-product-single tf-main-product section-image-zoom">
        <div class="container">
            <div class="row">

                <div class="col-md-6">
                    <div class="tf-product-media-wrap sticky-top">
                        <div class="product-thumbs-slider style-row row_left">
                            <div class="flat-wrap-media-product">
                                <div dir="ltr" class="swiper tf-product-media-main" id="gallery-swiper-started" data-spacing="0">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide" data-color="green">
                                            <a href="<?= $imageUrl ?>" target="_blank" class="item" data-pswp-width="576" data-pswp-height="768">
                                                <img loading="lazy" width="576" height="768" class="tf-image-zoom" data-zoom="<?= $imageUrl ?>" src="<?= $imageUrl ?>" alt="<?= e($productName) ?>">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="tf-product-info-wrap position-relative mt-md-0">
                        <div class="tf-zoom-main sticky-top"></div>
                        <div class="tf-product-info-list other-image-zoom">

                            <div class="tf-product-info-heading">
                                <p class="product-infor-cate text-caption-01 mb-4"><?= e($categoryName) ?></p>

                                <h3 class="product-infor-name mb-12"><?= e($productName) ?></h3>

                                <div class="product-infor-meta mb-20">
                                    <div class="meta_rate">
                                        <div class="star-wrap normal d-flex align-items-center">
                                            <i class="icon icon-Star"></i>
                                            <i class="icon icon-Star"></i>
                                            <i class="icon icon-Star"></i>
                                            <i class="icon icon-Star"></i>
                                            <i class="icon icon-Star"></i>
                                        </div>
                                        <span class="text-caption-01 cl-text-2">Customer reviews</span>
                                    </div>
                                </div>

                                <div class="product-infor-price mb-12">
                                    <h4 class="price-on-sale" id="mainProductPrice"><?= money($price) ?></h4>

                                    <?php if ($oldPrice > $price): ?>
                                        <div class="br-line type-vertical"></div>
                                        <p class="cl-text-3 text-decoration-line-through"><?= money($oldPrice) ?></p>
                                        <span class="badge-sale text-white fw-semibold text-caption-02">-<?= e($discount) ?>%</span>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($shortDescription)): ?>

                                    <div class="product-short-description">

                                        <h4 class="product-description-heading">
                                            Product Description
                                        </h4>

                                        <div class="product-description-content">

                                            <?php

                                            $descriptionHtml = html_entity_decode(
                                                $shortDescription,
                                                ENT_QUOTES | ENT_HTML5,
                                                'UTF-8'
                                            );

                                            $descriptionHtml = stripslashes(
                                                $descriptionHtml
                                            );

                                            echo $descriptionHtml;

                                            ?>

                                        </div>

                                    </div>

                                <?php endif; ?>


                            </div>

                            <div class="br-line"></div>

                            <div class="tf-product-variant">
                                <div class="tf-product-total-quantity">
                                    <p>Quantity:</p>
                                    <div class="group-action">

                                        <div class="wg-quantity">
                                            <button type="button" class="btn-quantity btn-decrease" aria-label="Decrease quantity">
                                                <i class="icon icon-minus"></i>
                                            </button>
                                            <input class="quantity-product" type="text" id="productQty" name="quantity" value="1" min="1" inputmode="numeric" autocomplete="off">
                                            <button type="button" class="btn-quantity btn-increase" aria-label="Increase quantity">
                                                <i class="icon icon-plus"></i>
                                            </button>
                                        </div>

                                        <form action="cart-action.php" method="post" class="w-100" id="addToCartForm">
                                            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                                            <input type="hidden" name="action" value="add">
                                            <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
                                            <input type="hidden" name="quantity" id="cartQty" value="1">

                                            <button type="submit" class="btn-action-price tf-btn type-xl animate-btn w-100">
                                                Add To Cart
                                            </button>
                                        </form>
                                    </div>

                                    <form action="checkout.php" method="get" class="w-100 mt-12" id="buyNowForm">
                                        <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
                                        <input type="hidden" name="quantity" id="buyNowQty" value="1">
                                        <button type="submit" class="tf-btn type-xl btn-primary animate-btn w-100">Buy It Now</button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="flat-spacing-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="tf-product-description">

                        <?php if (!empty($description)): ?>

                            <div class="mb-20">

                                <h4 class="mb-10">Product Specifications</h4>

                                <?php


                                $descriptionText = trim($description);

                                // Product Specifications ko remove karo
                                $descriptionText = preg_replace(
                                    '/Product\s*Specifications/i',
                                    '',
                                    $descriptionText
                                );


                                $specKeys = [
                                    'Product Name',
                                    'Net Quantity',
                                    'Product Type',
                                    'Taste',
                                    'Texture',
                                    'Source',
                                    'Pack Size',
                                    'Suitable For',
                                    'Storage',
                                    'Brand',
                                    'Country of Origin'
                                ];


                                foreach ($specKeys as $key) {

                                    $descriptionText = preg_replace(
                                        '/\s*(' . preg_quote($key, '/') . ')\s*:/i',
                                        "\n$1:",
                                        $descriptionText
                                    );
                                }


                                $descriptionText = preg_replace(
                                    '/\s*(Why\s+Choose\s+Khaatu\s+Foods\s+Forest\s+Honey\s*\??)/i',
                                    "\n\n$1\n",
                                    $descriptionText
                                );


                                $lines = preg_split(
                                    '/\r\n|\r|\n/',
                                    $descriptionText
                                );

                                $specRows = [];
                                $normalText = [];
                                $whyChooseText = [];

                                $insideWhyChoose = false;

                                foreach ($lines as $line) {

                                    $line = trim($line);

                                    if ($line === '') {
                                        continue;
                                    }


                                    if (
                                        stripos($line, 'Why Choose Khaatu Foods Forest Honey') === 0
                                    ) {

                                        $insideWhyChoose = true;

                                        $line = preg_replace(
                                            '/^Why\s+Choose\s+Khaatu\s+Foods\s+Forest\s+Honey\s*\??/i',
                                            '',
                                            $line
                                        );

                                        if (!empty(trim($line))) {
                                            $whyChooseText[] = trim($line);
                                        }

                                        continue;
                                    }


                                    if ($insideWhyChoose) {

                                        $whyChooseText[] = $line;

                                        continue;
                                    }


                                    $foundSpec = false;

                                    foreach ($specKeys as $key) {

                                        $pattern = '/^' . preg_quote($key, '/') . '\s*:\s*(.+)$/i';

                                        if (preg_match($pattern, $line, $match)) {

                                            $specRows[] = [
                                                'name'  => $key,
                                                'value' => trim($match[1])
                                            ];

                                            $foundSpec = true;

                                            break;
                                        }
                                    }


                                    if (!$foundSpec) {

                                        $normalText[] = $line;
                                    }
                                }
                                ?>


                                <!-- Normal Description -->
                                <?php if (!empty($normalText)): ?>

                                    <div class="product-description-text">

                                        <?php foreach ($normalText as $text): ?>

                                            <p>
                                                <?= htmlspecialchars(
                                                    $text,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ); ?>
                                            </p>

                                        <?php endforeach; ?>

                                    </div>

                                <?php endif; ?>


                                <!-- Specifications Table -->
                                <?php if (!empty($specRows)): ?>

                                    <div class="product-specification-table">

                                        <table class="table table-bordered product-details-table">

                                            <tbody>

                                                <?php foreach ($specRows as $row): ?>

                                                    <tr>

                                                        <th>
                                                            <?= htmlspecialchars(
                                                                $row['name'],
                                                                ENT_QUOTES,
                                                                'UTF-8'
                                                            ); ?>
                                                        </th>

                                                        <td>
                                                            <?= htmlspecialchars(
                                                                $row['value'],
                                                                ENT_QUOTES,
                                                                'UTF-8'
                                                            ); ?>
                                                        </td>

                                                    </tr>

                                                <?php endforeach; ?>

                                            </tbody>

                                        </table>

                                    </div>

                                <?php endif; ?>


                                <!-- Why Choose -->
                                <?php if (!empty($whyChooseText)): ?>

                                    <div class="why-choose-section">

                                        <h4 class="mb-10">
                                            Why Choose Khaatu Foods Forest Honey?
                                        </h4>

                                        <?php foreach ($whyChooseText as $text): ?>

                                            <p>
                                                <?= htmlspecialchars(
                                                    $text,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ); ?>
                                            </p>

                                        <?php endforeach; ?>

                                    </div>

                                <?php endif; ?>

                            </div>

                        <?php endif; ?>



                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                var $productBox = $(".tf-product-variant");
                if (!$productBox.length) {
                    return;
                }

                var basePrice = <?= json_encode((float)$price) ?>;
                basePrice = Number(basePrice);

                if (!Number.isFinite(basePrice) || basePrice < 0) {
                    basePrice = 0;
                }

                var $qty = $("#productQty");
                var $increase = $productBox.find(".btn-increase");
                var $decrease = $productBox.find(".btn-decrease");
                var $cartQty = $("#cartQty");
                var $buyNowQty = $("#buyNowQty");
                var $addCartPrice = $("#addCartPrice");

                function formatINR(amount) {
                    amount = Number(amount);
                    if (!Number.isFinite(amount)) {
                        amount = 0;
                    }
                    return new Intl.NumberFormat("en-IN", {
                        style: "currency",
                        currency: "INR",
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(amount);
                }

                function getQuantity() {
                    var value = String($qty.val()).replace(/[^0-9]/g, "");
                    var qty = parseInt(value, 10);

                    if (!Number.isFinite(qty) || qty < 1) {
                        qty = 1;
                    }

                    return qty;
                }

                function updateProduct() {
                    var qty = getQuantity();
                    var totalPrice = basePrice * qty;

                    if (!Number.isFinite(totalPrice)) {
                        totalPrice = 0;
                    }

                    $qty.val(qty);

                    if ($cartQty.length) {
                        $cartQty.val(qty);
                    }

                    if ($buyNowQty.length) {
                        $buyNowQty.val(qty);
                    }

                    if ($addCartPrice.length) {
                        $addCartPrice.text(formatINR(totalPrice));
                    }
                }

                $increase.off(".khaatuQuantity");
                $decrease.off(".khaatuQuantity");
                $qty.off(".khaatuQuantity");

                $increase.on("click.khaatuQuantity", function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    var qty = getQuantity();
                    qty++;
                    $qty.val(qty);
                    updateProduct();
                });

                $decrease.on("click.khaatuQuantity", function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    var qty = getQuantity();

                    if (qty > 1) {
                        qty--;
                    }

                    $qty.val(qty);
                    updateProduct();
                });

                $qty.on("input.khaatuQuantity", function() {
                    var value = String($(this).val()).replace(/[^0-9]/g, "");
                    $(this).val(value);

                    if (value !== "") {
                        updateProduct();
                    }
                });

                $qty.on("change.khaatuQuantity blur.khaatuQuantity", function() {
                    updateProduct();
                });

                $("#addToCartForm").off("submit.khaatuQuantity").on("submit.khaatuQuantity", function() {
                    var qty = getQuantity();
                    $qty.val(qty);
                    $cartQty.val(qty);
                    updateProduct();
                    return true;
                });

                $("#buyNowForm").off("submit.khaatuQuantity").on("submit.khaatuQuantity", function() {
                    var qty = getQuantity();
                    $qty.val(qty);
                    $buyNowQty.val(qty);
                    updateProduct();
                });

                updateProduct();
            });
        })(jQuery);
    </script>

    <?php include 'footer.php'; ?>
</body>

</html>