<?php require_once __DIR__ . '/includes/config.php';
$products = $conn->query("SELECT p.*,c.name category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.status=1 ORDER BY p.id DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khaatu Foods</title><?php include 'assets.php'; ?>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="tf-slideshow tf-btn-swiper-main hover-sw-nav">
        <div dir="ltr" class="swiper tf-swiper sw-slide-show slider_effect_fade" data-loop="true" data-effect="fade" data-delay="3000">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="slideshow-wrap">
                        <div class="sld_image"><img loading="lazy" width="1920" height="730" src="assets/images/slider/1.jpg" alt="Image"></div>
                        <div class="sld_content pst-5">
                            <div class="container"></div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="slideshow-wrap">
                        <div class="sld_image"><img loading="lazy" width="1920" height="730" src="assets/images/slider/2.jpg" alt="Image"></div>
                        <div class="sld_content pst-5">
                            <div class="container"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sw-line-default tf-sw-pagination"></div>
        </div>
        <div class="group-nav-action">
            <div class="container-full">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="tf-sw-nav text-white link nav-prev-swiper"><i class="icon icon-ArrowLongLeft"></i></div>
                    <div class="tf-sw-nav text-white link nav-next-swiper"><i class="icon icon-ArrowLongRight"></i></div>
                </div>
            </div>
        </div>
    </div>


    <section class="flat-spacing pt-0">
        <div class="container px-5">
            <div class="sect-heading type-2 text-center wow fadeInUp">
                <h3 class="s-title">Pure Honey And Premium Dates Together</h3>
                <p class="s-desc text-body-1 cl-text-2">Natural sweetness, rich nutrition, and lasting energy in every serving.</p>
            </div>

            <div class="row">
                <?php foreach ($products as $p):
                    $price = (float)($p['offerprice'] > 0 ? $p['offerprice'] : $p['price']);
                ?>

                    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                        <div class="swiper-slide wow fadeInUp">
                            <div class="card-product">

                                <div class="card-product_wrapper">
                                    <a href="product.php?id=<?= $p['id'] ?>" class="product-img">
                                        <img
                                            class="img-product"
                                            loading="lazy"
                                            width="330"
                                            height="440"
                                            src="admin/uploads-img/<?= e($p['image']) ?>"
                                            alt="<?= e($p['name']) ?>">

                                        <img
                                            class="img-hover"
                                            loading="lazy"
                                            width="330"
                                            height="440"
                                            src="admin/uploads-img/<?= e($p['image']) ?>"
                                            alt="<?= e($p['name']) ?>">
                                    </a>

                                    <ul class="product-action_list">
                                        <li class="wishlist">
                                            <a href="#;" class="hover-tooltip tooltip-left box-icon">
                                                <span class="icon icon-heart"></span>
                                                <span class="tooltip">Add to Wishlist</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="product.php?id=<?= $p['id'] ?>" class="hover-tooltip tooltip-left box-icon">
                                                <span class="icon icon-Eye"></span>
                                                <span class="tooltip">Quick view</span>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="product-action_bot">
                                        <a href="product.php?id=<?= $p['id'] ?>" class="tf-btn btn-white small w-100">
                                            Quick Add
                                        </a>
                                    </div>
                                </div>

                                <div class="card-product_info">
                                    <a
                                        href="product.php?id=<?= $p['id'] ?>"
                                        class="name-product lh-24 fw-medium link-underline-text">
                                        <?= e($p['name']) ?>
                                    </a>

                                    <div class="star-wrap d-flex align-items-center">
                                        <i class="icon icon-Star"></i>
                                        <i class="icon icon-Star"></i>
                                        <i class="icon icon-Star"></i>
                                        <i class="icon icon-Star"></i>
                                        <i class="icon icon-Star"></i>
                                    </div>

                                    <div class="price-wrap">
                                        <span class="price-new fw-medium">
                                            <?= money($price) ?>
                                        </span>

                                        <?php if ($p['offerprice'] > 0): ?>
                                            <span class="price-old">
                                                <?= money($p['price']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <!-- <div ><a href="" class="tf-btn btn-primary small w-100">
                                        buy now
                                    </a></div> -->
                                </div>

                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Marketplace & Social Listing Section -->
    <section class="flat-spacing marketplace-section" style=background-color:antiqueWhite;>
        <div class="container">
            <div class="sect-heading type-2 text-center wow fadeInUp">
                <h3 class="s-title">Find Us Everywhere You Shop</h3>
                <p class="s-desc text-body-1 cl-text-2">
                    Shop our products and stay connected with us across your favorite platforms.
                </p>
            </div>

            <div class="marketplace-list">

                <!-- Instagram -->
                <a href="https://www.instagram.com/khaatufoods?igsh=ZWJvd2ppczBubjc4"  class="marketplace-card wow fadeInUp" data-wow-delay="0.1s" target="_blank">
                    <div class="marketplace-icon instagram-icon">
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                    <div class="marketplace-content">
                        <h5>Instagram</h5>
                        <p>Follow us & discover our latest products</p>
                    </div>
                    <span class="marketplace-arrow">
                        <i class="icon icon-arrow-right"></i>
                    </span>
                </a>

                <!-- Facebook -->
                <a href="https://www.facebook.com/share/18frkK8jWS/" class="marketplace-card wow fadeInUp" data-wow-delay="0.2s" target="_blank">
                    <div class="marketplace-icon facebook-icon">
                        <i class="fa-brands fa-facebook-f"></i>
                    </div>
                    <div class="marketplace-content">
                        <h5>Facebook</h5>
                        <p>Connect with us & explore our collection</p>
                    </div>
                    <span class="marketplace-arrow">
                        <i class="icon icon-arrow-right"></i>
                    </span>
                </a>

                <!-- YouTube -->
                <a href="https://youtube.com/@khaatufoods?si=EbiXDcLEtUf-3aSQ" class="marketplace-card wow fadeInUp" data-wow-delay="0.3s" target="_blank">
                    <div class="marketplace-icon youtube-icon">
                        <i class="fa-brands fa-youtube"></i>
                    </div>
                    <div class="marketplace-content">
                        <h5>YouTube</h5>
                        <p>Watch our products, stories & updates</p>
                    </div>
                    <span class="marketplace-arrow">
                        <i class="icon icon-arrow-right"></i>
                    </span>
                </a>

                <!-- Flipkart -->
                <a href="#" class="marketplace-card wow fadeInUp" data-wow-delay="0.4s" target="_blank">
                    <div class="marketplace-icon flipkart-icon">
                        <span class="flipkart-logo">f</span>
                    </div>
                    <div class="marketplace-content">
                        <h5>Flipkart</h5>
                        <p>Shop our products on Flipkart</p>
                    </div>
                    <span class="marketplace-arrow">
                        <i class="icon icon-arrow-right"></i>
                    </span>
                </a>

                <!-- Amazon -->
                <a href="#" class="marketplace-card wow fadeInUp" data-wow-delay="0.5s" target="_blank">
                    <div class="marketplace-icon amazon-icon">
                        <i class="fa-brands fa-amazon"></i>
                    </div>
                    <div class="marketplace-content">
                        <h5>Amazon</h5>
                        <p>Browse and shop our products on Amazon</p>
                    </div>
                    <span class="marketplace-arrow">
                        <i class="icon icon-arrow-right"></i>
                    </span>
                </a>

            </div>
        </div>
    </section>
    <!-- Collection -->
    <div class="themesFlat pt-5 ">
        <div class="container">
            <div class="tf-grid-layout md-col-2 flat-spacing-2 pt-0">
                <div class="banner-image-text type-abs style-1 wow fadeInLeft">
                    <a href="shop-default.html" class="bn-image img-style">
                        <img loading="lazy" width="690" height="388" src="assets/collection/1.jpg"
                            alt="Image">
                    </a>

                </div>
                <div class="banner-image-text type-abs style-1 wow fadeInLeft" data-wow-delay="0.1s">
                    <a href="shop-default.html" class="bn-image img-style">
                        <img loading="lazy" width="690" height="388" src="assets/collection/2.jpg"
                            alt="Image">
                    </a>

                </div>
            </div>

        </div>
    </div>
    <!-- /Collection -->


    <!-- Testimonial -->
    <section class="flat-spacing testimonial-section">
        <div class="container">
            <div class="sect-heading type-2 text-center wow fadeInUp">
                <h3 class="s-title">
                    Customer Say!
                </h3>
                <p class="s-desc text-body-1 cl-text-2">
                    Our customers adore our products, and we constantly aim to delight them.
                </p>
            </div>
            <div dir="ltr" class="swiper tf-swiper" data-preview="2" data-tablet="2" data-mobile-sm="1" data-mobile="1" data-space-lg="60" data-space-md="30" data-space="15" data-pagination="1" data-pagination-sm="2" data-pagination-md="2" data-pagination-lg="2">
                <div class="swiper-wrapper">
                    <!-- slide 1 -->
                    <div class="swiper-slide">
                        <div class="testimonial-v01 style-1 style-def wow fadeInLeft">
                            <div class="tes-image">
                                <img loading="lazy" width="285" height="380" src="assets/test/1.jpg" alt="Image">
                            </div>
                            <div class="tes-content">
                                <div class="star-wrap d-flex align-items-center">
                                    <i class="icon icon-Star-thin fs-24"></i>
                                    <i class="icon icon-Star-thin fs-24"></i>
                                    <i class="icon icon-Star-thin fs-24"></i>
                                    <i class="icon icon-Star-thin fs-24"></i>
                                    <i class="icon icon-Star-thin fs-24"></i>
                                </div>
                                <div class="tes_author">
                                    <p class="author-name h5">Nikhil Vishwakarma</p>
                                    <div class="br-line"></div>
                                    <div class="author-verified">
                                        <i class="icon icon-CheckCircle1"></i>
                                        <span class="cl-text-2">
                                            Verified Buyer
                                        </span>
                                    </div>
                                </div>
                                <p class="tes_text h6">
                                    “Khaatu Foods Honey Dates are absolutely delicious! The perfect blend of naturally sweet dates and rich honey makes them a satisfying snack. They taste fresh, soft, and flavorful, making them perfect for enjoying with tea or anytime during the day.” </p>

                            </div>
                        </div>
                    </div>
                    <!-- slide 2 -->
                    <div class="swiper-slide">
                        <div class="testimonial-v01 style-1 style-def wow fadeInLeft" data-wow-delay="0.1s">
                            <div class="tes-image">
                                <img loading="lazy" width="285" height="380" src="assets/test/2.jpg" alt="Image">
                            </div>
                            <div class="tes-content">
                                <div class="star-wrap d-flex align-items-center">
                                    <i class="icon icon-Star-thin fs-24"></i>
                                    <i class="icon icon-Star-thin fs-24"></i>
                                    <i class="icon icon-Star-thin fs-24"></i>
                                    <i class="icon icon-Star-thin fs-24"></i>
                                    <i class="icon icon-Star-thin fs-24"></i>
                                </div>
                                <div class="tes_author">
                                    <p class="author-name h5">Arun Kumar</p>
                                    <div class="br-line"></div>

                                </div>
                                <p class="tes_text h6">
                                    “I’m genuinely impressed with Khaatu Foods Honey Dates. They offer a delicious combination of sweetness and natural flavor. The dates are soft, tasty, and enjoyable. My family loves them, and they have become our favorite everyday snack at home.” </p>

                            </div>
                        </div>
                    </div>
                    <!-- slide 3 -->
                    <div class="swiper-slide">
                        <div class="testimonial-v01 style-1 style-def wow fadeInLeft" data-wow-delay="0.1s">
                            <div class="tes-image">
                                <img loading="lazy" width="285" height="380" src="assets/test/3.jpg" alt="Image">
                            </div>
                            <div class="tes-content">
                                <div class="star-wrap d-flex align-items-center">
                                    <i class="icon icon-Star-thin fs-24"></i>
                                    <i class="icon icon-Star-thin fs-24"></i>
                                    <i class="icon icon-Star-thin fs-24"></i>
                                    <i class="icon icon-Star-thin fs-24"></i>
                                    <i class="icon icon-Star-thin fs-24"></i>
                                </div>
                                <div class="tes_author">
                                    <p class="author-name h5">Amit Kumar</p>
                                    <div class="br-line"></div>

                                </div>
                                <p class="tes_text h6">
                                    “Khaatu Foods Forest Honey has a rich, authentic taste and wonderful natural aroma. Its smooth texture and deep sweetness make it perfect for breakfast, warm milk, or tea. I really enjoy its natural flavor and would happily recommend it to everyone.” </p>

                            </div>
                        </div>
                    </div>
                    <!-- slide 4 -->
                    <div class="swiper-slide">
                        <div class="testimonial-v01 style-1 style-def wow fadeInLeft" data-wow-delay="0.1s">
                            <div class="tes-image">
                                <img loading="lazy" width="285" height="380" src="assets/test/4.jpg" alt="Image">
                            </div>
                            <div class="tes-content">
                                <div class="star-wrap d-flex align-items-center">
                                    <i class="icon icon-Star-thin fs-24"></i>
                                    <i class="icon icon-Star-thin fs-24"></i>
                                    <i class="icon icon-Star-thin fs-24"></i>
                                    <i class="icon icon-Star-thin fs-24"></i>
                                    <i class="icon icon-Star-thin fs-24"></i>
                                </div>
                                <div class="tes_author">
                                    <p class="author-name h5">Aditya Kumar</p>
                                    <div class="br-line"></div>
                                    <div class="author-verified">
                                        <i class="icon icon-CheckCircle1"></i>
                                        <span class="cl-text-2">
                                            Verified Buyer
                                        </span>
                                    </div>
                                </div>
                                <p class="tes_text h6">
                                    “I absolutely love Khaatu Foods Forest Honey! It has a beautifully rich taste, smooth texture, and natural sweetness that feels genuine. I enjoy adding it to my morning routine, and the quality makes this honey a product I trust.” </p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="sw-line-default style-2 tf-sw-pagination"></div>
            </div>
        </div>
    </section>
    <!-- /Testimonial -->

    <!-- Box Icon -->
    <div class="flat-spacing">
        <div class="container">
            <div dir="ltr" class="swiper tf-swiper" data-preview="4" data-tablet="3" data-mobile-sm="2" data-mobile="1" data-space-lg="30" data-space-md="20" data-space="10" data-pagination="1" data-pagination-sm="2" data-pagination-md="3" data-pagination-lg="4">
                <div class="swiper-wrapper">
                    <!-- slide 1 -->
                    <div class="swiper-slide">
                        <div class="box-icon_V01 wow fadeInLeft">
                            <span class="icon">
                                <i class="icon-ArrowUDownLeft"></i>
                            </span>
                            <div class="content">
                                <p class="title h6">7-Day Returns</p>
                                <p class="text cl-text-2">Risk-free shopping with easy returns.</p>
                            </div>
                        </div>
                    </div>
                    <!-- slide 2 -->
                    <div class="swiper-slide">
                        <div class="box-icon_V01 wow fadeInLeft">
                            <span class="icon">
                                <i class="icon-Package"></i>
                            </span>
                            <div class="content">
                                <p class="title h6">Free Shipping</p>
                                <p class="text cl-text-2">No extra costs, just the price you see.</p>
                            </div>
                        </div>
                    </div>
                    <!-- slide 3 -->
                    <div class="swiper-slide">
                        <div class="box-icon_V01 wow fadeInLeft">
                            <span class="icon">
                                <i class="icon-Headset"></i>
                            </span>
                            <div class="content">
                                <p class="title h6">24/7 Support</p>
                                <p class="text cl-text-2">24/7 support, always here just for you.</p>
                            </div>
                        </div>
                    </div>
                    <!-- slide 4 -->
                    <div class="swiper-slide">
                        <div class="box-icon_V01 wow fadeInLeft">
                            <span class="icon">
                                <i class="icon-SealPercent"></i>
                            </span>
                            <div class="content">
                                <p class="title h6">Member Discounts</p>
                                <p class="text cl-text-2">Special prices for our loyal customers.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sw-line-default style-2 tf-sw-pagination"></div>
            </div>
        </div>
    </div>
    <!-- /Box Icon -->
    <?php include 'footer.php'; ?>
</body>

</html>