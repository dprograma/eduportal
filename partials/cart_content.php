<?php
$carts = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : [];
$cart_count = 0;
$btn = "color: #fff; background-color: #347054; border-color: #347054;font-size: 12px; padding: 5px;";
foreach($carts as $key => $cart){  
    $cart_count = $cart_count + $cart['quantity'];
}
?>

<?php if (!empty($carts)): $total = 0; ?>
<section class="h-100">
  <div class="container py-5">
  <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-<?=$_GET['type']?>" role="alert"><?=$_GET['error']?></div>
        <?php endif; ?>
    <div class="row d-flex justify-content-center my-4">
      <div class="col-md-8">
        <div class="card mb-4">
          <div class="card-header py-3">
            <h5 class="float-start mb-0">Cart - <?=$cart_count ?> items</h5>
            <form action="cart" method="POST" class="float-end"><button type="submit" title="Clear cart" class="btn btn-danger btn-sm p-1" style="font-size: 11px;" name="action" value="clear">Clear cart</button></form>
          </div>
          <?php  foreach ($carts as $productId => $product):
        $subTotal = $product['price'] * $product['quantity'];
        $total += $subTotal; ?>

          <div class="card-body">
            <!-- Single item -->
            <div class="row">
              <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                <!-- Image -->
                <div class="bg-image hover-overlay hover-zoom ripple rounded" data-mdb-ripple-color="light">
                  <img src="<?=$product['coverpage']?>"
                    class="w-100" style="height: 180px;"/>
                  <a href="#!">
                    <div class="mask" style="background-color: rgba(251, 251, 251, 0.2)"></div>
                  </a>
                </div>
                <!-- Image -->
              </div>

              <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                <!-- Data -->
                <p class="mb-3"><strong><?=ucwords($product['subject']) . ' Question Paper'?></strong></p>
                <p class="mb-3"><small><?=$product['exam_body'] . ' (' . $product['year'] . ')' ?></small></p>
                <form method="post" action="cart">
                <input type="hidden" name="sku" value="<?=$productId?>">
                <button  type="submit" class="btn btn-danger btn-sm me-1 mb-4 py-2 px-3" data-mdb-tooltip-init
                  title="Remove item" name="action" value="delete">
                  <i class="bi bi-trash"></i>
                </button>
                </form>
                <!-- Data -->
              </div>

              <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <!-- Quantity -->
                <form action="cart" method="POST">
                <div class="d-flex mb-4 justify-content-center" style="max-width: 300px">
                <input type="hidden" name="sku" value="<?=$productId ?>">
                  <button type="submit" name="action" value="-" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary px-3 me-2" style="<?=$btn?>">
                    <i class="bi bi-dash"></i>
                  </button>

                  <div data-mdb-input-init class="form-outline">
                    <input id="form1" min="1" name="quantity" value="<?=$product['quantity'] ?>" type="text" maxlength="2" size="2" class="form-control"/>
                    
                  </div>

                  <button type="submit" name="action" value="+" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary px-3 ms-2" style="<?=$btn?>">
                    <i class="bi bi-plus"></i>
                  </button>
                </div>
                </form>
                <!-- Quantity -->
                <p class="text-center"><label class="form-label text-muted" for="form1">Quantity</label></p>
                <!-- Price -->
                <p class="text-start text-md-center text-success">
                  ₦<?=number_format($subTotal) ?>
                </p>
                <!-- Price -->
              </div>
            </div>
            <!-- Single item -->

            <hr class="my-4" />

          </div>
          <?php endforeach; ?>
        </div>

      </div>
      <div class="col-md-4">
        <div class="card mb-4">
          <div class="card-header py-3">
            <h5 class="mb-0">Summary</h5>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                Products
                <h5><strong>₦<?=number_format($total) ?></strong></h5>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center px-0">

              </li>
              <li
                class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                <div>
                  <strong>Total amount</strong>
                  <strong>
                    <p class="mb-0">(including VAT)</p>
                  </strong>
                </div>
                <h5 class="text-success"><strong>₦<?=number_format($total) ?></strong></h5>
              </li>
            </ul>
            <form method="post" action="checkout">
            <input type="hidden" name="total" value="<?=$total ?>">
            <button  type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg btn-block" style="<?=$btn?>; padding: 10px;">
              checkout
            </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php else: ?>
    <div class="container">
        <div class="d-flex justify-content-center" style="margin-top: 200px; margin-bottom: 200px;">
            <div class="card w-100 align-self-center text-muted py-5">
                <p class="text-center">Cart is empty!</p>
                <p class="text-center text-muted mt-5"><a href="home" class="text-decoration-none text-muted"><i class="bi bi-arrow-left"></i> Back to home page</a></p>
            </div>
        </div>
    </div>
<?php endif; ?>