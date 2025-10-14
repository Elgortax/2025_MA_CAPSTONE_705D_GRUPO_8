<div id="floating-cart" class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3">
  <button id="cart-toggle" type="button" class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-white px-4 py-2 text-sm font-semibold text-rose-600 shadow-lg shadow-rose-200/60 transition hover:border-rose-300 hover:bg-rose-50">
    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-rose-400 to-amber-300 text-sm font-semibold text-white shadow shadow-rose-200/60">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M6 8h12l-1 11H7L6 8Z"></path>
        <path d="M9 8a3 3 0 1 1 6 0"></path>
      </svg>
    </span>
    <span>Carrito</span>
    <span id="cart-count" class="inline-flex min-w-[2rem] items-center justify-center rounded-full bg-rose-500 px-2 py-0.5 text-xs font-bold text-white">0</span>
  </button>

  <div id="cart-panel" class="hidden w-[360px] max-w-[90vw] overflow-hidden rounded-[2rem] border border-rose-100 bg-white/95 p-5 shadow-2xl shadow-rose-200/70 backdrop-blur">
    <div class="flex items-start justify-between">
      <div>
        <h2 class="text-base font-semibold text-rose-700">Tu carrito</h2>
        <p class="text-xs text-slate-500">Revisa tus productos antes de completar la compra.</p>
      </div>
      <button type="button" id="cart-close" class="text-rose-300 transition hover:text-rose-500" aria-label="Cerrar carrito">
        <span aria-hidden="true">×</span>
      </button>
    </div>

    <div id="cart-panel-items" class="mt-4 max-h-72 space-y-4 overflow-y-auto pr-1">
      <p class="text-sm text-slate-500">Tu carrito está vacío.</p>
    </div>

    <div id="cart-panel-summary" class="mt-4 space-y-2 border-t border-rose-100 pt-4 text-sm text-slate-600">
      <div class="flex justify-between">
        <span>Subtotal</span>
        <span id="cart-panel-subtotal">$0</span>
      </div>
      <div class="flex justify-between text-base font-semibold text-rose-600">
        <span>Total</span>
        <span id="cart-panel-total">$0</span>
      </div>
    </div>

    <a href="{{ route('cart') }}" class="mt-4 inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-rose-400 to-amber-300 px-5 py-2.5 text-sm font-semibold text-white transition hover:from-rose-500 hover:to-amber-400">
      Ir al carrito
    </a>
  </div>
</div>
