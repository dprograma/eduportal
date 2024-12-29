<!doctype html>
<html lang="en">
<?php $title = "EduPortal | Purchases"; ?>
<?php include "partials/head.php"; ?>

<body>
    <!-- Navbar -->
    <?php include "partials/customer_header.php"; ?>

    <main>
        <!--Account home start-->
        <section class="py-lg-7 py-5 bg-light-subtle">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <?php
                        if ($currentUser->is_affiliate) {
                            include 'partials/affiliate_menu.php';
                        } else {
                            include 'partials/customer_menu.php';
                        }
                        ?>
                    </div>
                    <div class="col-lg-9 col-md-8">

                        <?php include 'partials/customer_purchases_content.php'; ?>

                    </div>
                </div>
            </div>
        </section>
        <!--Account home end-->
    </main>

    <?php include 'partials/footer.php'; ?>
    <!-- Scroll top -->
    <?php include 'partials/scroll_top.php'; ?>
    <!-- Libs JS -->
    <?php include 'partials/scripts.php'; ?>
</body>

</html>