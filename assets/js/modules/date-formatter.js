export class DateFormatter {
  constructor() {
    this.initialized = false;
    this.observers = [];
  }

  formatLocalDate(element) {
    const utcDate = new Date(element.dataset.utc + (element.dataset.utc.includes('UTC') ? '' : ' UTC'));
    
    const month = utcDate.toLocaleDateString('en-US', { month: 'short' });
    const day = String(utcDate.getDate()).padStart(2, '0');
    const year = utcDate.getFullYear();

    element.textContent = `${month} ${day}, ${year}`;
  }

  formatLocalDateTime(element) {
    const utcDate = new Date(element.dataset.utc + (element.dataset.utc.includes('UTC') ? '' : ' UTC'));
    
    const month = utcDate.toLocaleDateString('en-US', { month: 'short' });
    const day = String(utcDate.getDate()).padStart(2, '0');
    const year = utcDate.getFullYear();
    const hours12 = utcDate.getHours() % 12 || 12;
    const minutes = String(utcDate.getMinutes()).padStart(2, '0');
    const ampm = utcDate.getHours() >= 12 ? 'PM' : 'AM';

    element.textContent = `${month} ${day}, ${year} @ ${hours12}:${minutes}${ampm}`;
  }

  init() {
    if (this.initialized) return;
    this.initialized = true;

    document.querySelectorAll('.js-local-date').forEach(el => this.formatLocalDate(el));
    document.querySelectorAll('.js-local-datetime').forEach(el => this.formatLocalDateTime(el));

    const observer = new MutationObserver(mutations => {
      mutations.forEach(mutation => {
        mutation.addedNodes.forEach(node => {
          if (node.nodeType === 1) {
            if (node.classList?.contains('js-local-date')) {
              this.formatLocalDate(node);
            }
            if (node.classList?.contains('js-local-datetime')) {
              this.formatLocalDateTime(node);
            }
            node.querySelectorAll?.('.js-local-date').forEach(el => this.formatLocalDate(el));
            node.querySelectorAll?.('.js-local-datetime').forEach(el => this.formatLocalDateTime(el));
          }
        });
      });
    });

    observer.observe(document.body, { childList: true, subtree: true });
    this.observers.push(observer);
  }

  destroy() {
    this.observers.forEach(observer => observer.disconnect());
    this.observers = [];
    this.initialized = false;
  }
}