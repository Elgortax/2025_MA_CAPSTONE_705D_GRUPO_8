<?php $__env->startSection('title', 'Productos | PolyCrochet'); ?>
<?php $__env->startSection('page_heading', 'Productos'); ?>

<?php $__env->startSection('content'); ?>
  <section class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-semibold text-white">Gesti�n de productos</h1>
        <p class="text-sm text-slate-400">Administra fichas, categor�as y disponibilidad.</p>
      </div>
      <div class="flex gap-3">
        <button type="button" class="inline-flex items-center rounded-lg border border-slate-700 px-4 py-2 text-sm font-semibold text-slate-300 hover:border-blue-500 hover:text-blue-400">Importar cat�logo</button>
        <button type="button" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">Nuevo producto</button>
      </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/60">
      <table class="min-w-full divide-y divide-slate-800 text-sm">
        <thead class="bg-slate-900/70 text-left text-xs uppercase tracking-wide text-slate-400">
          <tr>
            <th class="px-4 py-3">Producto</th>
            <th class="px-4 py-3">Categor�a</th>
            <th class="px-4 py-3">Precio</th>
            <th class="px-4 py-3">Stock</th>
            <th class="px-4 py-3">Estado</th>
            <th class="px-4 py-3 text-right">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-800 text-slate-300">
          <?php $__currentLoopData = [
            ['Bufanda multicolor', 'Accesorios', '$24.990', 'Disponible', 'Publicado'],
            ['Deco mural boho', 'Decoraci�n', '$18.500', 'Bajo', 'Publicado'],
            ['Amigurumi osito', 'Juguetes', '$15.900', 'Sin stock', 'Borrador']
          ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td class="px-4 py-3 font-semibold text-white"><?php echo e($producto[0]); ?></td>
              <td class="px-4 py-3"><?php echo e($producto[1]); ?></td>
              <td class="px-4 py-3"><?php echo e($producto[2]); ?></td>
              <td class="px-4 py-3"><?php echo e($producto[3]); ?></td>
              <td class="px-4 py-3">
                <span class="rounded-full bg-blue-500/10 px-2 py-0.5 text-xs font-semibold text-blue-300"><?php echo e($producto[4]); ?></span>
              </td>
              <td class="px-4 py-3 text-right">
                <button type="button" class="text-xs font-semibold text-blue-300 hover:text-blue-200">Editar</button>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
      </table>
    </div>
  </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Alexis\Desktop\polycrochet\resources\views/admin/products/index.blade.php ENDPATH**/ ?>