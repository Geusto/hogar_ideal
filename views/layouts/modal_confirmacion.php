<?php
// Variables esperadas:
// $modal_id: string (ID único para el modal)
// $mensaje: string (mensaje a mostrar)
// $texto_confirmar: string (texto del botón de confirmar)
// $texto_cancelar: string (texto del botón de cancelar)
// $url_accion: string (URL a la que redirigir al confirmar)
?>
<style>
  .modal-fade-enter {
    opacity: 0;
    transform: scale(0.95);
    transition: opacity 0.2s ease, transform 0.2s ease;
  }
  .modal-fade-enter-active {
    opacity: 1;
    transform: scale(1);
  }
  .modal-fade-leave {
    opacity: 1;
    transform: scale(1);
    transition: opacity 0.15s ease, transform 0.15s ease;
  }
  .modal-fade-leave-active {
    opacity: 0;
    transform: scale(0.95);
  }
</style>
<div id="<?= htmlspecialchars($modal_id) ?>" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
  <div class="bg-white rounded-lg shadow-lg p-8 max-w-sm w-full transform modal-fade-enter">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Confirmar acción</h2>
    <p class="mb-6 text-gray-600"><?= htmlspecialchars($mensaje) ?></p>
    <div class="flex justify-end gap-4">
      <button type="button" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded btn-cancelar-modal" data-modal="<?= htmlspecialchars($modal_id) ?>">
        <?= htmlspecialchars($texto_cancelar ?? 'Cancelar') ?>
      </button>
      <button type="button" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded btn-confirmar-modal" data-modal="<?= htmlspecialchars($modal_id) ?>" data-url="<?= htmlspecialchars($url_accion) ?>">
        <?= htmlspecialchars($texto_confirmar ?? 'Confirmar') ?>
      </button>
    </div>
  </div>
</div>
<script>
// Animación de aparición/desaparición del modal
function showModal(modalId) {
  const modal = document.getElementById(modalId);
  const box = modal.querySelector('.modal-fade-enter');
  modal.classList.remove('hidden');
  box.classList.remove('modal-fade-leave', 'modal-fade-leave-active');
  box.classList.add('modal-fade-enter');
  setTimeout(() => {
    box.classList.add('modal-fade-enter-active');
  }, 10);
}
function hideModal(modalId) {
  const modal = document.getElementById(modalId);
  const box = modal.querySelector('.modal-fade-enter');
  box.classList.remove('modal-fade-enter', 'modal-fade-enter-active');
  box.classList.add('modal-fade-leave');
  setTimeout(() => {
    box.classList.add('modal-fade-leave-active');
    setTimeout(() => {
      modal.classList.add('hidden');
      box.classList.remove('modal-fade-leave', 'modal-fade-leave-active');
      box.classList.add('modal-fade-enter');
    }, 150);
  }, 10);
}
</script> 