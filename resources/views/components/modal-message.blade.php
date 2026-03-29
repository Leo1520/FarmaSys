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
     */
    function showMessageModal(title, message, type = 'info', callback = null) {
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

        if (successMessage) {
            const msg = successMessage.getAttribute('data-message-success');
            showMessageModal('✅ ¡Éxito!', msg, 'success');
        } else if (errorMessage) {
            const msg = errorMessage.getAttribute('data-message-error');
            showMessageModal('❌ Error', msg, 'error');
        } else if (warningMessage) {
            const msg = warningMessage.getAttribute('data-message-warning');
            showMessageModal('⚠️ Advertencia', msg, 'warning');
        } else if (infoMessage) {
            const msg = infoMessage.getAttribute('data-message-info');
            showMessageModal('ℹ️ Información', msg, 'info');
        }
    });
</script>
