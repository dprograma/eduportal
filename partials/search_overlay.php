<style>
.modal.search-modal {
    background: rgba(0, 0, 0, 0.9);
}

.modal.search-modal .modal-dialog {
    max-width: 800px;
    margin-top: 2rem;
}

.modal.search-modal .modal-content {
    background: transparent;
    border: none;
}

.search-filters {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin: 0 -1rem;
    padding: 0.5rem 1rem;
}

.search-filters .btn-group {
    display: flex;
    flex-wrap: nowrap;
    width: auto;
    min-width: 100%;
}

.search-filters .btn {
    flex: 1;
    white-space: nowrap;
    min-width: auto;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .search-filters {
        margin: 0 -0.5rem;
        padding: 0.5rem;
    }

    .search-filters .btn-group {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
        width: 100%;
    }

    .search-filters .btn {
        width: 100%;
        border-radius: 0.25rem !important;
        margin: 0 !important;
        border: 1px solid rgba(255,255,255,0.5) !important;
    }

    .search-filters label.btn {
        margin: 0;
        text-align: center;
    }

    .search-box {
        position: relative;
        z-index: 1;
    }

    .search-box input {
        width: 100%;
        padding: 0.75rem;
        font-size: 1rem;
    }

    .search-suggestions {
        position: absolute;
        width: 100%;
        z-index: 2;
        background: white;
        border-radius: 0.25rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
}
</style>

<!-- Search Modal -->
<div class="modal fade search-modal" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container">
                    <div class="d-flex justify-content-end mb-4">
                        <button type="button" class="btn btn-link text-white" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg fs-3"></i>
                        </button>
                    </div>
                    <h2 class="text-white mb-4">Search EduPortal</h2>
                    <div class="search-box mb-4">
                        <input type="text"
                               class="form-control form-control-lg"
                               id="searchInput"
                               placeholder="Enter keywords to search..."
                               autocomplete="off">
                        <small class="text-light mt-2 d-block">
                            <i class="bi bi-info-circle me-1"></i>
                            Search Tips:
                            <ul class="mt-1 small">
                                <li>Use subject names for past questions (e.g., "Mathematics", "English")</li>
                                <li>Include year for specific results (e.g., "WAEC 2022", "JAMB 2023")</li>
                                <li>Use book titles or authors for ebooks and publications</li>
                            </ul>
                        </small>
                        <div class="search-suggestions shadow-lg" id="searchSuggestions"></div>
                    </div>
                    <div class="search-filters mb-4">
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="searchType" id="all" value="all" checked>
                            <label class="btn btn-outline-light" for="all">All</label>

                            <input type="radio" class="btn-check" name="searchType" id="ebook" value="ebook">
                            <label class="btn btn-outline-light" for="ebook">Ebooks</label>

                            <input type="radio" class="btn-check" name="searchType" id="publication" value="publication">
                            <label class="btn btn-outline-light" for="publication">Publications</label>

                            <input type="radio" class="btn-check" name="searchType" id="past_question" value="past question">
                            <label class="btn btn-outline-light" for="past_question">Past Questions</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchTypes = document.getElementsByName('searchType');
    const searchModal = new bootstrap.Modal(document.getElementById('searchModal'));

    // Handle search input
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    });

    function performSearch() {
        const query = searchInput.value.trim();
        if (!query) return;

        // Get selected search type
        const selectedType = Array.from(searchTypes).find(radio => radio.checked)?.value;

        // Build the search URL with proper encoding
        const params = new URLSearchParams();
        params.append('q', query);

        // Add type parameter if not "all"
        if (selectedType && selectedType !== 'all') {
            params.append('types', selectedType);
        }

        // Close the search modal
        searchModal.hide();

        // Redirect to search results page
        window.location.href = `search?${params.toString()}`;
    }

    // Optional: Handle suggestions as user types
    let debounceTimer;
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const query = this.value.trim();
            if (query.length >= 2) {
                fetchSuggestions(query);
            } else {
                document.getElementById('searchSuggestions').innerHTML = '';
            }
        }, 300);
    });

    async function fetchSuggestions(query) {
        try {
            const selectedType = Array.from(searchTypes).find(radio => radio.checked)?.value;

            let params = new URLSearchParams({
                q: query,
                suggest: 'true'
            });

            if (selectedType !== 'all') {
                params.append('types', selectedType);
            }

            const response = await fetch(`search?${params.toString()}`);
            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();
            displaySuggestions(data.results);
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

        container.innerHTML = suggestions.map(item => {
            let params = new URLSearchParams({
                q: item.title,
                types: item.type
            });

            return `
                <a href="search?${params.toString()}"
                   class="suggestion-item p-2 d-block text-decoration-none text-dark hover-bg-light">
                    <div class="d-flex align-items-center">
                        <i class="bi ${getIconForType(item.type)} me-2"></i>
                        <div>
                            <div class="fw-semibold">${escapeHtml(item.title)}</div>
                            <small class="text-muted">${formatType(item.type)} • ${item.year}</small>
                        </div>
                    </div>
                </a>
            `;
        }).join('');

        container.style.display = 'block';
    }

    function formatType(type) {
        switch(type) {
            case 'ebook': return 'Ebook';
            case 'publication': return 'Publication';
            case 'past question': return 'Past Question';
            default: return type;
        }
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function getIconForType(type) {
        const icons = {
            'ebook': 'bi-book',
            'publication': 'bi-journal-text',
            'past question': 'bi-question-circle'
        };
        return icons[type] || 'bi-file-text';
    }

    // Make the modal focusable
    const searchModal = document.getElementById('searchModal');
    searchModal.addEventListener('shown.bs.modal', function () {
        searchInput.focus();
    });
});
</script>
