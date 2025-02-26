<?php $title = "Search Results | " . SITE_TITLE; ?>
<?php include "partials/head.php"?>

<body>
    <?php include "partials/landing_header.php"?>

    <main class="py-5" style="position: relative; top: 100px;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Search Results</h1>
                <div class="search-box">
                    <input type="text"
                           class="form-control"
                           id="refinementSearch"
                           value="<?php echo htmlspecialchars($_GET['q'] ?? '') ?>"
                           placeholder="Refine your search...">
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <!-- Filters Sidebar -->
                    <div class="card sticky-top" style="top: 100px; z-index: 1;">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Filters</h5>
                            <form id="searchFilters">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Document Type</label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input filter-check" type="checkbox" value="ebook" id="ebookFilter">
                                        <label class="form-check-label" for="ebookFilter">Ebooks</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input filter-check" type="checkbox" value="publication" id="publicationFilter">
                                        <label class="form-check-label" for="publicationFilter">Publications</label>
                                </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input filter-check" type="checkbox" value="past question" id="pastQuestionFilter">
                                        <label class="form-check-label" for="pastQuestionFilter">Past Questions</label>
                                </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Year</label>
                                <select class="form-select" id="yearFilter">
                                    <option value="">All Years</option>
                                        <?php
                                            // Generate years from 2015 to current year
                                            $currentYear = date('Y');
                                            for ($year = $currentYear; $year >= 2015; $year--) {
                                                echo "<option value=\"$year\">$year</option>";
                                            }
                                        ?>
                                </select>
                            </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Subject</label>
                                <select class="form-select" id="subjectFilter">
                                    <option value="">All Subjects</option>
                                        <?php
                                            $subjects = [
                                                'Mathematics', 'English', 'Physics', 'Chemistry',
                                                'Biology', 'Economics', 'Literature', 'Government',
                                                'History', 'Geography', 'Commerce', 'Accounting',
                                                'Computer Science',
                                            ];
                                            foreach ($subjects as $subject) {
                                                echo "<option value=\"$subject\">$subject</option>";
                                            }
                                        ?>
                                </select>
                            </div>

                                <button type="button" class="btn btn-primary w-100" onclick="applyFilters()">
                                    Apply Filters
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <!-- Results Grid -->
                    <div class="row g-4" id="searchResults">
                        <!-- Results will be dynamically inserted here -->
                    </div>

                    <!-- Pagination -->
                    <nav class="mt-4" aria-label="Search results pages">
                        <ul class="pagination justify-content-center" id="pagination">
                            <!-- Pagination will be dynamically inserted here -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </main>

    <?php include "partials/footer.php"?>
