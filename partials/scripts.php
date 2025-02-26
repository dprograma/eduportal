<script src="assets/libs/jquery/jquery.min.js"></script>
<script src="assets/js/admin.js"></script>
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
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

<!-- Search functionality -->
<script src="assets/js/search.js"></script>

<script src="assets/js/config.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> <!-- Ensure this is after jQuery -->


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- Conditionally include posts script -->
<?php if (isset($posts)): ?>
    <script src="assets/js/posts.js"></script>
    <script>
        // Initialize posts with data
        initializePosts(<?php echo json_encode($posts); ?>);
    </script>
<?php endif; ?>

<script>
function showSearchOverlay() {
    console.log('Search trigger clicked');
    const searchOverlay = document.getElementById('searchOverlay');
    const searchInput = document.getElementById('searchInput');

    if (searchOverlay) {
        console.log('Search overlay before adding active class:', searchOverlay.classList);
        searchOverlay.classList.add('active');
        console.log('Search overlay after adding active class:', searchOverlay.classList);
        if (searchInput) {
            setTimeout(() => searchInput.focus(), 100);
        }
    } else {
        console.error('Search overlay element not found');
    }
}

// Ensure the rest of your script handles closing the overlay and other interactions
document.addEventListener('DOMContentLoaded', function () {
    const closeSearch = document.getElementById('closeSearch');
    const searchOverlay = document.getElementById('searchOverlay');
    const searchSuggestions = document.getElementById('searchSuggestions');

    // Hide search overlay when close button is clicked
    closeSearch?.addEventListener('click', () => {
        closeSearchOverlay();
    });

    // Hide search overlay when clicking outside
    searchOverlay?.addEventListener('click', (e) => {
        if (e.target === searchOverlay) {
            closeSearchOverlay();
        }
    });

    // Close on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
            closeSearchOverlay();
        }
    });

    function closeSearchOverlay() {
        searchOverlay.classList.remove('active');
        searchInput.value = '';
        searchSuggestions.style.display = 'none';
    }
});

async function fetchSuggestions(query) {
    try {
        const searchType = document.querySelector('input[name="searchType"]:checked').value;
        const response = await fetch(`api/search/suggestions?q=${encodeURIComponent(query)}&type=${searchType}`);
        if (!response.ok) throw new Error('Network response was not ok');
        const data = await response.json();
        displaySuggestions(data);
    } catch (error) {
        console.error('Error fetching suggestions:', error);
    }
}

function displaySuggestions(suggestions) {
    const container = document.getElementById('searchSuggestions');
    if (!suggestions || !suggestions.length) {
        container.style.display = 'none';
        return;
    }

    container.innerHTML = suggestions.map(item => `
        <a href="search?q=${encodeURIComponent(item.title)}&type=${item.type}"
           class="suggestion-item p-2 d-block text-decoration-none text-dark hover-bg-light">
            <div class="d-flex align-items-center">
                <i class="bi ${getIconForType(item.type)} me-2"></i>
                <div>
                    <div class="fw-semibold">${escapeHtml(item.title)}</div>
                    <small class="text-muted">${item.type} • ${item.year}</small>
                </div>
            </div>
        </a>
    `).join('');

    container.style.display = 'block';
}

function getIconForType(type) {
    const icons = {
        ebook: 'bi-book',
        publication: 'bi-journal-text',
        pastQuestion: 'bi-question-circle'
    };
    return icons[type] || 'bi-file-text';
}

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Function to show search modal
function showSearchModal() {
    const searchModal = new bootstrap.Modal(document.getElementById('searchModal'));
    searchModal.show();
}
</script>
<script src="assets/js/admin.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>