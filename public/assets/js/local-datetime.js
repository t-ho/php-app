/**
 * Local DateTime Formatter
 * 
 * Formats timestamps to local datetime format: Jul 06, 2025 @ 8:20PM
 */

// Only initialize once
if (!window.localDateTimeInitialized) {
    window.localDateTimeInitialized = true;

    function formatLocalDateTime(element) {
        const utcDate = new Date(element.dataset.utc + (element.dataset.utc.includes('UTC') ? '' : ' UTC'));

        // Format: Jul 06, 2025 @ 8:20PM
        const month = utcDate.toLocaleDateString('en-US', { month: 'short' });
        const day = String(utcDate.getDate()).padStart(2, '0');
        const year = utcDate.getFullYear();
        const hours12 = utcDate.getHours() % 12 || 12;
        const minutes = String(utcDate.getMinutes()).padStart(2, '0');
        const ampm = utcDate.getHours() >= 12 ? 'PM' : 'AM';

        element.textContent = `${month} ${day}, ${year} @ ${hours12}:${minutes}${ampm}`;
    }

    // Format all existing elements
    document.querySelectorAll('.js-local-datetime').forEach(formatLocalDateTime);

    // Watch for new elements (for dynamically loaded content)
    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            mutation.addedNodes.forEach(node => {
                if (node.nodeType === 1) { // Element node
                    if (node.classList?.contains('js-local-datetime')) {
                        formatLocalDateTime(node);
                    }
                    // Check children
                    node.querySelectorAll?.('.js-local-datetime').forEach(formatLocalDateTime);
                }
            });
        });
    });

    observer.observe(document.body, { childList: true, subtree: true });
}