document.addEventListener('DOMContentLoaded', function () {
    // Search Overlay Elements
    const searchTrigger = document.getElementById('searchTrigger');
    const searchOverlay = document.getElementById('searchOverlay');
    const closeSearch = document.getElementById('closeSearch');
    const searchInput = document.getElementById('searchInput');
    const searchSuggestions = document.getElementById('searchSuggestions');

    // Show search overlay when search icon is clicked
    searchTrigger?.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        searchOverlay.classList.add('active');
        setTimeout(() => searchInput.focus(), 100);
    });

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

    // Handle search input with debouncing
    let searchTimeout;
    searchInput?.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();

        if (query.length < 2) {
            searchSuggestions.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetchSuggestions(query);
        }, 300);
    });

    // Handle search submission
    searchInput?.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            const query = e.target.value.trim();
            if (query.length >= 2) {
                const searchType = document.querySelector('input[name="searchType"]:checked').value;
                window.location.href = `search?q=${encodeURIComponent(query)}&type=${searchType}`;
            }
        }
    });

    // Handle radio button changes
    document.querySelectorAll('input[name="searchType"]').forEach(radio => {
        radio.addEventListener('change', () => {
            if (searchInput.value.trim().length >= 2) {
                fetchSuggestions(searchInput.value.trim());
            }
        });
    });
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

