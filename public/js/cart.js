/**
 * public/js/cart.js
 *
 * Shared "add to cart" behavior for the product page and product cards.
 * Talks to CartController@add / CartController@count and reports back
 * to whatever page includes it via custom events, so each page can
 * decide how to show its own login modal / toast / cart badge:
 *
 *   cart:guest  — visitor isn't logged in; show the login modal
 *   cart:added  — item was added; detail = { message, cart_count, cart_quantity }
 *   cart:error  — something went wrong; detail = { message }
 */
(function () {
  const script = document.currentScript;
  const addUrl = script?.dataset.addUrl || '/cart/add';
  const countUrl = script?.dataset.countUrl || '/cart/count';

  function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  }

  function updateCartBadges(count) {
    document.querySelectorAll('.cart-badge').forEach((el) => {
      el.textContent = count;
    });
  }

  function addToCart(productId, quantity, triggerEl) {
    if (!productId) return;

    if (triggerEl) {
      triggerEl.disabled = true;
      triggerEl.classList.add('is-loading');
    }

    fetch(addUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        'X-CSRF-TOKEN': csrfToken(),
      },
      body: JSON.stringify({ product_id: productId, quantity: quantity || 1 }),
    })
      .then(async (res) => {
        const data = await res.json().catch(() => ({}));

        if (res.status === 401 || data.status === 'guest') {
          document.dispatchEvent(new CustomEvent('cart:guest', { detail: data }));
          return;
        }

        if (!res.ok) {
          document.dispatchEvent(new CustomEvent('cart:error', { detail: data }));
          return;
        }

        updateCartBadges(data.cart_count ?? 0);
        document.dispatchEvent(new CustomEvent('cart:added', { detail: data }));
      })
      .catch(() => {
        document.dispatchEvent(
          new CustomEvent('cart:error', { detail: { message: 'Network error. Please try again.' } })
        );
      })
      .finally(() => {
        if (triggerEl) {
          triggerEl.disabled = false;
          triggerEl.classList.remove('is-loading');
        }
      });
  }

  function refreshCartCount() {
    fetch(countUrl, { headers: { Accept: 'application/json' } })
      .then((res) => res.json())
      .then((data) => updateCartBadges(data.cart_count ?? 0))
      .catch(() => {});
  }

  // Delegate from document so this works for cards rendered inside
  // carousels, search results, or anything added to the page later.
  document.addEventListener('click', function (e) {
    const addBtn = e.target.closest('.js-add-to-cart');
    if (addBtn) {
      e.preventDefault();
      e.stopPropagation();

      const productId = addBtn.dataset.productId;
      const qtyTarget = addBtn.dataset.qtyTarget;
      const qtyInput = qtyTarget ? document.querySelector(qtyTarget) : null;
      const quantity = qtyInput
        ? parseInt(qtyInput.value, 10) || 1
        : parseInt(addBtn.dataset.quantity || '1', 10);

      addToCart(productId, quantity, addBtn);
      return;
    }

    const qtyBtn = e.target.closest('.js-qty-btn');
    if (qtyBtn) {
      e.preventDefault();
      e.stopPropagation();

      const target = document.querySelector(qtyBtn.dataset.qtyTarget);
      if (!target) return;

      const min = parseInt(target.min || '1', 10);
      const max = parseInt(target.max || '99', 10);
      let value = parseInt(target.value, 10) || min;
      value += qtyBtn.dataset.qtyStep === 'minus' ? -1 : 1;
      target.value = Math.max(min, Math.min(max, value));
    }
  });

  window.CartUI = { addToCart, refreshCartCount, updateCartBadges };

  document.addEventListener('DOMContentLoaded', refreshCartCount);
})();