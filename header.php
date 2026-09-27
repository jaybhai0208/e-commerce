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


<!-- Scroll Top -->
<button id="goTop">
    <span class="border-progress"></span>
    <span class="ic-wrap">
        <span class="icon icon-CaretTopThin"></span>
    </span>
</button>



<main id="wrapper">

    <!-- Header -->
    <header class="tf-header header-s2 scr-box-shadow" style="background-color: antiquewhite;">
        <div class="container-full">
            <div class="header-inner">
                <div class="box-open-menu-mobile d-xl-none">
                    <a href="#mobileMenu" data-bs-toggle="offcanvas" class="btn-open-menu">
                        <i class="icon icon-List"></i>
                    </a>
                </div>
                <div class="header-left d-none d-xl-block">
                    <nav class="box-navigation">
                        <ul class="box-nav-menu">
                            <li class="menu-item position-relative">
                                <a href="index.php" class="item-link">
                                    <span class="text cus-text">
                                        Home
                                    </span>
                                </a>

                            </li>

                            <li class="menu-item">
                                <a href="about-us.php" class="item-link">
                                    <span class="text cus-text">
                                        About us
                                    </span></a>


                            </li>
                            <li class="menu-item position-relative">
                                <a href="#" class="item-link">
                                    <span class="text cus-text">
                                        Products
                                    </span>
                                    <i class="icon icon-CaretDown"></i>
                                </a>
                                <div class="sub-menu mega-menu-item">
                                    <ul class="sub-menu_list">
                                        <?php foreach ($headerProducts as $hp): ?>
                                            <li><a href="product.php?id=<?= e($hp['id']) ?>" class="sub-menu_link has-text"><span class="cus-text"><?= e($hp['name']) ?></span></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </li>
                            <li class="menu-item position-relative">
                                <a href="view-order.php" class="item-link"><span class="text cus-text">View Order</span></a>
                            </li>
                            <li class="menu-item position-relative">
                                <a href="contact-us.php" class="item-link">
                                    <span class="text cus-text">
                                        Contact US
                                    </span>

                                </a>

                            </li>

                        </ul>
                    </nav>
                </div>
                <div class="header-center ">
                    <a href="index.php" class="logo-site">
                        <img loading="lazy" width="150" height="30" src="assets/images/logo/main-logo.svg" alt="Image">
                    </a>
                </div>
                <div class="header-right">

                    <div class="br-line type-vertical d-none d-xxl-flex"></div>
                    <ul class="nav-icon-list">

                        <li>
                            <a href="<?= $headerUser ? 'logout.php' : '#sign' ?>"
                                <?= $headerUser ? 'onclick="return confirm(\'Are you sure you want to logout?\');"' : 'data-bs-toggle="modal"' ?>
                                class="nav-icon-item link">
                                <i class="icon icon-User"></i>
                            </a>
                        </li>

                        <li>
                            <a href="view-cart.php" class="nav-icon-item link shop-cart">
                                <i class="icon icon-Handbag"></i>
                                <span class="count"><?= e($headerCartCount) ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <!-- /Header -->