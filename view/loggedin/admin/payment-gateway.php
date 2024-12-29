<?php require_once APP_ROOT . '/view/partials/admin-header.php'?>

</head>
<body>
<?php require_once APP_ROOT . '/view/partials/admin_sidebar.php'?>

                        
                  
                        <div class="card-body">
          <h4 class="mb-2 text-uppercase">payment settings🔒</h4>
         
          <form id="" class="mb-3"  method="POST">
          <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-<?=$_GET['type']?>" role="alert"><?=$_GET['error']?></div>
    <?php endif; ?>
          <div class="mb-3 form-password-toggle">
              <div class="input-group input-group-merge">
                <div class="form-floating form-floating-outline">
                  <input type="text" id="amount" class="form-control" name="amount" placeholder="Enter Amount" aria-describedby="amount" />
                  <label for="amount">You Set Yearly Payment to ₦ <?= $currentUser->amount?></label>
                </div>
                <span class="input-group-text cursor-pointer"><i class="fas fa-money-check"></i></span>
              </div>
            </div>
          
            <div class="mb-3 form-password-toggle">
              <div class="input-group input-group-merge">
                <div class="form-floating form-floating-outline">
                  <input type="text" id="secretKey" class="form-control" name="secretKey" placeholder="Enter Secret Key" aria-describedby="secretKey" />
                  <label for="secretKey">You Set Your Live Secret Key to  <?= $currentUser->secretKey?></label>
                </div>
                <span class="input-group-text cursor-pointer"><i class="fa-solid fa-lock text-white"></i></span>
              </div>
            </div>
            <div class="mb-3 form-password-toggle">
              <div class="input-group input-group-merge">
                <div class="form-floating form-floating-outline">
                  <input type="text" id="publicKey" class="form-control" name="publicKey" placeholder="Enter Public Key" aria-describedby="publicKey" />
                  <label for="publicKey">You Set Your Live Public Key to  <?= $currentUser->publicKey?></label>
                </div>
                <span class="input-group-text cursor-pointer"><i class="fa-solid fa-lock text-white"></i></span>
              </div>
            </div>
            <input type="hidden" name="payment-gateway">
            <button type="submit" class="btn btn-primary d-grid w-100 mb-3">
            Save
            </button>
           
          </form>
        </div>
                     
                    </div>
                    <?php require_once APP_ROOT . '/view/partials/admin-footer.php'?>
