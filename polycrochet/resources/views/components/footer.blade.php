<footer class="relative border-t border-rose-100/80 bg-white/90">
  <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(249,213,220,0.35),transparent_60%)]"></div>
  <div class="relative mx-auto w-full max-w-7xl px-4 py-14 sm:px-6 lg:px-10">
    <div class="grid gap-10 lg:grid-cols-[1.4fr,1fr,1fr,1fr]">
      <div class="space-y-4">
        <span class="inline-flex items-center gap-3 text-lg font-semibold text-rose-700">
          <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-rose-300 via-rose-400 to-amber-300 text-base font-bold text-white shadow-lg shadow-rose-200/60">PC</span>
          PolyCrochet Studio
        </span>
        <p class="max-w-sm text-sm text-slate-600">Diseñamos experiencias tejidas con amor, colores suaves y acabados premium para regalos inolvidables.</p>
        <div class="flex items-center gap-4 text-xs uppercase tracking-[0.18em] text-rose-400">
          <span>Hecho en Chile</span>
          <span class="h-1 w-1 rounded-full bg-rose-300"></span>
          <span>Envíos a todo el país</span>
        </div>
      </div>

      <div class="space-y-3">
        <h4 class="text-xs font-semibold uppercase tracking-[0.25em] text-rose-500">Explorar</h4>
        <ul class="space-y-2 text-sm text-slate-600">
          <li><a href="{{ route('catalog') }}" class="transition hover:text-rose-500">Catálogo completo</a></li>
          <li><a href="{{ route('nosotros') }}#colecciones" class="transition hover:text-rose-500">Colecciones</a></li>
          <li><a href="{{ route('nosotros') }}" class="transition hover:text-rose-500">Nuestro proceso</a></li>
        </ul>
      </div>

      <div class="space-y-3">
        <h4 class="text-xs font-semibold uppercase tracking-[0.25em] text-rose-500">Soporte</h4>
        <ul class="space-y-2 text-sm text-slate-600">
          <li><a href="{{ route('nosotros') }}#faq" class="transition hover:text-rose-500">Preguntas frecuentes</a></li>
          <li><a href="{{ route('nosotros') }}#politicas" class="transition hover:text-rose-500">Políticas de envío</a></li>
          <li><a href="mailto:hola@polycrochet.cl" class="transition hover:text-rose-500">hola@polycrochet.cl</a></li>
          <li><a href="tel:+56912345678" class="transition hover:text-rose-500">+56 9 1234 5678</a></li>
        </ul>
      </div>

      <div class="space-y-3">
        <h4 class="text-xs font-semibold uppercase tracking-[0.25em] text-rose-500">Conecta</h4>
        <div class="flex flex-wrap gap-3">
          @foreach (['Instagram', 'TikTok', 'Pinterest'] as $network)
            <a href="#" class="inline-flex items-center justify-center rounded-full border border-rose-200 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-rose-500 transition hover:border-rose-300 hover:text-rose-600">{{ $network }}</a>
          @endforeach
        </div>
      </div>
    </div>

    <div class="mt-12 flex flex-col gap-3 border-t border-rose-100 pt-6 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
      <span>&copy; {{ date('Y') }} PolyCrochet Studio. Todas las piezas son hechas a mano.</span>
      <a href="{{ route('admin.dashboard') }}" class="uppercase tracking-[0.18em] text-rose-400 transition hover:text-rose-600">Panel administrador</a>
    </div>
  </div>
</footer>
