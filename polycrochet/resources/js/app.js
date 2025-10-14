import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const floatingCart = document.getElementById('floating-cart');
  const cartToggle = document.getElementById('cart-toggle');
  const cartClose = document.getElementById('cart-close');
  const cartPanel = document.getElementById('cart-panel');
  const cartPanelItems = document.getElementById('cart-panel-items');
  const panelSubtotal = document.getElementById('cart-panel-subtotal');
  const panelTotal = document.getElementById('cart-panel-total');
  const cartCount = document.getElementById('cart-count');
  const cartPage = document.querySelector('[data-cart-page]');

  const state = {
    summary: null,
  };

  const request = (url, { method = 'GET', body = null } = {}) => {
    const headers = {
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    };

    if (method !== 'GET') {
      headers['X-CSRF-TOKEN'] = csrfToken;
    }

    if (body && !(body instanceof FormData)) {
      headers['Content-Type'] = 'application/json';
      body = JSON.stringify(body);
    }

    return fetch(url, { method, headers, body });
  };

  const fetchSummary = () => {
    return request('/carrito/resumen')
      .then(response => response.ok ? response.json() : Promise.reject())
      .then(summary => {
        state.summary = summary;
        updateInterfaces(summary);
      })
      .catch(() => {
        // fallback: ignore silently
      });
  };

  const togglePanel = () => {
    if (!cartPanel) return;
    cartPanel.classList.toggle('hidden');
  };

  const closePanel = () => {
    if (!cartPanel) return;
    cartPanel.classList.add('hidden');
  };

  const openPanel = () => {
    if (!cartPanel) return;
    cartPanel.classList.remove('hidden');
  };

  const debounce = (fn, delay = 250) => {
    let timer;
    return (...args) => {
      clearTimeout(timer);
      timer = setTimeout(() => fn(...args), delay);
    };
  };

  const updateInterfaces = (summary) => {
    updateCartCount(summary);
    updateFloatingCart(summary);
    updateCartPage(summary);
  };

  const updateCartCount = (summary) => {
    if (!cartCount) return;
    cartCount.textContent = summary.count ?? 0;
  };

  const updateFloatingCart = (summary) => {
    if (!floatingCart || !cartPanelItems) return;

    if (!summary.items.length) {
      cartPanelItems.innerHTML = '<p class="text-sm text-slate-500">Tu carrito est\u00E1 vac\u00EDo.</p>';
    } else {
      cartPanelItems.innerHTML = summary.items.map(item => `
        <article class="rounded-2xl border border-rose-100 bg-white/90 p-4 shadow" data-cart-panel-item data-product-id="${item.id}">
          <div class="grid grid-cols-[auto,1fr,auto] items-start gap-3">
            <div class="h-16 w-16 overflow-hidden rounded-xl border border-rose-100 bg-rose-50">
              ${item.image ? `<img src="${item.image}" alt="${item.name}" class="h-full w-full object-cover" />` : `<span class="flex h-full items-center justify-center text-base font-semibold text-rose-400">${item.name.charAt(0)}</span>`}
            </div>
            <div class="space-y-2 text-xs text-slate-500">
              <a href="${item.url}" class="text-sm font-semibold text-rose-600 hover:text-rose-500 transition">${item.name}</a>
              <p>${item.price_formatted} c/u</p>
              <label class="flex items-center gap-2">
                Cantidad
                <input type="number" min="1" max="10" value="${item.quantity}" class="h-8 w-16 rounded-full border border-rose-200 px-2 text-center focus:border-rose-400 focus:outline-none focus:ring-1 focus:ring-rose-300" data-cart-panel-quantity data-product-id="${item.id}">
              </label>
            </div>
            <div class="flex flex-col items-end gap-2 text-xs">
              <span class="font-semibold text-rose-600">${item.subtotal_formatted}</span>
              <button type="button" class="text-rose-500 hover:text-rose-600" data-cart-panel-remove data-product-id="${item.id}">Eliminar</button>
            </div>
          </div>
        </article>
      `).join('');
    }

    if (panelSubtotal) panelSubtotal.textContent = summary.subtotal_formatted;
    if (panelTotal) panelTotal.textContent = summary.total_formatted;
  };

  const updateCartPage = (summary) => {
    if (!cartPage) return;

    const renderedItems = cartPage.querySelectorAll('[data-cart-item]');
    if (renderedItems.length !== summary.items.length) {
      // Items added or removed: easiest is to refresh.
      window.location.reload();
      return;
    }

    const itemsMap = new Map(summary.items.map(item => [String(item.id), item]));

    renderedItems.forEach(node => {
      const productId = node.dataset.productId;
      const data = itemsMap.get(productId);
      if (!data) {
        node.remove();
        return;
      }

      const subtotalEl = node.querySelector('[data-cart-item-subtotal]');
      if (subtotalEl) {
        subtotalEl.textContent = data.subtotal_formatted;
      }

      const quantityInput = node.querySelector('[data-cart-quantity]');
      if (quantityInput) {
        quantityInput.value = data.quantity;
      }
    });

    const subtotalEl = cartPage.querySelector('[data-cart-subtotal]');
    const totalEl = cartPage.querySelector('[data-cart-total]');

    if (subtotalEl) subtotalEl.textContent = summary.subtotal_formatted;
    if (totalEl) totalEl.textContent = summary.total_formatted;
  };

  const handleAddToCart = (form) => {
    const productId = form.querySelector('[name="product_id"]').value;
    const quantityInput = form.querySelector('[name="quantity"]');
    const quantity = quantityInput ? parseInt(quantityInput.value, 10) || 1 : 1;

    request('/carrito', { method: 'POST', body: { product_id: Number(productId), quantity } })
      .then(response => response.ok ? response.json() : Promise.reject())
      .then(summary => {
        state.summary = summary;
        updateInterfaces(summary);
        openPanel();
      })
      .catch(() => {
        form.submit();
      });
  };

  const updateQuantity = (productId, quantity) => {
    if (quantity < 1) quantity = 1;
    request(`/carrito/${productId}`, { method: 'PATCH', body: { quantity } })
      .then(response => response.ok ? response.json() : Promise.reject())
      .then(summary => {
        state.summary = summary;
        updateInterfaces(summary);
      })
      .catch(() => {
        window.location.reload();
      });
  };

  const debouncedUpdateQuantity = debounce(updateQuantity, 250);

  const removeItem = (productId) => {
    request(`/carrito/${productId}`, { method: 'DELETE' })
      .then(response => response.ok ? response.json() : Promise.reject())
      .then(summary => {
        state.summary = summary;
        updateInterfaces(summary);
      })
      .catch(() => {
        window.location.reload();
      });
  };

  // Floating cart events
  if (cartToggle) {
    cartToggle.addEventListener('click', () => {
      togglePanel();
    });
  }

  if (cartClose) {
    cartClose.addEventListener('click', () => {
      closePanel();
    });
  }

  document.addEventListener('click', (event) => {
    if (!cartPanel || cartPanel.classList.contains('hidden') || !floatingCart) {
      return;
    }

    if (!floatingCart.contains(event.target)) {
      closePanel();
    }
  });

  if (cartPanelItems) {
    cartPanelItems.addEventListener('input', (event) => {
      const target = event.target;
      if (target.matches('[data-cart-panel-quantity]')) {
        const productId = target.dataset.productId;
        const quantity = parseInt(target.value, 10) || 1;
        debouncedUpdateQuantity(productId, quantity);
      }
    });

    cartPanelItems.addEventListener('click', (event) => {
      const removeBtn = event.target.closest('[data-cart-panel-remove]');
      if (removeBtn) {
        const productId = removeBtn.dataset.productId;
        removeItem(productId);
      }
    });
  }

  // Product page add-to-cart forms
  document.querySelectorAll('[data-add-to-cart-form]').forEach(form => {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      handleAddToCart(form);
    });
  });

  // Cart page quantity handling
  if (cartPage) {
    cartPage.addEventListener('input', (event) => {
      const input = event.target;
      if (input.matches('[data-cart-quantity]')) {
        const article = input.closest('[data-cart-item]');
        if (!article) return;
        const productId = article.dataset.productId;
        const quantity = parseInt(input.value, 10) || 1;
        debouncedUpdateQuantity(productId, quantity);
      }
    });

    cartPage.addEventListener('click', (event) => {
      const removeBtn = event.target.closest('[data-cart-remove]');
      if (removeBtn) {
        event.preventDefault();
        const article = removeBtn.closest('[data-cart-item]');
        if (!article) return;
        const productId = article.dataset.productId;
        removeItem(productId);
      }
    });
  }

  fetchSummary();
});
