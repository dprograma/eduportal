<!doctype html>
<html lang="en">
<?php $title = "EduPortal | Past Questions"; ?>
<?php include 'partials/head.php'; ?>

<body>
   <!-- Navbar -->
   <?php include 'partials/landing_header.php'; ?>
   <!--main area-->
   <div class="mt-5 pt-5"></div>
   <?php include 'partials/past_question_content.php'; ?>
   <!-- Footer -->
   <?php include 'partials/footer.php'; ?>
   <!-- Scroll top -->
   <?php include 'partials/scroll_top.php'; ?>
   <!-- Libs JS -->
   <?php include 'partials/scripts.php'; ?>
</body>

<script>
   document.addEventListener('DOMContentLoaded', function () {
      const paginationLinks = document.querySelectorAll('.pagination-link');

      paginationLinks.forEach(link => {
         link.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            fetch(url)
               .then(response => response.text())
               .then(data => {
                  const parser = new DOMParser();
                  const doc = parser.parseFromString(data, 'text/html');
                  const newContent = doc.querySelector('.list-group').innerHTML;
                  const newPagination = doc.querySelector('#pagination-container').innerHTML;

                  // Update the content and pagination
                  document.querySelector('.list-group').innerHTML = newContent;
                  document.querySelector('#pagination-container').innerHTML = newPagination;

                  // Scroll to the pagination section
                  document.querySelector('#pagination-container').scrollIntoView({ behavior: 'smooth' });

                  // Re-bind the click events to the new pagination links
                  bindPaginationLinks();
               })
               .catch(error => console.error('Error:', error));
         });
      });

      function bindPaginationLinks() {
         const newPaginationLinks = document.querySelectorAll('.pagination-link');
         newPaginationLinks.forEach(link => {
            link.addEventListener('click', function (e) {
               e.preventDefault();
               const url = this.getAttribute('href');
               fetch(url)
                  .then(response => response.text())
                  .then(data => {
                     const parser = new DOMParser();
                     const doc = parser.parseFromString(data, 'text/html');
                     const newContent = doc.querySelector('.list-group').innerHTML;
                     const newPagination = doc.querySelector('#pagination-container').innerHTML;

                     document.querySelector('.list-group').innerHTML = newContent;
                     document.querySelector('#pagination-container').innerHTML = newPagination;

                     document.querySelector('#pagination-container').scrollIntoView({ behavior: 'smooth' });

                     bindPaginationLinks();
                  })
                  .catch(error => console.error('Error:', error));
            });
         });
      }
   });
</script>
<script>
   document.addEventListener('DOMContentLoaded', function () {
      // Check if the URL has query parameters
      if (window.location.search) {
         // Scroll to the search form
         document.getElementById('search-form').scrollIntoView({ behavior: 'smooth' });
      }
   });
</script>


</html>