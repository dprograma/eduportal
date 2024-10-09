<!doctype html>
<html lang="en">

<?php $title = "EduPortal | Reset Password"; ?>
<?php include "partials/head.php"; ?>

   <body>
      <main>
         <!--Pageheader start-->
         <div class="position-relative h-100">
            <div class="container d-flex flex-wrap justify-content-center align-items-center vh-100 w-lg-50 position-lg-absolute">
               <div class="row justify-content-center">
                  <div class="w-100 align-self-end col-12">
                     <div class="text-center mb-7">
                        <a href="index.html"><img src="assets/images/logo/brand-icon.svg" alt="brand" /></a>
                        <h1 class="mb-1">Set new password</h1>
                        <p class="mb-0">No worries, we will send you reset instruction.</p>
                     </div>
                     <?php if(isset($_GET['error'])): ?>
                     <div class="alert alert-<?=$_GET['type']?> alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_GET['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                  <?php endif; ?>
                     <form method="post" class="needs-validation">
                        <div class="mb-3">
                           <label for="newPassword" class="form-label">New Password</label>
                           <div class="password-field position-relative">
                              <input type="password" class="form-control fakePassword" name="newPassword" id="newPassword" required />
                              <span><i class="bi bi-eye-slash passwordToggler"></i></span>
                              <div class="invalid-feedback">Please enter password.</div>
                           </div>
                        </div>
                        <div class="mb-3">
                           <label for="confirmPassword" class="form-label">Confirm Password</label>
                           <div class="password-field position-relative">
                              <input type="password" class="form-control fakePassword" name="confirmPassword" id="confirmPassword" required />
                              <span><i class="bi bi-eye-slash passwordToggler"></i></span>
                              <div class="invalid-feedback">Please enter password.</div>
                           </div>
                        </div>

                        <div class="d-grid mb-4">
                           <button class="btn btn-primary" type="submit" name="reset-password">Reset Password</button>
                        </div>
                        <div class="text-center">
                           <a href="reset-password-v2.html" class="icon-link icon-link-hover">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                 <path
                                    fill-rule="evenodd"
                                    d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                              </svg>
                              <span>Back to Login</span>
                           </a>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
            <div
               class="position-fixed top-0 end-0 w-50 h-100 d-none d-xl-block vh-100"
               style="background-image: url(assets/images/sign-in/authentication-img.jpg); background-position: center; background-repeat: no-repeat; background-size: cover"></div>
         </div>
         <!--Pageheader end-->
         <!--Reset password v2 start-->
         <div class="position-absolute start-0 bottom-0 m-4">
            <div class="dropdown">
               <button class="btn btn-light btn-icon rounded-circle d-flex align-items-center" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
                  <i class="bi theme-icon-active"></i>
                  <span class="visually-hidden bs-theme-text">Toggle theme</span>
               </button>
               <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bs-theme-text">
                  <li>
                     <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                        <i class="bi theme-icon bi-sun-fill"></i>
                        <span class="ms-2">Light</span>
                     </button>
                  </li>
                  <li>
                     <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                        <i class="bi theme-icon bi-moon-stars-fill"></i>
                        <span class="ms-2">Dark</span>
                     </button>
                  </li>
                  <li>
                     <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                        <i class="bi theme-icon bi-circle-half"></i>
                        <span class="ms-2">Auto</span>
                     </button>
                  </li>
               </ul>
            </div>
         </div>
         <!--Reset password v2 end-->
      </main>

      <?php include "partials/scripts.php"; ?>
   </body>

</html>
