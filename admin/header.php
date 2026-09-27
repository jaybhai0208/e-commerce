<?php
// Session already active hai to dobara start nahi hoga
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// User name safely get karo
$userName = $_SESSION['auth_user']['user_name'] ?? 'Not Logged In';
?>

<nav class="app-header navbar navbar-expand bg-body">

    <!--begin::Container-->
    <div class="container-fluid">

        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">

            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>

            <li class="nav-item d-none d-md-block">
                <a href="index.php" class="nav-link">Home</a>
            </li>

            <li class="nav-item d-none d-md-block">
                <a href="#" class="nav-link">Contact</a>
            </li>

        </ul>
        <!--end::Start Navbar Links-->


        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">

            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">

                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">

                    <span class="d-none d-md-inline">
                        <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?>
                    </span>

                </a>


                <!--begin::Dropdown Menu-->
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">

                    <!--begin::User Header-->
                    <li class="user-header text-bg-primary">

                        <p>
                            <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?>
                        </p>

                    </li>
                    <!--end::User Header-->


                    <!--begin::Menu Body-->
                    <li class="user-body">
                        <!-- Yahan future mein user information add kar sakte ho -->
                    </li>
                    <!--end::Menu Body-->


                    <!--begin::Menu Footer-->
                    <li class="user-footer d-flex justify-content-between align-items-center w-100">

                        <!-- Logout -->
                        <form action="code.php" method="POST" class="m-0">

                            <button
                                type="submit"
                                name="logout_btn"
                                class="btn btn-danger btn-sm">
                                Logout
                            </button>

                        </form>


                        <!-- New Register -->
                        <a
                            href="register-page2.php"
                            class="btn btn-primary btn-sm">
                            New Register
                        </a>

                    </li>
                    <!--end::Menu Footer-->

                </ul>
                <!--end::Dropdown Menu-->

            </li>
            <!--end::User Menu Dropdown-->

        </ul>
        <!--end::End Navbar Links-->

    </div>
    <!--end::Container-->

</nav>