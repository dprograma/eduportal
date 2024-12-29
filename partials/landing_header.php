<?php

$cart_count = 0;
if (isset($_COOKIE['cart'])) {
    // Unserialize the cart data from the cookie
    $carts = unserialize($_COOKIE['cart']);
    foreach ($carts as $key => $cart) {
        $cart_count = $cart_count + $cart['quantity'];
    }
}

$currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));
?>
<header>
    <nav class="navbar navbar-expand-lg transparent navbar-transparent navbar-dark">
       <div class="container-fluid px-3">
          <a class="navbar-brand" href="home"><h3><span class="text-primary">Edu</span><span>Portal</span></h3></a>
          <button class="navbar-toggler offcanvas-nav-btn" type="button">
             <i class="bi bi-list"></i>
          </button>
          <div class="offcanvas offcanvas-start offcanvas-nav" style="width: 20rem">
             <div class="offcanvas-header">
                <a href="landing.php" class="text-inverse"><h3><span class="text-primary">Edu</span><span>Portal</span></h3></a>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
             </div>
             <div class="offcanvas-body pt-0 align-items-center">
                <ul class="navbar-nav mx-auto align-items-lg-center">
                   <li class="nav-item dropdown">
                      <a class="nav-link" href="publications" role="button" aria-expanded="false">Publications</a>
                   </li>
                   <li class="nav-item dropdown dropdown-fullwidth">
                      <a class="nav-link" href="ebooks" role="button" aria-expanded="false">Ebooks</a>
                   </li>
                   <li class="nav-item dropdown">
                      <a class="nav-link" href="past-questions" role="button" aria-expanded="false">Past Questions</a>
                   </li>
                   <li class="nav-item dropdown">
                      <a class="nav-link" href="cbt-test" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">CBT Test</a>
                   </li>
                   <li class="nav-item dropdown">
                      <a class="nav-link btn btn-primary" href="affiliate-signup?ref=affiliate" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">Become an Affiliate</a>
                   </li>
                </ul>
                <div class="navbar-nav ms-lg-4">
                <a class="nav-item nav-link" href="cart"><img src="<?= CART ?>" class="cart-icon" alt="cart" style="width: 32px; height: 32px; margin-right: 5px;" /></a>
                <?php if ($cart_count > 0): ?>
                    <span class="text-center"
                        style="color:white; font-size: 10px; line-height: 18px; background-color: #381866; min-width: 16px; height: 16px; border-radius: 4px; transform: translate(-12px, 0px);"><?= $cart_count ?></span>
                <?php endif; ?>
            </div>
            <?php if (empty($currentUser)): ?>
               <div class="mt-3 mt-lg-0 d-flex align-items-center">
                   <a href="login" class="btn btn-light mx-2">Login</a>
                   <a href="signup" class="btn btn-primary">Create account</a>
                </div>
            <?php else: ?>
                <li class="nav-item dropdown" style="color: white; list-style: none;">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false" >
                        Hi, <?= $currentUser->fullname ?>
                    </a>
                    <ul class="dropdown-menu"
                        style="360px; border-radius: 2px; transform: translate(-60px, 0px); padding-left: 10px; padding-right: 10px;">
                        <li><a class="dropdown-item" href="dashboard">Dashboard</a></li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li><a class="dropdown-item" href="logout">Logout</a></li>
                    </ul>
                </li>
            <?php endif; ?>
             </div>
          </div>
       </div>
    </nav>
 </header>