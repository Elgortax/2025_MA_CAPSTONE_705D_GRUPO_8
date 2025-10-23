<footer class="border-t border-rose-100/70 bg-white/95 py-12">
  <div class="mx-auto grid w-full max-w-7xl gap-8 px-4 text-sm text-slate-500 md:grid-cols-2 lg:grid-cols-4 lg:items-start sm:px-6 lg:px-10">
    <div class="space-y-3">
      <p class="text-base font-semibold text-rose-600">PolyCrochet Studio</p>
      <p class="text-sm text-slate-500">Tejemos ideas personalizadas desde Santiago y enviamos a todo Chile.</p>
      <p class="text-xs text-slate-400">&copy; {{ date('Y') }} PolyCrochet Studio. Piezas únicas hechas a mano.</p>
    </div>

    <div class="space-y-3">
      <p class="text-xs font-semibold uppercase tracking-[0.22em] text-rose-400">Contacto</p>
      <div class="space-y-2">
        <div>
          <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Correo</p>
          <a href="mailto:soporte@polycrochet.cl" class="font-medium text-rose-500 hover:text-rose-600">soporte@polycrochet.cl</a>
        </div>
        <div>
          <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Teléfono</p>
          <a href="tel:+56959307085" class="font-medium text-rose-500 hover:text-rose-600">+56 9 5930 7085</a>
        </div>
      </div>
    </div>

    <div class="space-y-2 max-w-[220px]">
      <p class="text-xs font-semibold uppercase tracking-[0.22em] text-rose-400">Redes</p>
      <a href="https://www.instagram.com/polycrochet_1975?igsh=MTRtZThpOWt5bGp4NQ==" class="group flex items-center gap-2 rounded-full border border-rose-200 px-3 py-2 text-rose-500 transition hover:border-rose-300 hover:text-rose-600" target="_blank" rel="noopener">
        <img src="{{ asset('img/social/instagram.png') }}" alt="Instagram PolyCrochet Studio" class="h-5 w-5 object-contain">
        <span class="text-sm font-medium">Instagram</span>
      </a>
      <a href="https://www.facebook.com/share/15g3Ht633G/?mibextid=wwXIfr" class="group flex items-center gap-2 rounded-full border border-rose-200 px-3 py-2 text-rose-500 transition hover:border-rose-300 hover:text-rose-600" target="_blank" rel="noopener">
        <img src="{{ asset('img/social/facebook.png') }}" alt="Facebook PolyCrochet Studio" class="h-5 w-5 object-contain">
        <span class="text-sm font-medium">Facebook</span>
      </a>
      <a href="https://wa.me/56959307085" class="group flex items-center gap-2 rounded-full border border-rose-200 px-3 py-2 text-rose-500 transition hover:border-rose-300 hover:text-rose-600" target="_blank" rel="noopener">
        <img src="{{ asset('img/social/whatsapp.png') }}" alt="WhatsApp PolyCrochet Studio" class="h-5 w-5 object-contain">
        <span class="text-sm font-medium">WhatsApp</span>
      </a>
    </div>

    <div class="space-y-3">
      <p class="text-xs font-semibold uppercase tracking-[0.22em] text-rose-400">Ubicación</p>
      <div>
        <p class="text-sm text-slate-500">Casas de Lo Espejo 2956<br>Maipú, Santiago, Chile</p>
      </div>
      <div class="overflow-hidden rounded-2xl border border-rose-100 shadow-lg max-w-[260px]">
        <iframe
          title="Mapa de PolyCrochet Studio"
          src="https://maps.google.com/maps?q=Casas%20de%20Lo%20Espejo%202956%2C%20Maip%C3%BA&t=&z=16&ie=UTF8&iwloc=&output=embed"
          class="h-28 w-full border-0"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <a href="https://www.google.com/maps/search/?api=1&query=Casas+de+Lo+Espejo+2956+Maipu" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm font-semibold text-rose-500 hover:text-rose-600">
      </a>
    </div>
  </div>
</footer>
