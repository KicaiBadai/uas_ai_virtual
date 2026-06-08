/* public/js/admin-scripts.js - Utility scripts for admin panel */

/**
 * Debounce a function, delaying its execution until after wait milliseconds have elapsed
 * since the last time it was invoked.
 */
function debounce(func, wait) {
  let timeout;
  return function (...args) {
    const later = () => {
      clearTimeout(timeout);
      func.apply(this, args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

/**
 * Filter order rows based on search input.
 * Looks for the query in any visible cell text.
 */
function filterOrders() {
  const query = document.getElementById('order-search').value.trim().toLowerCase();
  const rows = document.querySelectorAll('table tbody tr');
  rows.forEach(row => {
    const cells = Array.from(row.cells);
    const matches = cells.some(td => td.textContent.toLowerCase().includes(query));
    row.style.display = matches || query === '' ? '' : 'none';
  });
}

/**
 * Show a Bootstrap toast with a custom message.
 */
function showToast(message) {
  const toastEl = document.getElementById('copyToast');
  if (!toastEl) return;
  const body = toastEl.querySelector('.toast-body');
  if (body) body.textContent = message;
  const toast = new bootstrap.Toast(toastEl);
  toast.show();
}

/**
 * Copy message content to clipboard and show feedback toast.
 * Works for both user and AI bubbles.
 */
function copyMessage(btn) {
  const bubble = btn.closest('.chat-bubble-user, .chat-bubble-ai');
  const textNode = bubble.querySelector('.text-sm');
  const text = textNode ? textNode.innerText : '';
  navigator.clipboard.writeText(text).then(() => {
    showToast('Pesan berhasil disalin');
  }).catch(() => {
    showToast('Gagal menyalin pesan');
  });
}

/**
 * Initialize event listeners after DOM is ready.
 */
/**
 * Initialize event listeners after DOM is ready.
 */
document.addEventListener('DOMContentLoaded', () => {
  // Filter product table rows based on search input
  function filterProducts() {
    const query = document.getElementById('productSearch').value.trim().toLowerCase();
    const rows = document.querySelectorAll('.product-table tbody tr');
    rows.forEach(row => {
      const cells = Array.from(row.cells);
      const matches = cells.some(td => td.textContent.toLowerCase().includes(query));
      row.style.display = matches || query === '' ? '' : 'none';
    });
  }

  const orderSearch = document.getElementById('order-search');
  if (orderSearch) {
    orderSearch.addEventListener('input', debounce(filterOrders, 300));
  }

  const productSearch = document.getElementById('productSearch');
  if (productSearch) {
    productSearch.addEventListener('input', debounce(filterProducts, 300));
  }
});
