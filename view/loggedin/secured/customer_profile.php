<!doctype html>
<html lang="en">
<?php $title = "EduPortal | Profile"; ?>
<?php include "partials/head.php"; ?>

<body>
    <!-- Navbar -->
    <?php
    if ($currentUser->access == 'admin') {
        include "partials/admin_header.php";
    } else if ($currentUser->is_agent == '1') {
        include "partials/agent_header.php";
    } else {
        include "partials/customer_header.php";
    }
    ?>

    <main>
        <!-- Account Publications start -->
        <section class="py-lg-7 py-5 bg-light-subtle">
            <div class="container">
                <div class="row">
                        <?php
                        if ($currentUser->access == 'admin') {
                            include "partials/admin_menu.php";
                        } else if ($currentUser->is_agent == '1') {
                            include "partials/agent_menu.php";
                        } else {
                            include "partials/customer_menu.php";
                        }
                        ?>
                        <?php include 'partials/customer_profile_content.php'; ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- Account Ebooks end -->
    </main>

    <?php include 'partials/footer.php'; ?>
    <!-- Scroll top -->
    <?php include 'partials/scroll_top.php'; ?>
    <!-- Libs JS -->
    <?php include 'partials/scripts.php'; ?>
</body>

</html>