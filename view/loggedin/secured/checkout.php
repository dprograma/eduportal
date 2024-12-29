<?php require_once APP_ROOT . '/view/partials/secured-header.php' ?>
</head>

<body>


    <?php require_once APP_ROOT . '/view/partials/secured-nav.php' ?>

    <?php 
        if (isset($_SESSION['document_id'])) {
            $document_id = $_SESSION['document_id'];
        }
        if (isset($_SESSION['price'])) {
            $price = $_SESSION['price'];
        }
        if (isset($_SESSION['subject'])) {
            $subject = $_SESSION['subject'];
        }
        if (isset($_SESSION['exam_body'])) {
            $exam_body = $_SESSION['exam_body'];
        }
        if (isset($_SESSION['year'])) {
            $year = $_SESSION['year'];
        }
        if (isset($_SESSION['quantity'])) {
            $quantity = $_SESSION['quantity'];
        }
        if (isset($_SESSION['coverpage'])) {
            $coverpage = $_SESSION['coverpage'];
        }
    ?>

    <div class="container">
        <div class="row justify-content-center" style="height: 100vh; margin-top: 120px;">
            <div class="card" style="width: 20rem; padding-top: 10px; height: 550px;">
                <img src="<?= $coverpage ?>" class="card-img-top img-thumbnail" alt="<?= $subject ?> past question" style="height: 280px;">
                <div class="card-body" style="height: auto;">
                    <h6 class="card-subtitle mb-2 text-muted"><?= ucwords($subject) ?> (<?= $exam_body ?>) Past Question</h6>
                    <p class="card-text text-muted">Subtotal<span class="fa-pull-right">₦<?= $price ?></span></p>
                    <p class="card-text">Quantity<span class="fa-pull-right"><?= $quantity ?></span></p>
                    <p class="card-text text-muted">Total<span class="fa-pull-right text-success">₦<?= $price ?></span></p>
                    <form action="checkout-past-q" method="POST">
                        <input type="hidden" name="price" value="<?= $price ?>">
                        <input type="hidden" name="email" value="<?= $currentUser->email ?>">
                        <input type="submit" name="make-payment" class="btn btn-primary form-control" value="Checkout">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>