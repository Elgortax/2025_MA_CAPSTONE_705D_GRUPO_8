<footer class="border-t border-rose-100/70 bg-white/90 py-10">
  <div class="mx-auto flex w-full max-w-7xl flex-col gap-8 px-4 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-10">
    <div>
      <p class="text-base font-semibold text-rose-600">PolyCrochet Studio</p>
      <p class="mt-1 text-xs text-slate-400">Diseñamos piezas con amor desde Santiago · Envíos a todo Chile.</p>
    </div>
    <div class="flex flex-wrap items-center gap-4">
      <a href="mailto:hola@polycrochet.cl" class="hover:text-rose-500">hola@polycrochet.cl</a>
      <a href="https://www.instagram.com" class="hover:text-rose-500" target="_blank" rel="noopener">Instagram</a>
      <a href="{{ route('nosotros') }}" class="hover:text-rose-500">Nosotros</a>
    </div>
    <span>&copy; {{ date('Y') }} PolyCrochet Studio. Todas las piezas son hechas a mano.</span>
  </div>
</footer>
