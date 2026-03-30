<!-- Modal de Mensajes Dinámico -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <!-- Header del Modal -->
            <div class="modal-header border-0" id="modalHeader">
                <h5 class="modal-title" id="messageTitle">
                    <i id="messageIcon" class="bi me-2"></i>
                    <span id="messageTitleText">Mensaje</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!-- Cuerpo del Modal -->
            <div class="modal-body">
                <p id="messageBody" style="font-size: 1rem; line-height: 1.6;"></p>
            </div>

            <!-- Footer del Modal -->
            <div class="modal-footer border-0">
                <button type="button" 
                        class="btn btn-primary" 
                        data-bs-dismiss="modal"
                        id="messageButton">
                    Entendido
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Función para mostrar modal de mensajes dinámico
     * @param {string} title - Título del modal
     * @param {string} message - Mensaje del modal
     * @param {string} type - Tipo: 'success', 'error', 'info', 'warning'
     * @param {function} callback - Callback al cerrar (opcional)
     * @param {number} autoCloseTime - Tiempo en ms para cerrar automáticamente (0 = manual)
     */
    function showMessageModal(title, message, type = 'info', callback = null, autoCloseTime = 3000) {
        const modal = new bootstrap.Modal(document.getElementById('messageModal'));
        const modalHeader = document.getElementById('modalHeader');
        const messageTitle = document.getElementById('messageTitleText');
        const messageIcon = document.getElementById('messageIcon');
        const messageBody = document.getElementById('messageBody');
        const messageButton = document.getElementById('messageButton');

        // Configurar mensaje
        messageTitle.textContent = title;
        messageBody.innerHTML = message;

        // Configurar estilos según tipo
        const colorMap = {
            'success': { bg: 'bg-success', icon: 'bi-check-circle', btn: 'btn-success' },
            'error': { bg: 'bg-danger', icon: 'bi-exclamation-circle', btn: 'btn-danger' },
            'warning': { bg: 'bg-warning', icon: 'bi-exclamation-triangle', btn: 'btn-warning' },
            'info': { bg: 'bg-info', icon: 'bi-info-circle', btn: 'btn-info' }
        };

        const config = colorMap[type] || colorMap['info'];

        // Limpiar clases anteriores
        modalHeader.className = 'modal-header border-0';
        messageButton.className = `btn ${config.btn}`;
        messageIcon.className = `bi ${config.icon}`;

        // Agregar clase de fondo
        modalHeader.classList.add(config.bg, 'text-white');
        messageTitle.style.color = 'white';
        messageIcon.style.color = 'white';

        // Mostrar modal
        modal.show();

        // Callback al cerrar
        if (callback && typeof callback === 'function') {
            const offcanvas = document.getElementById('messageModal');
            offcanvas.addEventListener('hidden.bs.modal', callback, { once: true });
        }

        // Auto-cerrar el modal si autoCloseTime > 0
        if (autoCloseTime > 0) {
            setTimeout(function() {
                modal.hide();
            }, autoCloseTime);
        }
    }

    /**
     * Detectar mensajes de sesión y mostrar modal automáticamente
     */
    document.addEventListener('DOMContentLoaded', function() {
        // Buscar mensajes en HTML (generados desde Laravel)
        const successMessage = document.querySelector('[data-message-success]');
        const errorMessage = document.querySelector('[data-message-error]');
        const warningMessage = document.querySelector('[data-message-warning]');
        const infoMessage = document.querySelector('[data-message-info]');

        // Success: se cierra automáticamente en 3 segundos
        if (successMessage) {
            const msg = successMessage.getAttribute('data-message-success');
            showMessageModal('✅ ¡Éxito!', msg, 'success', null, 3000);
        } 
        // Error: requiere click (autoCloseTime = 0)
        else if (errorMessage) {
            const msg = errorMessage.getAttribute('data-message-error');
            showMessageModal('❌ Error', msg, 'error', null, 0);
        } 
        // Warning: se cierra en 4 segundos
        else if (warningMessage) {
            const msg = warningMessage.getAttribute('data-message-warning');
            showMessageModal('⚠️ Advertencia', msg, 'warning', null, 4000);
        } 
        // Info: se cierra en 3 segundos
        else if (infoMessage) {
            const msg = infoMessage.getAttribute('data-message-info');
            showMessageModal('ℹ️ Información', msg, 'info', null, 3000);
        }
    });
</script>
