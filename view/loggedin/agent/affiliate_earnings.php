<!doctype html>
<html lang="en">
<?php $title = "EduPortal | Affiliate Earnings"; ?>
<?php include "partials/head.php"; ?>

<body>
    <!-- Navbar -->
    <?php include "partials/admin_header.php"; ?>

    <main>
        <section class="py-lg-7 py-5 bg-light-subtle">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <?php include "partials/affiliate_menu.php"; ?>
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <?php include "partials/affiliate_earnings_content.php"; ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'partials/footer.php'; ?>
    <?php include 'partials/scroll_top.php'; ?>
    <?php include 'partials/scripts.php'; ?>
    <script src="assets/js/affiliate.js"></script>
</body>
</html> 