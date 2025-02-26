<!doctype html>
<html lang="en">
<?php $title = "EduPortal | Welcome"; ?>
<?php include 'partials/head.php'; ?>
<style>
   span.cart-tip {
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
      span.cart-tip {
         background-color: #9519ba;
         transform: translate(-10px, 0px);
      }
   }
</style>

<body>


    <!-- Navbar -->
    <?php include 'partials/landing_header.php'; ?>
    <!--main area-->
    <?php include 'partials/main.php'; ?>
    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>
    <!-- Scroll top -->
    <?php include 'partials/scroll_top.php'; ?>
    <!-- Libs JS -->
    <?php include 'partials/scripts.php'; ?>
</body>

</html>