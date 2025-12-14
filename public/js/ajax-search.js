/**
 * Reusable AJAX Search Function
 * 
 * @param {string} inputSelector - CSS selector for the search input
 * @param {string} containerSelector - CSS selector for the container to update (e.g., table body or div)
 * @param {string} url - Base URL for the search request
 */
function initAjaxSearch(inputSelector, containerSelector, url) {
    const input = document.querySelector(inputSelector);
    const container = document.querySelector(containerSelector);
    let timeout = null;

    if (!input || !container) {
        console.error('AJAX Search: Input or Container not found', { inputSelector, containerSelector });
        return;
    }

    input.addEventListener('input', function () {
        const query = this.value;
        const filter = document.querySelector('select[name="filter"]')?.value || 'all'; // Optional filter support

        // Clear existing timeout (debounce)
        if (timeout) clearTimeout(timeout);

        // Set new timeout
        timeout = setTimeout(() => {
            // Show loading state (optional)
            container.style.opacity = '0.5';

            // Construct URL with query params
            const searchUrl = new URL(url, window.location.origin);
            searchUrl.searchParams.set('search', query);
            if (filter) searchUrl.searchParams.set('filter', filter);
            searchUrl.searchParams.set('page', 1); // Reset to page 1 on search

            fetch(searchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.text())
                .then(html => {
                    container.innerHTML = html;
                    container.style.opacity = '1';

                    // Re-initialize any necessary JS listeners here if needed
                })
                .catch(error => {
                    console.error('AJAX Search Error:', error);
                    container.style.opacity = '1';
                });
        }, 300); // 300ms debounce
    });

    // Handle Filter Change if it exists
    const filterSelect = document.querySelector('select[name="filter"]');
    if (filterSelect) {
        filterSelect.addEventListener('change', function () {
            input.dispatchEvent(new Event('input'));
        });
    }
}
