<header class="border-b bg-white">
  <div class="mx-auto flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
    <a href="<?php echo e(route('home')); ?>" class="text-lg font-semibold text-gray-900">PolyCrochet</a>
    <nav class="hidden items-center gap-6 text-sm font-medium text-gray-600 md:flex">
      <a href="<?php echo e(route('home')); ?>" class="<?php echo e(request()->routeIs('home') ? 'text-blue-600' : 'hover:text-blue-600'); ?>">Inicio</a>
      <a href="<?php echo e(route('catalog')); ?>" class="<?php echo e(request()->routeIs('catalog') ? 'text-blue-600' : 'hover:text-blue-600'); ?>">Catálogo</a>
      <a href="<?php echo e(route('nosotros')); ?>" class="<?php echo e(request()->routeIs('nosotros') ? 'text-blue-600' : 'hover:text-blue-600'); ?>">Nosotros</a>
      <a href="<?php echo e(route('account')); ?>" class="<?php echo e(request()->routeIs('account') ? 'text-blue-600' : 'hover:text-blue-600'); ?>">Cuenta</a>
      <a href="<?php echo e(route('cart')); ?>" class="<?php echo e(request()->routeIs('cart') ? 'text-blue-600' : 'hover:text-blue-600'); ?>">Carrito</a>
    </nav>
    <button type="button" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-600 hover:border-blue-400 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 md:hidden">
      Menú
    </button>
  </div>
</header>
<?php /**PATH C:\Users\Alexis\Desktop\polycrochet\resources\views/components/navbar.blade.php ENDPATH**/ ?>