<?php include "partials/scripts.php"?>

    <script>
    // Initialize search on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Get URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const initialQuery = urlParams.get('q');
        const initialTypes = urlParams.get('types')?.split(',') || [];

        // Set initial values in the form
        document.getElementById('refinementSearch').value = initialQuery || '';

        // Set initial document type checkboxes
        initialTypes.forEach(type => {
            const checkbox = document.querySelector(`input[value="${type}"]`);
            if (checkbox) checkbox.checked = true;
        });

        // Load initial results
        loadSearchResults(1);

        // Add event listener for search refinement
        const refinementSearch = document.getElementById('refinementSearch');
        let debounceTimer;
        refinementSearch.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => loadSearchResults(1), 500);
        });
    });

    function applyFilters() {
        loadSearchResults(1); // Reset to first page when applying filters
    }

    async function loadSearchResults(page = 1) {
        try {
            const searchQuery = document.getElementById('refinementSearch').value;
            const types = Array.from(document.querySelectorAll('.filter-check:checked')).map(cb => cb.value);
            const year = document.getElementById('yearFilter').value;
            const subject = document.getElementById('subjectFilter').value;

            // Construct query parameters
            const params = new URLSearchParams({
                q: searchQuery || '<?php echo htmlspecialchars($_GET['q'] ?? '') ?>', // Use URL parameter if no refinement
                page: page
            });

            // Only add filters if they have values
            if (types.length > 0) params.append('types', types.join(','));
            if (year) params.append('year', year);
            if (subject) params.append('subject', subject);

            // Update URL without reloading the page
            window.history.replaceState(
                {},
                '',
                `${window.location.pathname}?${params.toString()}`
            );

            console.log('Fetching results with params:', params.toString());

            const response = await fetch(`search?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Response was not JSON');
            }

            const data = await response.json();
            console.log('Search response:', data);

            if (data.error) {
                throw new Error(data.message || 'Server error');
            }

            displayResults(data.results);
            updatePagination(data.pagination);

            console.log(`Displaying ${data.results.length} results`);

        } catch (error) {
            console.error('Error fetching results:', error);
            document.getElementById('searchResults').innerHTML = `
                <div class="col-12 text-center">
                    <div class="alert alert-danger">
                        <p>Error loading results: ${error.message}</p>
                        <p>Please try again or contact support if the problem persists.</p>
                    </div>
                </div>`;
        }
    }

    function displayResults(results) {
        const container = document.getElementById('searchResults');
        console.log('Display results called with:', results);

        if (!results || results.length === 0) {
            const searchQuery = document.getElementById('refinementSearch').value;
            const selectedType = document.querySelector('.filter-check:checked')?.value || 'All';

            container.innerHTML = `
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <h4 class="alert-heading mb-3">No matches found</h4>
                        <p>We couldn't find any ${selectedType !== 'all' ? selectedType + ' ' : ''}documents matching "${searchQuery}"</p>
                        <hr>
                        <p class="mb-0">Try:</p>
                        <ul class="list-unstyled mt-2">
                            <li>• Checking for typos or misspellings</li>
                            <li>• Using more general keywords</li>
                            <li>• Removing filters to broaden your search</li>
                            ${selectedType !== 'all' ? '<li>• Searching in all document types</li>' : ''}
                        </ul>
                    </div>
                </div>`;
            return;
        }

        container.innerHTML = results.map(result => {
            return `
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <img src="uploads/template/avatar.jpg"
                             class="card-img-top"
                             alt="${escapeHtml(result.title)}"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">${escapeHtml(result.title)}</h5>
                            <p class="card-text">
                                <small class="text-muted">
                                    ${formatType(result.type)} • ${result.year}
                                    ${result.subject ? `• ${result.subject}` : ''}
                                    ${result.exam_body ? `• ${result.exam_body}` : ''}
                                </small>
                            </p>
                            <p class="card-text">${escapeHtml(result.description || '')}</p>
                            <h6 class="mb-3">₦${result.price}</h6>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <button onclick='showProductDetails(${JSON.stringify(result)})'
                                    class="btn btn-primary btn-sm">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function formatType(type) {
        switch(type) {
            case 'ebook': return 'Ebook';
            case 'publication': return 'Publication';
            case 'past question': return 'Past Question';
            default: return type;
        }
    }

    function updatePagination(pagination) {
        const container = document.getElementById('pagination');
        if (!pagination || pagination.totalPages <= 1) {
            container.innerHTML = '';
            return;
        }

        let html = '';
        // Previous button
        html += `
            <li class="page-item ${pagination.currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="loadSearchResults(${pagination.currentPage - 1}); return false;">Previous</a>
            </li>`;

        // Page numbers
        for (let i = 1; i <= pagination.totalPages; i++) {
            html += `
                <li class="page-item ${pagination.currentPage === i ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="loadSearchResults(${i}); return false;">${i}</a>
                </li>`;
        }

        // Next button
        html += `
            <li class="page-item ${pagination.currentPage === pagination.totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="loadSearchResults(${pagination.currentPage + 1}); return false;">Next</a>
            </li>`;

        container.innerHTML = html;
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // Helper function for suggestion icons (keep this if you're using suggestions)
    function getIconForType(type) {
        const icons = {
            'ebooks': 'bi-book',
            'publications': 'bi-journal-text',
            'pastQuestions': 'bi-question-circle'
        };
        return icons[type] || 'bi-file-text';
    }

    // Product Details Modal
    let currentProduct = null;

    function showProductDetails(result) {
        currentProduct = result;

        // Update modal content
        document.getElementById('modalProductTitle').textContent = result.title;
        document.getElementById('modalProductType').textContent = formatType(result.type);
        document.getElementById('modalProductYear').textContent = result.year;
        document.getElementById('modalProductSubject').textContent = result.subject ? ` • ${result.subject}` : '';
        document.getElementById('modalProductExamBody').textContent = result.exam_body ? ` • ${result.exam_body}` : '';
        document.getElementById('modalProductDescription').textContent = result.description || '';
        document.getElementById('modalProductPrice').textContent = `₦${result.price}`;
        document.getElementById('modalProductImage').src = result.image_url || 'assets/img/placeholder.jpg';

        // Update form inputs
        document.getElementById('modalProductSku').value = `${result.type}_${result.id}`;
        document.getElementById('modalProductPriceInput').value = result.price;
        document.getElementById('modalProductTitleInput').value = result.title;
        document.getElementById('modalProductSubjectInput').value = result.subject || '';
        document.getElementById('modalProductExamBodyInput').value = result.exam_body || '';
        document.getElementById('modalProductYearInput').value = result.year;
        document.getElementById('modalProductImageInput').value = result.image_url || '';

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    }
    </script>

    <!-- Product Details Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img id="modalProductImage" src="" alt="Product" class="img-fluid mb-3">
                            <h4 id="modalProductPrice" class="text-primary"></h4>
                        </div>
                        <div class="col-md-6">
                            <h3 id="modalProductTitle"></h3>
                            <p class="text-muted">
                                <span id="modalProductType"></span> •
                                <span id="modalProductYear"></span>
                                <span id="modalProductSubject"></span>
                                <span id="modalProductExamBody"></span>
                            </p>
                            <p id="modalProductDescription"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form action="add-to-cart" method="POST" id="addToCartForm">
                        <input type="hidden" name="sku" id="modalProductSku">
                        <input type="hidden" name="price" id="modalProductPriceInput">
                        <input type="hidden" name="title" id="modalProductTitleInput">
                        <input type="hidden" name="subject" id="modalProductSubjectInput">
                        <input type="hidden" name="exam_body" id="modalProductExamBodyInput">
                        <input type="hidden" name="year" id="modalProductYearInput">
                        <input type="hidden" name="coverpage" id="modalProductImageInput">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>