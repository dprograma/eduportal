<!doctype html>
<html lang="en">

<?php $title = "EduPortal | Signup"; ?>
<?php include "partials/head.php"?>
<?php
    $old = $_SESSION['old_values'] ?? [];
    unset($_SESSION['old_values']);
?>

<body>
   <main>
      <!--Pageheader start-->
      <div class="position-relative h-100">
         <div
            class="container d-flex flex-wrap justify-content-center align-items-center vh-100 w-lg-50 position-lg-absolute">
            <div class="row justify-content-center">
               <div class="w-100 align-self-end col-12">
                  <div class="text-center mb-7">
                     <a href="home">
                        <h3 class="my-4"><span class="text-primary">Edu</span><span>Portal</span></h3>
                     </a>
                     <h1 class="mb-1">Create Account</h1>
                     <p class="mb-0">
                        Sign up now and get free account instant. Already
                        <a href="login" class="text-primary">Sign in</a>
                     </p>
                  </div>

                  <?php if (isset($_GET['error'])): ?>
                  <?php
                     $alertClass = 'alert-' . ($_GET['type'] === 'success' ? 'success' : 'danger');
                     $message    = htmlspecialchars($_GET['error']);
                  ?>
                     <div class="alert <?php echo $alertClass ?> alert-dismissible fade show" role="alert">
                        <?php echo $message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                  <?php endif; ?>

                     <?php

                     // Check for a flash message in the session
                     if (isset($_SESSION['flash_message'])) {
                        $flashMessage = $_SESSION['flash_message']['message'];
                        $flashType    = $_SESSION['flash_message']['type'];

                        // Display the Bootstrap alert
                        $alertClass = 'alert-' . ($flashType === 'success' ? 'success' : 'danger');
                        echo "<div class='alert $alertClass alert-dismissible fade show' role='alert'>
                                 $flashMessage
                                 <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                              </div>";

                        // Clear the flash message from the session
                        unset($_SESSION['flash_message']);
                     }
                     ?>

                  <form method="post" class="needs-validation mb-6" novalidate>
                     <div class="mb-3">
                        <label for="username" class="form-label"></label></label>
                           Username
                           <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="username" id="username"
                               value="<?= isset($old['UserName']) ? htmlspecialchars($old['UserName']) : ''; ?>" required />
                        <div class="invalid-feedback">Please enter username.</div>
                     </div>
                     <div class="mb-3">
                        <label for="name" class="form-label">
                           Full Name
                           <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="name" id="name"
                               value="<?= isset($old['FullName']) ? htmlspecialchars($old['FullName']) : ''; ?>" required />
                        <div class="invalid-feedback">Please enter Full name.</div>
                     </div>
                     <div class="mb-3">
                        <label for="signupEmailInput" class="form-label">
                           Email
                           <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control" name="email" id="email"
                               value="<?= isset($old['Email']) ? htmlspecialchars($old['Email']) : ''; ?>" required />
                        <div class="invalid-feedback">Please enter email.</div>
                     </div>
                     <div class="mb-3">
                        <label for="formSignUpPassword" class="form-label">Password</label>
                        <div class="password-field position-relative">
                           <input type="password" class="form-control fakePassword" name="password" id="password"
                              required />
                           <span><i class="bi bi-eye-slash passwordToggler"></i></span>
                           <div class="invalid-feedback">Please enter password.</div>
                        </div>
                     </div>
                     <div class="mb-3">
                        <label for="formSignUpConfirmPassword" class="form-label">Confirm Password</label>
                        <div class="password-field position-relative">
                           <input type="password" class="form-control fakePassword" name="confirm-password"
                              id="confirm-password" required />
                           <span><i class="bi bi-eye-slash passwordToggler"></i></span>
                           <div class="invalid-feedback">Please enter password.</div>
                        </div>
                     </div>
                     <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="terms-condition"
                                 id="terms-condition" required />
                              <label class="form-check-label ms-2" for="signupCheckTextCheckbox">
                                 <a href="#">Terms of Use</a>
                                 &
                                 <a href="#">Privacy Policy</a>
                              </label>
                           </div>
                        </div>
                     </div>
                     <?php if (isset($_GET['ref']) && $_GET['ref'] == 'affiliate' || isset($old['affiliate'])): ?>
                        <div class="alert alert-info">
                           <p><strong>Affiliate Registration Fee:</strong> ₦3,000 (one-time payment)</p>
                           <p class="small mb-0">
                              Become an affiliate and earn:
                              <br>- 65% commission on all purchases made by your referrals for 3 months
                              <br>- Additional commissions from your uploaded materials
                           </p>
                        </div>
                     <?php endif; ?>
                     <div class="mb-3">
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" name="affiliate" id="affiliate"
                                  <?php echo(isset($_GET['ref']) && $_GET['ref'] == 'affiliate' ||  isset($old['affiliate'])) ? 'checked' : ''; ?>>
                           <label class="form-check-label" for="affiliate">
                              Register as an Affiliate (₦3,000 one-time fee + 65% referral commission)
                           </label>
                        </div>
                     </div>

                     <div class="d-grid">
                        <button class="btn btn-primary" type="submit" name="register">Sign Up</button>
                     </div>
                  </form>

                  <span>Sign up with your social network.</span>
                  <div class="d-grid mt-3">
                     <a href="google-login" class="btn btn-google">
                        <span class="me-3">
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                              class="bi bi-google" viewBox="0 0 16 16">
                              <path
                                 d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                           </svg>
                        </span>
                        Continue with Google
                     </a>
                  </div>
                  <div class="d-grid mt-2">
                     <a href="facebook-login" class="btn btn-facebook">
                        <span class="me-3">
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                              class="bi bi-facebook" viewBox="0 0 16 16">
                              <path
                                 d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                           </svg>
                        </span>
                        Continue with Facebook
                     </a>
                  </div>

                  <div class="text-center mt-7">
                     <div class="small mb-3 mb-lg-0 text-body-tertiary">
                        Copyright ©                                                                                                                                                 <?php echo date('Y') ?>
                        <span class="text-primary"><a href="home">EduPortal Educational Platform</a></span>
                        | All Rights Reserved
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="position-fixed top-0 end-0 w-50 h-100 d-none d-lg-block vh-100"
            style="background-image: url(assets/images/sign-in/authentication-img.jpg); background-position: center; background-repeat: no-repeat; background-size: cover">
         </div>
      </div>
      <!--Pageheader end-->
      <!--sign up v2-->
      <div class="position-absolute start-0 bottom-0 m-4">
         <!-- <div class="dropdown">
            <button class="btn btn-light btn-icon rounded-circle d-flex align-items-center" type="button"
               aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
               <i class="bi theme-icon-active"></i>
               <span class="visually-hidden bs-theme-text">Toggle theme</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bs-theme-text">
               <li>
                  <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light"
                     aria-pressed="false">
                     <i class="bi theme-icon bi-sun-fill"></i>
                     <span class="ms-2">Light</span>
                  </button>
               </li>
               <li>
                  <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark"
                     aria-pressed="false">
                     <i class="bi theme-icon bi-moon-stars-fill"></i>
                     <span class="ms-2">Dark</span>
                  </button>
               </li>
               <li>
                  <button type="button" class="dropdown-item d-flex align-items-center active"
                     data-bs-theme-value="auto" aria-pressed="true">
                     <i class="bi theme-icon bi-circle-half"></i>
                     <span class="ms-2">Auto</span>
                  </button>
               </li>
            </ul>
         </div> -->
      </div>
   </main>
   <?php include "partials/scripts.php"; ?>
</body>

</html>