<script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/simplebar/dist/simplebar.min.js"></script>
<script src="assets/libs/headhesive/dist/headhesive.min.js"></script>

<!-- Theme JS -->
<script src="assets/js/theme.min.js"></script>

<script src="assets/libs/jarallax/dist/jarallax.min.js"></script>
<script src="assets/js/vendors/jarallax.js"></script>
<script src="assets/libs/scrollcue/scrollCue.min.js"></script>
<script src="assets/js/vendors/scrollcue.js"></script>
<script src="assets/js/vendors/password.js"></script>
<script src="assets/vendor/js/helpers.js"></script>
<script src="assets/vendor/js/template-customizer.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="assets/js/config.js"></script>
<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- <script>
         $(document).ready(function() {
             $('#usersTable').DataTable({
                 "paging": true,
                 "lengthMenu": [10, 25, 50, 75, 100],
                 "pageLength": 10,
                 "searching": true,
                 "ordering": true,
                 "info": true,
                 "autoWidth": false
             });
         }); -->
</script>
<script>
    // document.addEventListener('DOMContentLoaded', function () {
    //     document.querySelectorAll('.view-user-btn').forEach(button => {
    //         button.addEventListener('click', function () {
    //             const userId = this.getAttribute('data-id');
    //             console.log("I got here!!!");
    //             // Fetch user details via AJAX
    //             fetch(`user-details?id=${userId}`)
    //                 .then(response => {response.json(); console.log('response from user modal: ', response)})
    //                 .then(data => {
    //                     if (data.success) {
    //                         // Populate modal with user details
    //                         document.getElementById('userImage').src = data.user.profileimg;
    //                         document.getElementById('userFullName').textContent = data.user.fullname;
    //                         document.getElementById('userEmail').textContent = data.user.email;
    //                         document.getElementById('userAccessLevel').textContent = data.user.access;
    //                         document.getElementById('userYear').textContent = data.user.created_date;
    //                     } else {
    //                         alert('Error fetching user details.');
    //                     }
    //                 })
    //                 .catch(error => console.error('Error:', error));
    //         });
    //     });
    // });

    document.addEventListener("DOMContentLoaded", () => {
        // Data (Replace with actual PHP data if needed)
        const posts = <?= json_encode($posts) ?>; // Example PHP-to-JS conversion
        const postsPerPage = 6; // Number of posts per page
        let currentPage = 1;

        // Elements
        const postsContainer = document.getElementById("postsContainer");
        const paginationControls = document.getElementById("paginationControls");

        // Function to render posts for the current page
        function renderPosts() {
            postsContainer.innerHTML = ""; // Clear container

            const start = (currentPage - 1) * postsPerPage;
            const end = start + postsPerPage;
            const paginatedPosts = posts.slice(start, end);

            paginatedPosts.forEach((post) => {
                const postHtml = `
                <div class="col-lg-4 col-md-6">
                    <div class="card shadow-sm h-100">
                        <img src="${post.img}" class="card-img-top" alt="${post.title}" />
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-uppercase fw-bold text-primary">
                                ${post.title}
                            </h5>
                            <p class="text-muted mb-2">
                                <span class="fw-bold">Category:</span> ${post.category}
                            </p>
                            <p class="card-text text-muted">
                                ${post.body.substring(0, 150)}...
                            </p>
                            <small class="text-muted">
                                ${(new Date(post.date_created)).toLocaleDateString("en-US", {
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                })}
                            </small>
                            <div class="mt-auto">
                                <a href="blogdetails?title=${post.title.replace(/\s+/g, "_")}" 
                                   class="btn btn-primary btn-sm mt-3">
                                    Learn More <i class="bi bi-arrow-right-circle-fill"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>`;
                postsContainer.innerHTML += postHtml;
            });
        }

        // Function to create pagination buttons
        function createPagination() {
            paginationControls.innerHTML = ""; // Clear pagination controls

            const totalPages = Math.ceil(posts.length / postsPerPage);

            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement("li");
                li.className = `page-item ${i === currentPage ? "active" : ""}`;
                li.innerHTML = `
                <button class="page-link" data-page="${i}">${i}</button>
            `;
                paginationControls.appendChild(li);
            }
        }

        // Handle page change
        paginationControls.addEventListener("click", (e) => {
            if (e.target.tagName === "BUTTON") {
                currentPage = parseInt(e.target.getAttribute("data-page"));
                renderPosts();
                createPagination();
            }
        });

        // Initialize
        renderPosts();
        createPagination();
    });

</script>
<script src="assets/js/admin.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>