<!doctype html>
<html lang="en">

<?php $title = "EduPortal | Agent Signup"; ?>
<?php include "partials/head.php" ?>
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
                     <h1 class="mb-1">Become an Agent</h1>
                     <p class="mb-0">Post Ebooks, Publications and Past-Questions. 
                        <a href="login" class="text-primary">Sign in</a>
                     </p>
                  </div>

                  <?php if (isset($_GET['error'])): ?>
                     <div class="alert alert-<?= $_GET['type'] ?> alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_GET['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                  <?php endif; ?>

                  <form method="post" action='agent-signup' class="needs-validation mb-6" novalidate>
                     <div class="mb-3">
                        <label for="username" class="form-label">
                           Username
                           <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="username" id="username"   value="<?= isset($old['UserName']) ? htmlspecialchars($old['UserName']) : ''; ?>" required />
                        <div class="invalid-feedback">Please enter username.</div>
                     </div>
                     <div class="mb-3">
                        <label for="name" class="form-label">
                           Full Name
                           <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="name" id="name"  value="<?= isset($old['FullName']) ? htmlspecialchars($old['FullName']) : ''; ?>" required />
                        <div class="invalid-feedback">Please enter Full name.</div>
                     </div>
                     <div class="mb-3">
                        <label for="signupEmailInput" class="form-label">
                           Email
                           <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control" name="email" id="email"  value="<?= isset($old['Email']) ? htmlspecialchars($old['Email']) : ''; ?>" required />
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
                        <button class="btn btn-primary" type="submit" name="register_agent">Sign Up</button>
                     </div>
                  </form>

                  
                  <div class="text-center mt-7">
                     <div class="small mb-3 mb-lg-0 text-body-tertiary">
                        Copyright © <?= date('Y') ?>
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
        
      </div>
   </main>
   <?php include "partials/scripts.php"; ?>
</body>

</html>