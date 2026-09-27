 <!-- Footer -->
 <?php require_once __DIR__ . '/includes/config.php';
    $headerUser = current_user();
    $headerCartCount = 0;
    if ($headerUser) {
        $q = $conn->prepare('SELECT COALESCE(SUM(quantity),0) c FROM cart WHERE user_id=?');
        $q->bind_param('i', $headerUser['id']);
        $q->execute();
        $headerCartCount = (int)$q->get_result()->fetch_assoc()['c'];
    } else {
        $headerCartCount = guest_cart_count();
    }
    $headerProducts = $conn->query('SELECT id,name FROM products WHERE status=1 ORDER BY id DESC LIMIT 20')->fetch_all(MYSQLI_ASSOC); ?>


 <footer class="tf-footer" style="background-image: url(assets/images/footer-bg.jpg); background-size: cover; background-position: center; background-repeat: no-repeat;">
     <div class="footer-inner flat-spacing position-relative">
         <div class="br-line fake-class top-0"></div>
         <div class="container">
             <div class="row">
                 <div class="col-md-6 col-lg-4">
                     <div class="footer-infor d-flex flex-column align-items-start mb-lg-0">
                         <a href="index.html" class="logo-site mb-16">
                             <img loading="lazy" width="150" height="30" src="assets/images/logo/main-logo.svg" alt="Image">
                         </a>
                         <p class="lh-26 cl-text-2">
                             Basahiya Urf Captainganj Babhnauli Near Baba Dhaba Ramkola Road Kushinagar, 274301, U.P. India.
                         </p>
                         <a href="https://maps.app.goo.gl/7Y223JFCp7aUZXpB7" target="_blank" class="text-decoration-underline text-primary lh-26 mb-16">
                             Open in Maps
                         </a>
                         <a href="mailto:khaatufood@gmail.com" class="cl-text-2 link mb-8">
                             khaatufood@gmail.com
                         </a>
                         <a href="tel:+917398026045" class="cl-text-2 link mb-16">
                             +91 7398026045
                         </a>
                         <ul class="tf-social-icon-2">
                             <li>
                                 <a href="https://www.facebook.com/share/18frkK8jWS/" target="_blank">
                                     <img loading="lazy" width="24" height="24" src="assets/icon/facebook-color-svgrepo-com.svg" alt="Image">
                                 </a>
                             </li>

                             <li>
                                 <a href="https://www.instagram.com/khaatufoods?igsh=ZWJvd2ppczBubjc4" target="_blank">
                                     <img loading="lazy" width="24" height="24" src="assets/icon/instagram-1-svgrepo-com.svg" alt="Image">
                                 </a>
                             </li>

                             <li>
                                 <a href="https://youtube.com/@khaatufoods?si=EbiXDcLEtUf-3aSQ" target="_blank">
                                     <img loading="lazy" width="24" height="24" src="assets/icon/youtube-color-svgrepo-com.svg" alt="Image">
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </div>
                 <div class="col-sm-6 col-md-6 col-lg-2">
                     <div class="footer-col-block footer-wrap-1 mx-xl-auto">
                         <p class="footer-heading footer-heading-mobile">COMPANY</p>
                         <div class="tf-collapse-content">
                             <ul class="footer-menu-list">
                                 <li><a href="index.php" class="cl-text-2 link">Home</a></li>
                                 <li><a href="about.php" class="cl-text-2 link">About Us</a></li>
                                 <li><a href="contact-us.php" class="cl-text-2 link">Contact us</a></li>
                                 <li><a href="account-page.php" class="cl-text-2 link">My Account</a></li>
                             </ul>
                         </div>
                     </div>
                 </div>
                 <div class="col-sm-6 col-md-6 col-lg-2">
                     <div class="footer-col-block footer-wrap-2 mx-xl-auto">
                         <p class="footer-heading footer-heading-mobile">PRODUCTS</p>
                         <div class="tf-collapse-content">

                             <ul class="sub-menu_list">
                                 <?php foreach ($headerProducts as $hp): ?>
                                     <li><a href="product.php?id=<?= e($hp['id']) ?>" class="sub-menu_link has-text"><span class="cus-text"><?= e($hp['name']) ?></span></a></li>
                                 <?php endforeach; ?>
                             </ul>
                         </div>
                     </div>
                 </div>
                 <div class="col-md-6 col-lg-4">
                     <div class="footer-col-block footer-wrap-3 mb-0">
                         <p class="footer-heading footer-heading-mobile">LOCATION</p>
                         <div class="tf-collapse-content">
                             <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d910642.8154339067!2d83.706926!3d26.928921!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3993fbd400106417%3A0x42752772ef8d995d!2sBaba%20sweet%20house%20and%20restaurant!5e0!3m2!1sen!2sin!4v1789496961147!5m2!1sen!2sin" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>


                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <div class="footer-bottom">
         <div class="container">
             <div class="br-line sm-d-none"></div>
             <div class="inner-bottom">

                 <p class="text-nocopy cl-text-2">
                     ©2026 Khaatu Foods. All Rights Reserved.
                 </p>
                 <ul class="tf-list payment-list">
                     <li><a href="return-policy.php" class="cl-text-2 link">Return & Refund </a></li>
                     <li><a href="privacy-policy.php" class="cl-text-2 link">Privacy Policy</a></li>

                 </ul>
             </div>
         </div>
     </div>
 </footer>
 <!-- /Footer -->
 </main>
 <!-- Mobile Menu -->
 <div class="offcanvas offcanvas-start canvas-mb" id="mobileMenu">
     <div class="canvas-header">
         <span class="icon-close-popup" data-bs-dismiss="offcanvas">
             <i class="icon icon-X2"></i>
         </span>
         <form class="form-search-nav">
             <fieldset>
                 <input type="text" placeholder="What are you looking for?" required="">
             </fieldset>
             <button type="submit" class="btn-action">
                 <i class="icon icon-MagnifyingGlass"></i>
             </button>
         </form>
     </div>
     <div class="canvas-body">
         <div class="mb-content-top">
             <ul class="nav-ul-mb" id="wrapper-menu-navigation"></ul>
         </div>
         <div class="need-help-wrap">
             <p class="nd-title h6 fw-medium mb-16">Need Help?</p>
             <p class="lh-26 cl-text-2 mb-4">
                 Basahiya Urf Captainganj Babhnauli Near Baba Dhaba Ramkola Road Kushinagar, 274301, U.P. India.
             </p>
             <a href="https://maps.app.goo.gl/FG2LPWTF7EMT3A2X8" target="_blank" class="text-decoration-underline text-primary lh-26 mb-16">
                 Open in Maps
             </a>
             <a href="mailto:khaatufood@gmail.com" class="cl-text-2 link mb-8">
                 khaatufood@gmail.com
             </a>
             <a href="tel:3156666688" class="cl-text-2 link">
                 +91 7398026045
             </a>
         </div>
     </div>
     <!-- <div class="canvas-footer">
         <div class="d-flex justify-content-center border-end">
             <div class="tf-currencies">
                 <select class="tf-dropdown-select style-default type-currencies">
                     <option selected="" data-thumbnail="assets/images/country/us.png">(USD $)</option>
                     <option data-thumbnail="assets/images/country/vn.png">(VND ₫)</option>
                 </select>
             </div>
         </div>

     </div> -->
 </div>
 <!-- /Mobile Menu -->
 <!-- Toolbar -->
 <div class="tf-toolbar-bottom">
     <div class="toolbar-item">
         <a href="shop-default.html">
             <span class="toolbar-icon">
                 <i class="icon icon-storefront"></i>
             </span>
             <span class="toolbar-label">Shop</span>
         </a>
     </div>
     <div class="toolbar-item">
         <a href="#search" data-bs-toggle="modal">
             <span class="toolbar-icon">
                 <i class="icon icon-MagnifyingGlass"></i>
             </span>
             <span class="toolbar-label">Search</span>
         </a>
     </div>
     <div class="toolbar-item">
         <a href="account-page.html">
             <span class="toolbar-icon">
                 <i class="icon icon-User"></i>
             </span>
             <span class="toolbar-label">Account</span>
         </a>
     </div>
     <div class="toolbar-item">
         <a href="wishlist.html">
             <span class="toolbar-icon">
                 <i class="icon icon-HeartStraight"></i>
             </span>
             <span class="toolbar-label">Wishlist</span>
         </a>
     </div>
     <div class="toolbar-item">
         <a href="view-cart.html">
             <span class="toolbar-icon">
                 <i class="icon icon-Handbag"></i>
                 <span class="toolbar-count">12</span>
             </span>
             <span class="toolbar-label">Cart</span>
         </a>
     </div>
 </div>
 <!-- /Toolbar -->

 <!-- Forgot Pass -->
 <div class="modal modalCentered fade modal-log modal-log_forgot" id="modalForgot">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <span class="icon-close-popup" data-bs-dismiss="modal">
                 <i class="icon-X2"></i>
             </span>
             <div class="modal-heading text-center">
                 <h3 class="title-pop mb-8">Forgot Password</h3>
                 <p class="desc-pop cl-text-2">We’ll send instructions to reset your password.</p>
             </div>
             <div class="modal-main">
                 <form class="form-log">
                     <div class="form-content">
                         <fieldset class="tf-field">
                             <label for="forgot-user" class="tf-lable fw-medium">
                                 Username or email address
                                 <span class="text-primary">*</span>
                             </label>
                             <input type="text" id="forgot-user" placeholder="Username or email address*" required="">
                         </fieldset>
                     </div>
                     <div class="group-action">
                         <button type="submit" class="tf-btn animate-btn w-100">
                             Get Reset Code
                         </button>
                         <p class="orther-log text-center">
                             Remember your password?
                             <a href="#sign" data-bs-toggle="modal" class="text-primary text-decoration-underline">
                                 Sign In
                             </a>
                         </p>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
 <!-- /Forgot Pass -->
 <!-- Size Guide -->
 <!-- <div class="modal modalCentered fade modal-find_size" id="findSize">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-heading d-flex align-items-center justify-content-between">
                 <h4 class="title-pop">Size Chart</h4>
                 <span class="cs-pointer d-flex link" data-bs-dismiss="modal">
                     <i class="icon-X2 fs-24"></i>
                 </span>
             </div>
             <div class="modal-main">
                 <div class="tf-rte">
                     <div class="tf-table-res-df mb-20">
                         <p class="h6 fw-medium mb-16 cl-text-main">Size Guide</p>
                         <div class="overflow-auto">
                             <table class="tf-sizeguide-table">
                                 <thead>
                                     <tr>
                                         <th>Size</th>
                                         <th>US</th>
                                         <th>Bust</th>
                                         <th>Waist</th>
                                         <th>Low Hip</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <tr>
                                         <td>XS</td>
                                         <td>2</td>
                                         <td>32</td>
                                         <td>24 - 25</td>
                                         <td>33 - 34</td>
                                     </tr>
                                     <tr>
                                         <td>S</td>
                                         <td>4</td>
                                         <td>34 - 35</td>
                                         <td>26 - 27</td>
                                         <td>35 - 26</td>
                                     </tr>
                                     <tr>
                                         <td>M</td>
                                         <td>6</td>
                                         <td>36 - 37</td>
                                         <td>28 - 29</td>
                                         <td>38 - 40</td>
                                     </tr>
                                     <tr>
                                         <td>L</td>
                                         <td>8</td>
                                         <td>38 - 29</td>
                                         <td>30 - 31</td>
                                         <td>42 - 44</td>
                                     </tr>
                                     <tr>
                                         <td>XL</td>
                                         <td>10</td>
                                         <td>40 - 41</td>
                                         <td>32 - 33</td>
                                         <td>45 - 47</td>
                                     </tr>
                                     <tr>
                                         <td>XXL</td>
                                         <td>12</td>
                                         <td>42 - 43</td>
                                         <td>34 - 35</td>
                                         <td>48 - 50</td>
                                     </tr>
                                 </tbody>
                             </table>
                         </div>
                     </div>
                     <div class="tf-page-size-chart-content">
                         <div>
                             <p class="h6 fw-medium mb-16 cl-text-main">Measuring Tips</p>
                             <div class="title fw-medium">Bust</div>
                             <p class="mb-12">Measure around the fullest part of your bust.</p>
                             <div class="title fw-medium">Waist</div>
                             <p class="mb-12">Measure around the narrowest part of your torso.</p>
                             <div class="title fw-medium">Low Hip</div>
                             <p class="mb-12">With your feet together measure around the fullest part of your
                                 hips/rear.
                             </p>
                         </div>
                         <div>
                             <img loading="lazy" width="270" height="270" src="assets/images/section/size-chart.jpg" alt="Image">
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div> -->
 <!-- /Size Guide -->
 <!-- Share -->
 <div class="modal modalCentered fade modal-share" id="share">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-heading d-flex align-items-center justify-content-between">
                 <h4 class="title-pop">Share</h4>
                 <span class="cs-pointer d-flex link" data-bs-dismiss="modal">
                     <i class="icon-X2 fs-24"></i>
                 </span>
             </div>
             <div class="modal-main">
                 <ul class="tf-social-icon-2 hv-dark mb-20">
                     <li>
                         <a href="https://www.facebook.com/share/18frkK8jWS/" target="_blank">
                             <img loading="lazy" width="24" height="24" src="assets/icon/facebook-color-svgrepo-com.svg" alt="Image">
                         </a>
                     </li>

                     <li>
                         <a href="https://www.instagram.com/khaatufoods?igsh=ZWJvd2ppczBubjc4" target="_blank">
                             <img loading="lazy" width="24" height="24" src="assets/icon/instagram-1-svgrepo-com.svg" alt="Image">
                         </a>
                     </li>

                     <li>
                         <a href="https://youtube.com/@khaatufoods?si=EbiXDcLEtUf-3aSQ" target="_blank">
                             <img loading="lazy" width="24" height="24" src="assets/icon/youtube-color-svgrepo-com.svg" alt="Image">
                         </a>
                     </li>

                 </ul>
                 <div class="wrap-code btn-coppy-text">
                     <p class="coppyText cl-text-2" id="coppyText">http://themesflat.com</p>
                     <div class="btn-action-copy tf-btn">Copy</div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- /Share -->
 <!-- Ask -->
 <div class="modal modalCentered fade modal-log modal-ask" id="ask">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <span class="icon-close-popup" data-bs-dismiss="modal">
                 <i class="icon-X2"></i>
             </span>
             <div class="modal-heading text-center">
                 <h3 class="title-pop mb-8">Ask A Question</h3>
                 <p class="desc-pop cl-text-2">Have a question? Ask us today!</p>
             </div>
             <div class="modal-main">
                 <form class="form-log mb-20">
                     <div class="form-content">
                         <fieldset class="tf-field">
                             <label for="name-ask" class="tf-lable fw-medium">
                                 Your Name<span class="text-primary">*</span>
                             </label>
                             <input type="text" id="name-ask" placeholder="Your Name*" required="">
                         </fieldset>
                         <fieldset class="tf-field">
                             <label for="email-ask" class="tf-lable fw-medium">
                                 Your Email<span class="text-primary">*</span>
                             </label>
                             <input type="email" id="email-ask" placeholder="Your Email*" required="">
                         </fieldset>
                         <fieldset class="tf-field">
                             <label for="phone-ask" class="tf-lable fw-medium">
                                 Your phone
                             </label>
                             <input type="number" id="phone-ask" placeholder="Your phone" required="">
                         </fieldset>
                         <fieldset class="tf-field">
                             <label for="message-ask" class="tf-lable fw-medium">
                                 Your Message
                                 <span class="text-primary">*</span>
                             </label>
                             <textarea id="message-ask" placeholder="Your Message*" required=""></textarea>
                         </fieldset>
                     </div>
                     <div class="group-action">
                         <button type="submit" class="tf-btn animate-btn w-100">
                             Subcribe
                         </button>
                     </div>
                 </form>
                 <ul class="tf-social-icon-2 hv-dark justify-content-center">
                     <li>
                         <a href="https://www.facebook.com/share/18frkK8jWS/" target="_blank">
                             <img loading="lazy" width="24" height="24" src="assets/icon/facebook-color-svgrepo-com.svg" alt="Image">
                         </a>
                     </li>

                     <li>
                         <a href="https://www.instagram.com/khaatufoods?igsh=ZWJvd2ppczBubjc4" target="_blank">
                             <img loading="lazy" width="24" height="24" src="assets/icon/instagram-1-svgrepo-com.svg" alt="Image">
                         </a>
                     </li>

                     <li>
                         <a href="https://youtube.com/@khaatufoods?si=EbiXDcLEtUf-3aSQ" target="_blank">
                             <img loading="lazy" width="24" height="24" src="assets/icon/youtube-color-svgrepo-com.svg" alt="Image">
                         </a>
                     </li>
                 </ul>
             </div>
         </div>
     </div>
 </div>
 <!-- /Ask -->
 <!-- Compare -->
 <div class="offcanvas offcanvas-bottom canvas-compare" id="compare">
     <div class="canvas-wrapper">
         <div class="canvas-body">
             <div class="container">
                 <div class="tf-compare-list main-list-clear wrap-empty_text">
                     <div class="tf-compare-head">
                         <h4 class="title letter-space-0">Compare products</h4>
                     </div>
                     <div class="tf-compare-offcanvas list-empty">
                         <p class="box-text_empty cl-text-2">Your Compare is curently empty</p>
                         <div class="tf-compare-item file-delete">
                             <a href="">
                                 <div class="icon remove">
                                     <i class="icon-X2"></i>
                                 </div>
                                 <img class="radius-3" width="660" height="880" src="assets/images/product/product-1.jpg" alt="Image">
                             </a>
                         </div>
                         <div class="tf-compare-item file-delete">
                             <a href="">
                                 <div class="icon remove">
                                     <i class="icon-X2"></i>
                                 </div>
                                 <img class="radius-3" width="660" height="880" src="assets/images/product/product-2.jpg" alt="Image">
                             </a>
                         </div>
                         <div class="tf-compare-item file-delete">
                             <a href="">
                                 <div class="icon remove">
                                     <i class="icon-X2"></i>
                                 </div>
                                 <img class="radius-3" width="660" height="880" src="assets/images/product/product-3.jpg" alt="Image">
                             </a>
                         </div>
                     </div>
                     <div class="tf-compare-buttons justify-content-center">
                         <a href="compare.html" class="tf-btn animate-btn">Compare </a>
                         <div class="tf-btn btn-white btn-stroke clear-list-empty tf-compare-button-clear-all">
                             Clear All
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- /Compare -->
 <!-- =============================================================================================== -->

 <!-- Quick Add 1-->
 <div class="modal modalCentered fade modal-quickadd" id="quickAdd">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="d-flex align-items-center justify-content-between mb-20">
                 <h5>Quick Add</h5>
                 <span class="d-flex cs-pointer link" data-bs-dismiss="modal">
                     <i class="icon icon-X2 fs-24"></i>
                 </span>
             </div>
             <div class="tf-product-quick_add tf-quick-prd_variant">
                 <div class="product-mini-view">
                     <a href="" class="prd-image">
                         <img class="img-product" width="80" height="107" src="assets/pro/1.png" alt="Image Product">
                     </a>
                     <div class="prd-content">
                         <a href="" class="prd-name fw-medium link-underline link text-capitalize">
                             Mix Honey Dates 500gm
                         </a>
                         <div class="price-wrap">
                             <span class="price-new text-primary fw-semibold price-on-sale">₹359.00</span>
                             <span class="price-old text-caption-01 cl-text-3">₹459.00</span>
                         </div>
                     </div>
                 </div>

                 <div class="quick-variant-picker picker_size">
                     <div class="variant-picker_label mb-12">
                         <div>
                             Weight:
                             <span class="variant__value text-capitalize fw-medium">500gm</span>
                         </div>
                         <!-- <a href="#findSize" data-bs-toggle="modal" class="tf-btn-line-2 style-primary text-caption-01 fw-semibold">
                             Size Guide
                         </a> -->
                     </div>
                     <div class="variant-picker_values">

                         <span class="size_btn active" data-quick-size="500gm" data-quick-price="359.00">500gm</span>
                         <span class="size_btn " data-quick-size="1kg" data-quick-price="498.00">1kg</span>


                     </div>
                 </div>
                 <div class="product-total-quantity">
                     <p class="">
                         Quantity:
                     </p>
                     <div class="group-action">
                         <div class="wg-quantity">
                             <button class="btn-quantity btn-decrease">
                                 <i class="icon icon-minus"></i>
                             </button>
                             <input class="quantity-product" type="text" name="number" value="1">
                             <button class="btn-quantity btn-increase">
                                 <i class="icon icon-plus"></i>
                             </button>
                         </div>
                         <a href="#shoppingCart" data-bs-toggle="offcanvas" class="btn-action-price tf-btn type-xl animate-btn w-100">
                             Add to Cart
                             <span class="d-none d-sm-block d-md-none d-lg-block">&nbsp;-&nbsp;</span>
                             <span class="price-add d-none d-sm-block d-md-none d-lg-block">₹359.00</span>
                         </a>
                     </div>
                     <a href="" class="tf-btn type-xl btn-primary animate-btn w-100">
                         Buy It Now
                     </a>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- /Quick Add 1-->

 <!-- Quick Add 2-->
 <div class="modal modalCentered fade modal-quickadd" id="quickAdd2">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="d-flex align-items-center justify-content-between mb-20">
                 <h5>Quick Add</h5>
                 <span class="d-flex cs-pointer link" data-bs-dismiss="modal">
                     <i class="icon icon-X2 fs-24"></i>
                 </span>
             </div>
             <div class="tf-product-quick_add tf-quick-prd_variant">
                 <div class="product-mini-view">
                     <a href="" class="prd-image">
                         <img class="img-product" width="80" height="107" src="assets/pro/2.png" alt="Image Product">
                     </a>
                     <div class="prd-content">
                         <a href="" class="prd-name fw-medium link-underline link text-capitalize">
                             Honey Dates 1kg
                         </a>
                         <div class="price-wrap">
                             <span class="price-new text-primary fw-semibold price-on-sale">₹498.00</span>
                             <span class="price-old text-caption-01 cl-text-3">₹598.00</span>
                         </div>
                     </div>
                 </div>

                 <div class="quick-variant-picker picker_size">
                     <div class="variant-picker_label mb-12">
                         <div>
                             Weight:
                             <span class="variant__value text-capitalize fw-medium">1kg</span>
                         </div>
                         <!-- <a href="#findSize" data-bs-toggle="modal" class="tf-btn-line-2 style-primary text-caption-01 fw-semibold">
                             Size Guide
                         </a> -->
                     </div>
                     <div class="variant-picker_values">

                         <span class="size_btn " data-quick-size="500gm" data-quick-price="359.00">500gm</span>
                         <span class="size_btn active" data-quick-size="1kg" data-quick-price="498.00">1kg</span>


                     </div>
                 </div>
                 <div class="product-total-quantity">
                     <p class="">
                         Quantity:
                     </p>
                     <div class="group-action">
                         <div class="wg-quantity">
                             <button class="btn-quantity btn-decrease">
                                 <i class="icon icon-minus"></i>
                             </button>
                             <input class="quantity-product" type="text" name="number" value="1">
                             <button class="btn-quantity btn-increase">
                                 <i class="icon icon-plus"></i>
                             </button>
                         </div>
                         <a href="#shoppingCart" data-bs-toggle="offcanvas" class="btn-action-price tf-btn type-xl animate-btn w-100">
                             Add to Cart
                             <span class="d-none d-sm-block d-md-none d-lg-block">&nbsp;-&nbsp;</span>
                             <span class="price-add d-none d-sm-block d-md-none d-lg-block">₹498.00</span>
                         </a>
                     </div>
                     <a href="" class="tf-btn type-xl btn-primary animate-btn w-100">
                         Buy It Now
                     </a>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- /Quick Add 2-->

 <!-- Quick Add 3-->
 <div class="modal modalCentered fade modal-quickadd" id="quickAdd3">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="d-flex align-items-center justify-content-between mb-20">
                 <h5>Quick Add</h5>
                 <span class="d-flex cs-pointer link" data-bs-dismiss="modal">
                     <i class="icon icon-X2 fs-24"></i>
                 </span>
             </div>
             <div class="tf-product-quick_add tf-quick-prd_variant">
                 <div class="product-mini-view">
                     <a href="" class="prd-image">
                         <img class="img-product" width="80" height="107" src="assets/pro/3.png" alt="Image Product">
                     </a>
                     <div class="prd-content">
                         <a href="" class="prd-name fw-medium link-underline link text-capitalize">
                             Forest Honey 500gm
                         </a>
                         <div class="price-wrap">
                             <span class="price-new text-primary fw-semibold price-on-sale">₹349.00</span>
                             <span class="price-old text-caption-01 cl-text-3">₹449.00</span>
                         </div>
                     </div>
                 </div>

                 <div class="quick-variant-picker picker_size">
                     <div class="variant-picker_label mb-12">
                         <div>
                             Weight:
                             <span class="variant__value text-capitalize fw-medium">500gm</span>
                         </div>
                         <!-- <a href="#findSize" data-bs-toggle="modal" class="tf-btn-line-2 style-primary text-caption-01 fw-semibold">
                                Size Guide
                            </a> -->
                     </div>
                     <div class="variant-picker_values">

                         <span class="size_btn active" data-quick-size="500gm" data-quick-price="349.00">500gm</span>
                         <span class="size_btn " data-quick-size="1kg" data-quick-price="449.00">1kg</span>


                     </div>
                 </div>
                 <div class="product-total-quantity">
                     <p class="">
                         Quantity:
                     </p>
                     <div class="group-action">
                         <div class="wg-quantity">
                             <button class="btn-quantity btn-decrease">
                                 <i class="icon icon-minus"></i>
                             </button>
                             <input class="quantity-product" type="text" name="number" value="1">
                             <button class="btn-quantity btn-increase">
                                 <i class="icon icon-plus"></i>
                             </button>
                         </div>
                         <a href="#shoppingCart" data-bs-toggle="offcanvas" class="btn-action-price tf-btn type-xl animate-btn w-100">
                             Add to Cart
                             <span class="d-none d-sm-block d-md-none d-lg-block">&nbsp;-&nbsp;</span>
                             <span class="price-add d-none d-sm-block d-md-none d-lg-block">₹349.00</span>
                         </a>
                     </div>
                     <a href="" class="tf-btn type-xl btn-primary animate-btn w-100">
                         Buy It Now
                     </a>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- /Quick Add 3-->

 <!-- Quick Add 4-->
 <div class="modal modalCentered fade modal-quickadd" id="quickAdd4">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="d-flex align-items-center justify-content-between mb-20">
                 <h5>Quick Add</h5>
                 <span class="d-flex cs-pointer link" data-bs-dismiss="modal">
                     <i class="icon icon-X2 fs-24"></i>
                 </span>
             </div>
             <div class="tf-product-quick_add tf-quick-prd_variant">
                 <div class="product-mini-view">
                     <a href="" class="prd-image">
                         <img class="img-product" width="80" height="107" src="assets/pro/3.png" alt="Image Product">
                     </a>
                     <div class="prd-content">
                         <a href="" class="prd-name fw-medium link-underline link text-capitalize">
                             Forest Honey 1kg
                         </a>
                         <div class="price-wrap">
                             <span class="price-new text-primary fw-semibold price-on-sale">₹449.00</span>
                             <span class="price-old text-caption-01 cl-text-3">₹549.00</span>
                         </div>
                     </div>
                 </div>

                 <div class="quick-variant-picker picker_size">
                     <div class="variant-picker_label mb-12">
                         <div>
                             Weight:
                             <span class="variant__value text-capitalize fw-medium">1kg</span>
                         </div>
                         <!-- <a href="#findSize" data-bs-toggle="modal" class="tf-btn-line-2 style-primary text-caption-01 fw-semibold">
                                Size Guide
                            </a> -->
                     </div>
                     <div class="variant-picker_values">

                         <span class="size_btn " data-quick-size="500gm" data-quick-price="349.00">500gm</span>
                         <span class="size_btn active" data-quick-size="1kg" data-quick-price="449.00">1kg</span>


                     </div>
                 </div>
                 <div class="product-total-quantity">
                     <p class="">
                         Quantity:
                     </p>
                     <div class="group-action">
                         <div class="wg-quantity">
                             <button class="btn-quantity btn-decrease">
                                 <i class="icon icon-minus"></i>
                             </button>
                             <input class="quantity-product" type="text" name="number" value="1">
                             <button class="btn-quantity btn-increase">
                                 <i class="icon icon-plus"></i>
                             </button>
                         </div>
                         <a href="#shoppingCart" data-bs-toggle="offcanvas" class="btn-action-price tf-btn type-xl animate-btn w-100">
                             Add to Cart
                             <span class="d-none d-sm-block d-md-none d-lg-block">&nbsp;-&nbsp;</span>
                             <span class="price-add d-none d-sm-block d-md-none d-lg-block">₹449.00</span>
                         </a>
                     </div>
                     <a href="" class="tf-btn type-xl btn-primary animate-btn w-100">
                         Buy It Now
                     </a>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- /Quick Add 4-->


 <!-- Quick View -->
 <div class="offcanvas offcanvas-end canvas-quickview" id="quickView">
     <div class="mini-quick-image">
         <div class="wrap-quick wrapper-scroll-quickview">
             <div class="image item-scroll-quickview" data-scroll-quickview="Green">
                 <img loading="lazy" width="340" height="444" src="assets/images/product/single/detail-1.jpg" alt="Image">
             </div>
             <div class="image item-scroll-quickview" data-scroll-quickview="Green">
                 <img loading="lazy" width="340" height="444" src="assets/images/product/single/detail-1_2.jpg" alt="Image">
             </div>
             <div class="image item-scroll-quickview" data-scroll-quickview="Green">
                 <img loading="lazy" width="340" height="444" src="assets/images/product/single/detail-1_3.jpg" alt="Image">
             </div>
             <div class="image item-scroll-quickview" data-scroll-quickview="Gray">
                 <img loading="lazy" width="340" height="444" src="assets/images/product/single/detail-1_5.jpg" alt="Image">
             </div>
             <div class="image item-scroll-quickview" data-scroll-quickview="Gray">
                 <img loading="lazy" width="340" height="444" src="assets/images/product/single/detail-1_6.jpg" alt="Image">
             </div>
             <div class="image item-scroll-quickview" data-scroll-quickview="Black">
                 <img loading="lazy" width="340" height="444" src="assets/images/product/single/detail-1_7.jpg" alt="Image">
             </div>
             <div class="image item-scroll-quickview" data-scroll-quickview="Black">
                 <img loading="lazy" width="340" height="444" src="assets/images/product/single/detail-1_8.jpg" alt="Image">
             </div>
         </div>
     </div>
     <div class="wrap-canvas">
         <div class="canvas-header ps-md-0">
             <h5 class="title-pop">Quick View</h5>
             <span class="icon-close-popup" data-bs-dismiss="offcanvas">
                 <i class="icon icon-X2"></i>
             </span>
         </div>
         <div class="canvas-body ps-md-0">
             <div class="tf-product-quick_view tf-quick-prd_variant">
                 <div class="tf-product-info-heading">
                     <p class="product-infor-cate text-caption-01 mb-4">
                         Clothing
                     </p>
                     <h3 class="product-infor-name mb-12 letter-space-0">
                         Lyocell wrap top
                     </h3>
                     <div class="product-infor-meta mb-20">
                         <div class="meta_rate">
                             <div class="star-wrap normal d-flex align-items-center">
                                 <i class="icon icon-Star"></i>
                                 <i class="icon icon-Star"></i>
                                 <i class="icon icon-Star"></i>
                                 <i class="icon icon-Star"></i>
                                 <i class="icon icon-Star"></i>
                             </div>
                             <span class="text-caption-01 cl-text-2">
                                 (134 reviews)
                             </span>
                         </div>
                         <div class="br-line type-vertical"></div>
                         <div class="meta_sold">
                             <i class="icon icon-Lightning text-primary"></i>
                             <span class="text-caption-01 cl-text-2">18&nbsp;sold in last&nbsp;32&nbsp;hours</span>
                         </div>
                     </div>
                     <div class="product-infor-price mb-12">
                         <h4 class="price-on-sale">$59.99</h4>
                         <div class="br-line type-vertical"></div>
                         <p class="cl-text-3 text-decoration-line-through">$98.99</p>
                         <span class="badge-sale text-white fw-semibold text-caption-02">
                             -25%
                         </span>
                     </div>
                     <p class="product-infor-desc cl-text-2 mb-12">
                         The garments labelled as Committed are products that have been produced using
                         sustainable fibres or processes, reducing their environmental impact.
                     </p>
                     <div class="product-infor-reality lh-24">
                         <div class="ic d-flex">
                             <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                 <rect width="24" height="24" rx="4" fill="#101010"></rect>
                                 <path d="M19.4569 11.7975C19.435 11.7481 18.9056 10.5738 17.7287 9.39687C16.1606 7.82875 14.18 7 12 7C9.81999 7 7.83937 7.82875 6.27124 9.39687C5.09437 10.5738 4.56249 11.75 4.54312 11.7975C4.51469 11.8614 4.5 11.9306 4.5 12.0006C4.5 12.0706 4.51469 12.1398 4.54312 12.2037C4.56499 12.2531 5.09437 13.4269 6.27124 14.6038C7.83937 16.1713 9.81999 17 12 17C14.18 17 16.1606 16.1713 17.7287 14.6038C18.9056 13.4269 19.435 12.2531 19.4569 12.2037C19.4853 12.1398 19.5 12.0706 19.5 12.0006C19.5 11.9306 19.4853 11.8614 19.4569 11.7975ZM12 14.5C11.5055 14.5 11.0222 14.3534 10.6111 14.0787C10.1999 13.804 9.87951 13.4135 9.69029 12.9567C9.50107 12.4999 9.45157 11.9972 9.54803 11.5123C9.64449 11.0273 9.88259 10.5819 10.2322 10.2322C10.5819 9.8826 11.0273 9.6445 11.5123 9.54804C11.9972 9.45157 12.4999 9.50108 12.9567 9.6903C13.4135 9.87952 13.804 10.2 14.0787 10.6111C14.3534 11.0222 14.5 11.5055 14.5 12C14.5 12.663 14.2366 13.2989 13.7678 13.7678C13.2989 14.2366 12.663 14.5 12 14.5Z" fill="white"></path>
                             </svg>
                         </div>
                         28&nbsp;people are viewing this right now
                     </div>
                 </div>
                 <div class="br-line"></div>
                 <div class="tf-product-variant">
                     <div class="variant-picker-item variant-color">
                         <div class="variant-picker-label">
                             <div>
                                 Colors:
                                 <span class="variant-picker-label-value value-currentColor text-capitalize fw-medium">Gray</span>
                             </div>
                         </div>
                         <div class="variant-picker-values">

                             <input class="d-none" id="values-green" type="radio" name="colorQuickAdd" checked="">
                             <label class="hover-tooltip tooltip-bot color-btn color-btn_quick style-image btn-scroll-quickview active" for="values-green" data-color="Green" data-scroll-quickview="Green">
                                 <span class="img">
                                     <img loading="lazy" width="60" height="60" src="assets/images/product/single/img_square/detail-1_2.jpg" data-src="assets/images/product/single/img_square/detail-1_2.jpg" alt="img">
                                 </span>
                                 <span class="tooltip">Green</span>
                             </label>

                             <input class="d-none" id="values-gray" type="radio" name="colorQuickAdd">
                             <label class="hover-tooltip tooltip-bot color-btn color-btn_quick style-image btn-scroll-quickview" for="values-gray" data-color="Gray" data-scroll-quickview="Gray">
                                 <span class="img">
                                     <img loading="lazy" width="60" height="60" src="assets/images/product/single/img_square/detail-1_5.jpg" data-src="assets/images/product/single/img_square/detail-1_5.jpg" alt="img">
                                 </span>
                                 <span class="tooltip">Gray</span>
                             </label>

                             <input class="d-none" id="values-black" type="radio" name="colorQuickAdd">
                             <label class="hover-tooltip tooltip-bot color-btn color-btn_quick style-image btn-scroll-quickview" for="values-black" data-color="Black" data-scroll-quickview="Black">
                                 <span class="img">
                                     <img loading="lazy" width="60" height="60" src="assets/images/product/single/img_square/detail-1_7.jpg" data-src="assets/images/product/single/img_square/detail-1_7.jpg" alt="img">
                                 </span>
                                 <span class="tooltip">Black</span>
                             </label>
                         </div>
                     </div>

                     <div class="quick-variant-picker picker_size">
                         <div class="variant-picker_label mb-12">
                             <div>
                                 Size:
                                 <span class="variant__value text-capitalize fw-medium">M</span>
                             </div>
                             <div data-bs-dismiss="offcanvas">
                                 <a href="#findSize" data-bs-toggle="modal" class="tf-btn-line-2 style-primary text-caption-01 fw-semibold">
                                     Size Guide
                                 </a>
                             </div>
                         </div>
                         <div class="variant-picker_values">
                             <span class="size_btn" data-quick-size="S" data-quick-price="39.99">S</span>
                             <span class="size_btn active" data-quick-size="M" data-quick-price="59.99">M</span>
                             <span class="size_btn" data-quick-size="L" data-quick-price="79.99">L</span>
                             <span class="size_btn" data-quick-size="XL" data-quick-price="89.99">XL</span>
                             <span class="size_btn disabled" data-quick-size="XX" data-quick-price="99.99">XXL</span>
                         </div>
                     </div>

                     <div class="tf-product-total-quantity">
                         <p class="">
                             Quantity:
                         </p>
                         <div class="group-action">
                             <div class="wg-quantity">
                                 <button class="btn-quantity btn-decrease">
                                     <i class="icon icon-minus"></i>
                                 </button>
                                 <input class="quantity-product" type="text" name="number" value="1">
                                 <button class="btn-quantity btn-increase">
                                     <i class="icon icon-plus"></i>
                                 </button>
                             </div>
                             <a href="#shoppingCart" data-bs-toggle="offcanvas" class="btn-action-price tf-btn type-xl animate-btn w-100">
                                 Add to Cart
                                 <span class="d-none d-sm-block d-md-none d-lg-block">&nbsp;-&nbsp;</span>
                                 <span class="price-add d-none d-sm-block d-md-none d-lg-block">$59.99</span>
                             </a>
                         </div>
                         <a href="" class="tf-btn type-xl btn-primary animate-btn w-100">
                             Buy It Now
                         </a>
                     </div>
                 </div>
                 <div class="box-action">
                     <a href="" class="tf-btn-line-2 style-primary fw-semibold">
                         View Full Details
                     </a>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- /Quick View -->






 <!-- /Shopping Cart -->
 <!-- Search -->
 <div class="modal modalCentered fade modal-search" id="search">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="d-flex align-items-center justify-content-between gap-10">
                 <h3>
                     Search
                 </h3>
                 <span class="icon-close-popup flex-shrink-0" data-bs-dismiss="modal">
                     <i class="icon-X2"></i>
                 </span>
             </div>
             <form action="search-result.html" class="form-search-nav style-2">
                 <fieldset>
                     <input type="text" placeholder="Searching..." required="">
                 </fieldset>
                 <button type="submit" class="btn-action">
                     <i class="icon icon-MagnifyingGlass"></i>
                 </button>
             </form>
             <div class="search-feature">
                 <p class="h5 mb-16">Feature Keywords Today</p>
                 <div class="tf-list-tag">
                     <a href="#" class="link-tag">Dresses</a>
                     <a href="#" class="link-tag">Dresses women</a>
                     <a href="#" class="link-tag">Dresses midi</a>
                     <a href="#" class="link-tag">Dress summer</a>
                 </div>
             </div>
             <div class="recently-view">
                 <p class="h5 mb-16">Recently Viewed Products</p>
                 <div dir="ltr" class="swiper tf-swiper mb-24" data-preview="4" data-tablet="3" data-mobile-sm="2" data-mobile="2" data-space-lg="30" data-space-md="20" data-space="10" data-pagination="2" data-pagination-sm="2" data-pagination-md="3" data-pagination-lg="4">
                     <div class="swiper-wrapper">
                         <!-- slide 1 -->
                         <div class="swiper-slide">
                             <div class="card-product">
                                 <div class="card-product_wrapper">
                                     <a href="" class="product-img">
                                         <img class="img-product" loading="lazy" width="330" height="440" src="assets/images/product/product-1.jpg" alt="Product">
                                         <img class="img-hover" loading="lazy" width="330" height="440" src="assets/images/product/product-1_2.jpg" alt="Product">
                                     </a>
                                     <ul class="product-action_list">
                                         <li class="wishlist">
                                             <a href="#;" class="hover-tooltip tooltip-left box-icon">
                                                 <span class="icon icon-heart"></span>
                                                 <span class="tooltip">Add to Wishlist</span>
                                             </a>
                                         </li>
                                         <li class="compare">
                                             <a href="#compare" data-bs-toggle="offcanvas" class="hover-tooltip tooltip-left box-icon">
                                                 <span class="icon icon-ArrowsLeftRight"></span>
                                                 <span class="tooltip">Compare</span>
                                             </a>
                                         </li>
                                         <li>
                                             <a href="#quickView" data-bs-toggle="offcanvas" class="hover-tooltip tooltip-left box-icon">
                                                 <span class="icon icon-Eye"></span>
                                                 <span class="tooltip">Quick view</span>
                                             </a>
                                         </li>
                                     </ul>
                                     <ul class="product-badge_list">
                                         <li class="product-badge_item text-caption-01 new">NEW</li>
                                     </ul>
                                     <div class="product-action_bot">
                                         <a href="#quickAdd" data-bs-toggle="modal" class="tf-btn btn-white small  w-100">
                                             Quick Add
                                         </a>
                                     </div>
                                     <div class="product-marquee_sale">
                                         <div class="marquee-wrapper">
                                             <div class="initial-child-container">
                                                 <!-- 1 -->
                                                 <div class="marquee-child-item">
                                                     HOT SALE 25% OFF
                                                 </div>
                                                 <i class="icon icon-Star2"></i>
                                                 <!-- 2 -->
                                                 <div class="marquee-child-item">
                                                     HOT SALE 25% OFF
                                                 </div>
                                                 <i class="icon icon-Star2"></i>
                                                 <!-- 3 -->
                                                 <div class="marquee-child-item">
                                                     HOT SALE 25% OFF
                                                 </div>
                                                 <i class="icon icon-Star2"></i>
                                                 <!-- 4 -->
                                                 <div class="marquee-child-item">
                                                     HOT SALE 25% OFF
                                                 </div>
                                                 <i class="icon icon-Star2"></i>
                                                 <!-- 5 -->
                                                 <div class="marquee-child-item">
                                                     HOT SALE 25% OFF
                                                 </div>
                                                 <i class="icon icon-Star2"></i>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="card-product_info">
                                     <a href="" class="name-product lh-24 fw-medium link-underline-text">
                                         Lyocell wrap top
                                     </a>
                                     <div class="star-wrap d-flex align-items-center">
                                         <i class="icon icon-Star"></i>
                                         <i class="icon icon-Star"></i>
                                         <i class="icon icon-Star"></i>
                                         <i class="icon icon-Star"></i>
                                         <i class="icon icon-Star"></i>
                                     </div>
                                     <div class="price-wrap">
                                         <span class="price-new text-primary fw-semibold">$69,99</span>
                                         <span class="price-old text-caption-01 cl-text-3">$99,99</span>
                                     </div>
                                     <ul class="product-color_list">
                                         <li class="product-color-item color-swatch hover-tooltip tooltip-bot active">
                                             <span class="tooltip color-filter">Brown</span>
                                             <span class="swatch-value bg-warm-beige"></span>
                                             <img src="assets/images/product/product-1.jpg" data-src="assets/images/product/product-1.jpg" alt="Image">
                                         </li>
                                         <li class="product-color-item color-swatch hover-tooltip tooltip-bot">
                                             <span class="tooltip color-filter">Dark Blue</span>
                                             <span class="swatch-value bg-midnight-blue"></span>
                                             <img src="assets/images/product/product-1_3.jpg" data-src="assets/images/product/product-1_3.jpg" alt="Image">
                                         </li>
                                         <li class="product-color-item color-swatch hover-tooltip tooltip-bot">
                                             <span class="tooltip color-filter">White</span>
                                             <span class="swatch-value bg-white"></span>
                                             <img src="assets/images/product/product-1_4.jpg" data-src="assets/images/product/product-1_4.jpg" alt="Image">
                                         </li>
                                     </ul>
                                 </div>
                             </div>
                         </div>
                         <!-- slide 2 -->
                         <div class="swiper-slide">
                             <div class="card-product">
                                 <div class="card-product_wrapper">
                                     <a href="" class="product-img">
                                         <img class="img-product" loading="lazy" width="330" height="440" src="assets/images/product/product-2.jpg" alt="Product">
                                         <img class="img-hover" loading="lazy" width="330" height="440" src="assets/images/product/product-2_2.jpg" alt="Product">
                                     </a>
                                     <ul class="product-action_list">
                                         <li class="wishlist">
                                             <a href="#;" class="hover-tooltip tooltip-left box-icon">
                                                 <span class="icon icon-heart"></span>
                                                 <span class="tooltip">Add to Wishlist</span>
                                             </a>
                                         </li>
                                         <li class="compare">
                                             <a href="#compare" data-bs-toggle="offcanvas" class="hover-tooltip tooltip-left box-icon">
                                                 <span class="icon icon-ArrowsLeftRight"></span>
                                                 <span class="tooltip">Compare</span>
                                             </a>
                                         </li>
                                         <li>
                                             <a href="#quickView" data-bs-toggle="offcanvas" class="hover-tooltip tooltip-left box-icon">
                                                 <span class="icon icon-Eye"></span>
                                                 <span class="tooltip">Quick view</span>
                                             </a>
                                         </li>
                                     </ul>
                                     <ul class="product-badge_list">
                                         <li class="product-badge_item text-caption-01 sale">-25%</li>
                                     </ul>
                                     <div class="product-action_bot">
                                         <a href="#quickAdd" data-bs-toggle="modal" class="tf-btn btn-white small  w-100">
                                             Quick Add
                                         </a>
                                     </div>
                                 </div>
                                 <div class="card-product_info">
                                     <a href="" class="name-product lh-24 fw-medium link-underline-text">
                                         Buttons cotton top
                                     </a>
                                     <div class="star-wrap d-flex align-items-center">
                                         <i class="icon icon-Star"></i>
                                         <i class="icon icon-Star"></i>
                                         <i class="icon icon-Star"></i>
                                         <i class="icon icon-Star"></i>
                                         <i class="icon icon-Star"></i>
                                     </div>
                                     <div class="price-wrap">
                                         <span class="price-new text-primary fw-semibold">$29,99</span>
                                         <span class="price-old text-caption-01 cl-text-3">$49,99</span>
                                     </div>
                                     <ul class="product-color_list">
                                         <li class="product-color-item color-swatch hover-tooltip tooltip-bot active">
                                             <span class="tooltip color-filter">Brown</span>
                                             <span class="swatch-value bg-warm-brown"></span>
                                             <img src="assets/images/product/product-2.jpg" data-src="assets/images/product/product-2.jpg" alt="Image">
                                         </li>
                                         <li class="product-color-item color-swatch hover-tooltip tooltip-bot">
                                             <span class="tooltip color-filter">Beige</span>
                                             <span class="swatch-value bg-beige"></span>
                                             <img src="assets/images/product/product-2_3.jpg" data-src="assets/images/product/product-2_3.jpg" alt="Image">
                                         </li>
                                     </ul>
                                 </div>
                             </div>
                         </div>
                         <!-- slide 3 -->
                         <div class="swiper-slide">
                             <div class="card-product has-size">
                                 <div class="card-product_wrapper">
                                     <a href="" class="product-img">
                                         <img class="img-product" loading="lazy" width="330" height="440" src="assets/images/product/product-3.jpg" alt="Product">
                                         <img class="img-hover" loading="lazy" width="330" height="440" src="assets/images/product/product-3_2.jpg" alt="Product">
                                     </a>
                                     <ul class="product-action_list">
                                         <li class="wishlist">
                                             <a href="#;" class="hover-tooltip tooltip-left box-icon">
                                                 <span class="icon icon-heart"></span>
                                                 <span class="tooltip">Add to Wishlist</span>
                                             </a>
                                         </li>
                                         <li class="compare">
                                             <a href="#compare" data-bs-toggle="offcanvas" class="hover-tooltip tooltip-left box-icon">
                                                 <span class="icon icon-ArrowsLeftRight"></span>
                                                 <span class="tooltip">Compare</span>
                                             </a>
                                         </li>
                                         <li>
                                             <a href="#quickView" data-bs-toggle="offcanvas" class="hover-tooltip tooltip-left box-icon">
                                                 <span class="icon icon-Eye"></span>
                                                 <span class="tooltip">Quick view</span>
                                             </a>
                                         </li>
                                     </ul>
                                     <ul class="product-badge_list">
                                         <li class="product-badge_item text-caption-01 sale">-25%</li>
                                         <li class="product-badge_item text-caption-01 trend">TREND</li>
                                     </ul>
                                     <div class="variant-box">
                                         <ul class="product-size_list">
                                             <li class="size-item text-caption-01">XS</li>
                                             <li class="size-item text-caption-01">S</li>
                                             <li class="size-item text-caption-01">M</li>
                                         </ul>
                                     </div>
                                     <div class="product-action_bot">
                                         <a href="#quickAdd" data-bs-toggle="modal" class="tf-btn btn-white small  w-100">
                                             Quick Add
                                         </a>
                                     </div>
                                 </div>
                                 <div class="card-product_info">
                                     <a href="" class="name-product lh-24 fw-medium link-underline-text">
                                         Wool Midi Coat
                                     </a>
                                     <div class="star-wrap d-flex align-items-center">
                                         <i class="icon icon-Star"></i>
                                         <i class="icon icon-Star"></i>
                                         <i class="icon icon-Star"></i>
                                         <i class="icon icon-Star"></i>
                                         <i class="icon icon-Star"></i>
                                     </div>
                                     <div class="price-wrap">
                                         <span class="price-new text-primary fw-semibold">$15,99</span>
                                         <span class="price-old text-caption-01 cl-text-3">$25,99</span>
                                     </div>
                                     <ul class="product-color_list">
                                         <li class="product-color-item color-swatch hover-tooltip tooltip-bot active">
                                             <span class="tooltip color-filter">Brown</span>
                                             <span class="swatch-value bg-olive-brown"></span>
                                             <img src="assets/images/product/product-3.jpg" data-src="assets/images/product/product-3.jpg" alt="Image">
                                         </li>
                                         <li class="product-color-item color-swatch hover-tooltip tooltip-bot">
                                             <span class="tooltip color-filter">Blue</span>
                                             <span class="swatch-value bg-dark-blue"></span>
                                             <img src="assets/images/product/product-3_3.jpg" data-src="assets/images/product/product-3_3.jpg" alt="Image">
                                         </li>
                                         <li class="product-color-item color-swatch hover-tooltip tooltip-bot">
                                             <span class="tooltip color-filter">Light</span>
                                             <span class="swatch-value bg-warm-beige"></span>
                                             <img src="assets/images/product/product-3_4.jpg" data-src="assets/images/product/product-3_4.jpg" alt="Image">
                                         </li>
                                     </ul>
                                 </div>
                             </div>
                         </div>
                         <!-- slide 4 -->
                         <div class="swiper-slide">
                             <div class="card-product">
                                 <div class="card-product_wrapper">
                                     <a href="" class="product-img">
                                         <img class="img-product" loading="lazy" width="330" height="440" src="assets/images/product/product-4.jpg" alt="Product">
                                         <img class="img-hover" loading="lazy" width="330" height="440" src="assets/images/product/product-4_2.jpg" alt="Product">
                                     </a>
                                     <ul class="product-action_list">
                                         <li class="wishlist">
                                             <a href="#;" class="hover-tooltip tooltip-left box-icon">
                                                 <span class="icon icon-heart"></span>
                                                 <span class="tooltip">Add to Wishlist</span>
                                             </a>
                                         </li>
                                         <li class="compare">
                                             <a href="#compare" data-bs-toggle="offcanvas" class="hover-tooltip tooltip-left box-icon">
                                                 <span class="icon icon-ArrowsLeftRight"></span>
                                                 <span class="tooltip">Compare</span>
                                             </a>
                                         </li>
                                         <li>
                                             <a href="#quickView" data-bs-toggle="offcanvas" class="hover-tooltip tooltip-left box-icon">
                                                 <span class="icon icon-Eye"></span>
                                                 <span class="tooltip">Quick view</span>
                                             </a>
                                         </li>
                                     </ul>
                                     <ul class="product-badge_list">
                                         <li class="product-badge_item text-caption-01 new">NEW</li>
                                     </ul>
                                     <div class="product-action_bot">
                                         <a href="#quickAdd" data-bs-toggle="modal" class="tf-btn btn-white small  w-100">
                                             Quick Add
                                         </a>
                                     </div>
                                     <div class="product-countdown">
                                         <div class="js-countdown cd-has-zero" data-timer="1093120" data-labels="D : ,H : ,M : ,S">
                                         </div>
                                     </div>
                                 </div>
                                 <div class="card-product_info">
                                     <a href="" class="name-product lh-24 fw-medium link-underline-text">
                                         linen slim-fit shirt
                                     </a>
                                     <div class="star-wrap d-flex align-items-center">
                                         <i class="icon icon-Star"></i>
                                         <i class="icon icon-Star"></i>
                                         <i class="icon icon-Star"></i>
                                         <i class="icon icon-Star"></i>
                                         <i class="icon icon-Star"></i>
                                     </div>
                                     <div class="price-wrap">
                                         <span class="price-new text-primary fw-semibold">$45,99</span>
                                         <span class="price-old text-caption-01 cl-text-3">$79,99</span>
                                     </div>
                                     <ul class="product-color_list">
                                         <li class="product-color-item color-swatch hover-tooltip tooltip-bot active">
                                             <span class="tooltip color-filter">Blue</span>
                                             <span class="swatch-value bg-dark-blue-2"></span>
                                             <img src="assets/images/product/product-4.jpg" data-src="assets/images/product/product-4.jpg" alt="Image">
                                         </li>
                                     </ul>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="sw-dot-default tf-sw-pagination"></div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- /Search -->
 <!-- Register -->
 <div class="modal modalCentered fade modal-log" id="register">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <span class="icon-close-popup" data-bs-dismiss="modal">
                 <i class="icon-X2"></i>
             </span>
             <div class="modal-heading text-center">
                 <h3 class="title-pop mb-8">Create Account</h3>
                 <p class="desc-pop cl-text-2">Be part of our growing family of new customers!</p>
             </div>
             <div class="modal-main">
                 <form action="auth.php" method="post" class="form-log"><input type="hidden" name="action" value="register"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                     <div class="form-content">
                         <fieldset class="tf-field">
                             <label for="user-name" class="tf-lable fw-medium">Username or email address <span class="text-primary">*</span></label>
                             <input type="text" name="name" id="user-name" placeholder="Name*" required="">
                         </fieldset>
                         <fieldset class="tf-field password-wrapper">
                             <label for="register-password" class="tf-lable fw-medium">
                                 Password
                                 <span class="text-primary">*</span>
                             </label>
                             <div class="password-wrapper w-100">
                                 <span class="toggle-pass icon-EyeSlash fs-20 cl-text-3"></span>
                                 <input class="password-field" type="password" name="password" id="register-password" placeholder="Password" minlength="8" required="">
                             </div>
                         </fieldset>
                         <fieldset class="tf-field password-wrapper">
                             <label for="register-password-confirm" class="tf-lable fw-medium">
                                 Confirm Password
                                 <span class="text-primary">*</span>
                             </label>
                             <div class="password-wrapper w-100">
                                 <span class="toggle-pass icon-EyeSlash fs-20 cl-text-3"></span>
                                 <input class="password-field" type="password" name="password_confirmation" id="register-password-confirm" placeholder="Confirm Password" minlength="8" required="">
                             </div>
                         </fieldset>
                     </div>
                     <div class="group-action">
                         <button type="submit" class="action-create-account tf-btn animate-btn w-100">
                             Create Account
                         </button>
                         <a href="#sign" data-bs-toggle="modal" class="tf-btn btn-stroke">
                             Login
                         </a>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
 <!-- /Register -->
 <!-- Sign In -->
 <div class="modal modalCentered fade modal-log" id="sign">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <span class="icon-close-popup" data-bs-dismiss="modal">
                 <i class="icon-X2"></i>
             </span>
             <div class="modal-heading text-center">
                 <h3 class="title-pop mb-8">Sign In</h3>
                 <p class="desc-pop cl-text-2">Sign in to access your personalized experience.</p>
             </div>
             <div class="modal-main">
                 <form action="auth.php" method="post" class="form-log"><input type="hidden" name="action" value="login"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                     <div class="form-content">
                         <fieldset class="tf-field">
                             <label for="user-name-log" class="tf-lable fw-medium">Username or email address <span class="text-primary">*</span></label>
                             <input type="email" name="email" id="user-name-log" placeholder="Username or email address*" required="">
                         </fieldset>
                         <fieldset class="tf-field password-wrapper">
                             <label for="password" class="tf-lable fw-medium">
                                 Password
                                 <span class="text-primary">*</span>
                             </label>
                             <div class="password-wrapper w-100">
                                 <span class="toggle-pass icon-EyeSlash fs-20 cl-text-3"></span>
                                 <input class="password-field" type="password" name="password" id="password" placeholder="Password" required="">
                             </div>
                         </fieldset>
                         <fieldset class="field-bottom">
                             <div class="checkbox-wrap">
                                 <input class="tf-check style-2" type="checkbox" id="remember">
                                 <label for="remember">
                                     Remember me
                                 </label>
                             </div>
                             <a href="#modalForgot" data-bs-toggle="modal" class="link text-decoration-underline">
                                 <span class="text-caption-01 fw-semibold">
                                     Forgot Your Password?
                                 </span>
                             </a>
                         </fieldset>
                     </div>
                     <div class="group-action">
                         <button type="submit" class="tf-btn animate-btn w-100">
                             Login
                         </button>
                         <a href="register.php" class="tf-btn btn-stroke">
                             Create Account
                         </a>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
 <!-- /Sign In -->

 <!-- Newsletter -->
 <!-- <div class="modal modalCentered fade modal-newsletter auto-popup" id="newsletter">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="image-left">
                    <img loading="lazy" width="360" height="360" src="assets/images/section/banner-newsletter.jpg" alt="Image">
                </div>
                <div class="content-right">
                    <span class="icon-close-popup" data-bs-dismiss="modal">
                        <i class="icon-X2"></i>
                    </span>
                    <p class="h6 mb-8">Subscribe &amp; Enjoy</p>
                    <p class="h1 fw-medium mb-8 text-primary">
                        10% OFF
                    </p>
                    <p class="desc-pop">
                        Join our email list &amp; be first to Receive 10% OFF your next order, exclusive offers &amp; more!
                    </p>
                    <form class="form-newsletter mb-12">
                        <fieldset>
                            <input type="email" placeholder="Your email address" required="">
                        </fieldset>
                        <button type="submit" class="btn-action tf-btn small animate-btn">
                            Subscribe
                        </button>
                    </form>
                    <p class="text-caption-01 cl-text-2">
                        Don’t worry, we hate spam as much as you do
                    </p>
                </div>
            </div>
        </div>
    </div> -->
 <!-- /Newsletter -->

 <!-- Javascript -->
 <script src="assets/js/plugin/bootstrap.min.js"></script>
 <script src="assets/js/plugin/jquery.min.js"></script>
 <script src="assets/js/plugin/swiper-bundle.min.js"></script>
 <script src="assets/js/plugin/bootstrap-select.min.js"></script>
 <script src="assets/js/plugin/count-down.js"></script>
 <script src="assets/js/plugin/infinityslide.js"></script>
 <script src="assets/js/plugin/wow.min.js"></script>


 <script src="assets/js/carousel.js"></script>
 <script src="assets/js/main.js"></script>