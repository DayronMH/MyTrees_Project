const ModalManager = {
    
    openEditModal: function(model, id) {
        console.error('NO ES ERROR ES PRUBEBA');
        try {
            if (!model || !id) {
                console.error('Error: Se requieren modelo e ID válidos');
                return;
            }

            // Remover modal existente si hay uno
            this.closeEditModal();

            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="modal-overlay" id="modalOverlay">
                    <iframe src="http://mytrees.com/views/edit.php?model=${model}&id=${id}" 
                            style="border: none; width: 100%; height: 100%; position: fixed; top: 0; left: 0;"
                            title="Modal de edición">
                    </iframe>
                </div>
            `;
            
            // Agregar listener para cerrar con ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.closeEditModal();
                }
            });

            // Agregar listener para cerrar al hacer clic fuera
            modal.querySelector('.modal-overlay').addEventListener('click', (e) => {
                if (e.target === e.currentTarget) {
                    this.closeEditModal();
                }
            });

            document.body.appendChild(modal);
            
            // Log para debugging
            console.log(`Modal abierto para ${model} con ID ${id}`);
        } catch (error) {
            console.error('Error al abrir el modal:', error);
        }
    },

    closeEditModal: function() {
        try {
            const overlay = document.getElementById('modalOverlay');
            if (overlay) {
                overlay.parentElement.remove();
            }
        } catch (error) {
            console.error('Error al cerrar el modal:', error);
        }
    }
};

// Auto-inicialización
document.addEventListener('DOMContentLoaded', () => {
    console.log('ModalManager inicializado');
});