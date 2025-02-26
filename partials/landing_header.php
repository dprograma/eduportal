<style>
   .carttip {
      color: white;
      font-size: 10px;
      line-height: 18px;
      background-color: #9519ba;
      width: 16px;
      height: 16px;
      border-radius: 4px;
      transform: translate(-12px, 0px);
   }

   @media (max-width: 768px) {
      .carttip {
         background-color: #9519ba;
         transform: translate(32px, -40px);
      }
   }
</style>
<?php
    if (! isset($pdo)) {
        global $pdo;
    }

    $cart_count = 0;
    if (isset($_COOKIE['cart'])) {
        // Unserialize the cart data from the cookie
        $carts = unserialize($_COOKIE['cart']);
        foreach ($carts as $key => $cart) {
            $cart_count = $cart_count + $cart['quantity'];
        }
    }

    // No need to query for currentUser since it should be passed from the controller
?>


<header>
<!-- Search Overlay -->
 <?php include 'search_overlay.php'; ?>
   <nav class="navbar navbar-expand-lg transparent navbar-transparent navbar-dark">
      <div class="container-fluid px-3">
         <a class="navbar-brand" href="home">
            <h3><span class="text-primary">Edu</span><span>Portal</span></h3>
         </a>
         <button class="navbar-toggler offcanvas-nav-btn" type="button">
            <i class="bi bi-list"></i>
         </button>
         <div class="offcanvas offcanvas-start offcanvas-nav" style="width: 20rem">
            <div class="offcanvas-header">
               <a href="landing.php" class="text-inverse">
                  <h3><span class="text-primary">Edu</span><span>Portal</span></h3>
               </a>
               <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body pt-0 align-items-center">
               <ul class="navbar-nav mx-auto align-items-lg-center">
                  <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Products
                     </a>
                     <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="publications">Publications</a></li>
                        <li>
                           <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="ebooks">Ebooks</a></li>
                        <li>
                           <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="past-questions">Past Questions</a></li>
                     </ul>
                  </li>

                  <!-- <li class="nav-item dropdown">
                      <a class="nav-link" href="publications" role="button" aria-expanded="false">Publications</a>
                   </li>
                   <li class="nav-item dropdown dropdown-fullwidth">
                      <a class="nav-link" href="ebooks" role="button" aria-expanded="false">Ebooks</a>
                   </li>
                   <li class="nav-item dropdown">
                      <a class="nav-link" href="past-questions" role="button" aria-expanded="false">Past Questions</a>
                   </li> -->
                  <li class="nav-item dropdown">
                     <a class="nav-link" href="cbt-test" id="navbarDropdown" role="button" aria-haspopup="true"
                        aria-expanded="false">CBT Test</a>
                  </li>
                  <li class="nav-item dropdown">
                     <a class="nav-link" href="news" id="navbarDropdown" role="button" aria-haspopup="true"
                        aria-expanded="false">News</a>
                  </li>
                  <li class="nav-item dropdown">
                     <a class="nav-link btn btn-primary" href="affiliate-signup?ref=affiliate" id="navbarDropdown"
                        role="button" aria-haspopup="true" aria-expanded="false">Become an Affiliate</a>
                  </li>
                  <li class="nav-item dropdown">
                     <a class="nav-link btn btn-primary" href="agent-signup" id="navbarDropdown" role="button"
                        aria-haspopup="true" aria-expanded="false">Become an Agent</a>
                  </li>
                  <!-- Add this in your navbar, perhaps next to the cart icon -->

               </ul>

               
<div class="navbar-nav ms-lg-4">
    <button class="nav-link p-0 me-3" onclick="showSearchModal()">
        <i class="bi bi-search fs-5"></i>
    </button>
    <a class="nav-item nav-link" href="cart">
        <img src="<?php echo CART ?>" class="cart-icon" alt="cart" style="width: 32px; height: 32px; margin-right: 5px;" />
    </a>
    <?php if ($cart_count > 0): ?>
        <div class="text-center carttip"><?php echo $cart_count ?></div>
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
                        aria-expanded="false">
                        Hi,                                                       <?php echo $currentUser->fullname ?>
                     </a>
                     <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item text-center"
                              href="<?php echo $currentUser->access == 'admin' ? 'admin-dashboard' : ($currentUser->is_agent == '1' ? 'agent-dashboard' : 'dashboard'); ?>">Dashboard</a>
                        </li>
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

