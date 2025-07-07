/**
 * Local Date Formatter
 * 
 * Formats timestamps to local date format: Jul 08, 2025
 */

// Only initialize once
if (!window.localDateInitialized) {
    window.localDateInitialized = true;

    function formatLocalDate(element) {
        const utcDate = new Date(element.dataset.utc + (element.dataset.utc.includes('UTC') ? '' : ' UTC'));

        // Format: Jul 08, 2025
        const month = utcDate.toLocaleDateString('en-US', { month: 'short' });
        const day = String(utcDate.getDate()).padStart(2, '0');
        const year = utcDate.getFullYear();

        element.textContent = `${month} ${day}, ${year}`;
    }

    // Format all existing elements
    document.querySelectorAll('.js-local-date').forEach(formatLocalDate);

    // Watch for new elements (for dynamically loaded content)
    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            mutation.addedNodes.forEach(node => {
                if (node.nodeType === 1) { // Element node
                    if (node.classList?.contains('js-local-date')) {
                        formatLocalDate(node);
                    }
                    // Check children
                    node.querySelectorAll?.('.js-local-date').forEach(formatLocalDate);
                }
            });
        });
    });

    observer.observe(document.body, { childList: true, subtree: true });
}