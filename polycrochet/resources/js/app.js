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
      cartPanelItems.innerHTML = '<p class="text-sm text-slate-500">Tu carrito está vacío.</p>';
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

  const registerForm = document.querySelector('[data-register-form]');
  if (registerForm) {
    const submitButton = registerForm.querySelector('[data-register-submit]');
    const firstNameInput = registerForm.querySelector('#first_name');
    const lastNameInput = registerForm.querySelector('#last_name');
    const emailInput = registerForm.querySelector('#email');
    const phoneInput = registerForm.querySelector('[data-phone]');
    const passwordInput = registerForm.querySelector('[data-password]');
    const confirmationInput = registerForm.querySelector('[data-password-confirmation]');

    const firstNameMessage = registerForm.querySelector('[data-first-name-error]');
    const lastNameMessage = registerForm.querySelector('[data-last-name-error]');
    const emailMessage = registerForm.querySelector('[data-email-error]');
    const phoneMessage = registerForm.querySelector('[data-phone-error]');
    const passwordLengthMessage = registerForm.querySelector('[data-password-length-error]');
    const passwordMatchMessage = registerForm.querySelector('[data-password-match-error]');

    const MIN_PASSWORD_LENGTH = 8;
    const NAME_ALLOWED_CHARS = /[^a-zA-ZÁÉÍÓÚáéíóúÑñÜü\s'-]/g;
    const NAME_VALIDATION_REGEX = /^[a-zA-ZÁÉÍÓÚáéíóúÑñÜü\s'-]+$/;
    const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    const fieldErrors = {
      firstName: false,
      lastName: false,
      email: false,
      phone: false,
      password: false,
      passwordConfirmation: false,
    };

    const setInputErrorState = (input, hasError) => {
      if (!input) return;
      input.classList.toggle('border-rose-500', hasError);
      input.classList.toggle('focus:border-rose-500', hasError);
      input.classList.toggle('border-rose-200', !hasError);
    };

    const setMessageVisibility = (element, isVisible) => {
      if (!element) return;
      element.classList.toggle('hidden', !isVisible);
    };

    const toggleSubmitState = (hasErrors) => {
      if (!submitButton) return;
      submitButton.disabled = hasErrors;
      submitButton.classList.toggle('opacity-70', hasErrors);
      submitButton.classList.toggle('cursor-not-allowed', hasErrors);
    };

    const updateSubmitState = () => {
      const hasErrors = Object.values(fieldErrors).some(Boolean);
      toggleSubmitState(hasErrors);
    };

    const sanitizeName = (input) => {
      if (!input) return '';
      const sanitized = input.value.replace(NAME_ALLOWED_CHARS, '');
      if (sanitized !== input.value) {
        input.value = sanitized;
      }
      return sanitized.trim();
    };

    const updateNameFeedback = (input, messageElement, key, force = false) => {
      if (!input) return false;
      const value = sanitizeName(input);
      const showError = value.length > 0
        ? !NAME_VALIDATION_REGEX.test(value)
        : force;

      setInputErrorState(input, showError);
      setMessageVisibility(messageElement, showError);
      fieldErrors[key] = showError;
      updateSubmitState();
      return showError;
    };

    const updateEmailFeedback = (force = false) => {
      if (!emailInput) return false;
      const value = emailInput.value.trim();
      const showError = value.length > 0
        ? !EMAIL_REGEX.test(value)
        : force;

      setInputErrorState(emailInput, showError);
      setMessageVisibility(emailMessage, showError);
      fieldErrors.email = showError;
      updateSubmitState();
      return showError;
    };

    const updatePhoneFeedback = (force = false) => {
      if (!phoneInput) return false;
      const digits = (phoneInput.value ?? '').replace(/\D/g, '').slice(0, 9);
      if (digits !== phoneInput.value) {
        phoneInput.value = digits;
      }

      const showError = force
        ? digits.length !== 9
        : digits.length > 0 && digits.length !== 9;

      setInputErrorState(phoneInput, showError);
      setMessageVisibility(phoneMessage, showError);
      fieldErrors.phone = showError;
      updateSubmitState();
      return showError;
    };

    const updatePasswordFeedback = (force = false) => {
      const passwordValue = passwordInput?.value ?? '';
      const confirmationValue = confirmationInput?.value ?? '';

      const passwordTooShort = passwordValue.length < MIN_PASSWORD_LENGTH;
      const showPasswordError = force ? passwordTooShort : passwordValue.length > 0 && passwordTooShort;

      const confirmationMissing = confirmationValue.length === 0;
      const passwordsDiffer = passwordValue !== confirmationValue;
      const showConfirmationError = force ? (confirmationMissing || passwordsDiffer) : (!confirmationMissing && passwordsDiffer);

      setInputErrorState(passwordInput, showPasswordError);
      setMessageVisibility(passwordLengthMessage, showPasswordError);
      fieldErrors.password = showPasswordError;

      setInputErrorState(confirmationInput, showConfirmationError);
      setMessageVisibility(passwordMatchMessage, showConfirmationError);
      fieldErrors.passwordConfirmation = showConfirmationError;

      updateSubmitState();
      return showPasswordError || showConfirmationError;
    };

    registerForm.addEventListener('input', (event) => {
      const target = event.target;

      if (target === firstNameInput) {
        updateNameFeedback(firstNameInput, firstNameMessage, 'firstName');
      } else if (target === lastNameInput) {
        updateNameFeedback(lastNameInput, lastNameMessage, 'lastName');
      } else if (target === emailInput) {
        updateEmailFeedback(false);
      } else if (target === phoneInput) {
        updatePhoneFeedback(false);
      }

      if (target === passwordInput || target === confirmationInput) {
        updatePasswordFeedback(false);
      }
    });

    registerForm.addEventListener('submit', (event) => {
      const hasNameErrors = [
        updateNameFeedback(firstNameInput, firstNameMessage, 'firstName', true),
        updateNameFeedback(lastNameInput, lastNameMessage, 'lastName', true),
      ].some(Boolean);

      const hasEmailErrors = updateEmailFeedback(true);
      const hasPhoneErrors = updatePhoneFeedback(true);
      const hasPasswordErrors = updatePasswordFeedback(true);

      if (hasNameErrors || hasEmailErrors || hasPhoneErrors || hasPasswordErrors) {
        event.preventDefault();
        const firstErrorField = [
          firstNameInput,
          lastNameInput,
          emailInput,
          phoneInput,
          passwordInput,
          confirmationInput,
        ].find((input) => input && input.classList.contains('border-rose-500'));

        firstErrorField?.focus();
      }
    });

    updateNameFeedback(firstNameInput, firstNameMessage, 'firstName');
    updateNameFeedback(lastNameInput, lastNameMessage, 'lastName');
    updateEmailFeedback(false);
    updatePhoneFeedback(false);
    updatePasswordFeedback(false);
  }

  let cachedLocations = null;

  const getLocationsDataset = () => {
    if (cachedLocations !== null) {
      return cachedLocations;
    }

    const script = document.getElementById('chile-locations');
    if (!script) {
      cachedLocations = [];
      return cachedLocations;
    }

    try {
      const parsed = JSON.parse(script.textContent ?? '[]');
      cachedLocations = Array.isArray(parsed) ? parsed : [];
    } catch (error) {
      cachedLocations = [];
    }

    return cachedLocations;
  };

  const initAddressForm = (form, regions) => {
    const regionSelect = form.querySelector('[data-region-select]');
    const communeSelect = form.querySelector('[data-commune-select]');

    if (!regionSelect || !communeSelect) {
      return;
    }

    const renderPlaceholder = () => {
      communeSelect.innerHTML = '';
      const placeholder = document.createElement('option');
      placeholder.value = '';
      placeholder.textContent = 'Selecciona una comuna';
      communeSelect.appendChild(placeholder);
    };

    const sortByName = (items) => {
      return [...items].sort((a, b) => a.name.localeCompare(b.name, 'es'));
    };

    const fillCommunes = (regionId, selectedCommuneId = null) => {
      renderPlaceholder();

      if (!regionId) {
        communeSelect.disabled = true;
        return;
      }

      const region = regions.find((item) => String(item.id) === String(regionId));

      if (!region || !Array.isArray(region.communes)) {
        communeSelect.disabled = true;
        return;
      }

      sortByName(region.communes).forEach((commune) => {
        const option = document.createElement('option');
        option.value = commune.id;
        option.textContent = commune.name;
        communeSelect.appendChild(option);
      });

      communeSelect.disabled = false;

      if (selectedCommuneId && region.communes.some(
        (commune) => String(commune.id) === String(selectedCommuneId)
      )) {
        communeSelect.value = String(selectedCommuneId);
      } else {
        communeSelect.value = '';
      }
    };

    const defaultRegion = regionSelect.dataset.defaultValue || regionSelect.value || '';
    const defaultCommune = communeSelect.dataset.defaultValue || communeSelect.value || '';

    if (defaultRegion) {
      regionSelect.value = String(defaultRegion);
      fillCommunes(defaultRegion, defaultCommune);
    } else {
      renderPlaceholder();
      communeSelect.disabled = true;
    }

    regionSelect.addEventListener('change', (event) => {
      communeSelect.dataset.defaultValue = '';
      fillCommunes(event.target.value);
    });
  };

  const addressForms = document.querySelectorAll('[data-address-form]');
  if (addressForms.length) {
    const locations = getLocationsDataset();
    addressForms.forEach((form) => initAddressForm(form, locations));
  }

  const addressEditors = document.querySelectorAll('[data-address-toggle]');
  if (addressEditors.length) {
    addressEditors.forEach((button) => {
      const targetSelector = button.dataset.target;
      if (!targetSelector) {
        return;
      }

      const target = document.querySelector(targetSelector);
      if (!target) {
        return;
      }

      const openText = button.dataset.openText || button.textContent.trim();
      const closeText = button.dataset.closeText || openText;
      const focusable = target.querySelector('input, select, textarea');

      const setState = (isOpen) => {
        button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        button.textContent = isOpen ? closeText : openText;
      };

      const initiallyOpen = !target.classList.contains('hidden');
      setState(initiallyOpen);

      button.addEventListener('click', () => {
        const isHidden = target.classList.toggle('hidden');
        const isOpen = !isHidden;
        setState(isOpen);
        if (isOpen && focusable) {
          focusable.focus();
        }
      });
    });
  }

  const addressRadios = document.querySelectorAll('[data-address-radio]');
  if (addressRadios.length) {
    addressRadios.forEach((radio) => {
      radio.addEventListener('change', () => {
        const form = radio.closest('form');
        if (form) {
          form.submit();
        }
      });
    });
  }

  fetchSummary();
});
