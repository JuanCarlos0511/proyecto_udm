// Sistema de portal para menús desplegables
class DropdownPortal {
    constructor() {
        this.init();
    }
    
    init() {
        // Crear el contenedor del portal
        this.portalContainer = document.createElement('div');
        this.portalContainer.id = 'dropdown-portal-container';
        this.portalContainer.style.position = 'fixed';
        this.portalContainer.style.zIndex = '10000'; // Un valor muy alto
        this.portalContainer.style.top = '0';
        this.portalContainer.style.left = '0';
        this.portalContainer.style.width = '100%';
        this.portalContainer.style.height = '100%';
        this.portalContainer.style.pointerEvents = 'none'; // No bloquea clics en elementos debajo
        document.body.appendChild(this.portalContainer);
        
        // Crear el elemento del menú desplegable
        this.menuElement = document.createElement('div');
        this.menuElement.className = 'dropdown-portal-menu';
        this.menuElement.style.position = 'absolute';
        this.menuElement.style.backgroundColor = 'white';
        this.menuElement.style.borderRadius = '4px';
        this.menuElement.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
        this.menuElement.style.display = 'none';
        this.menuElement.style.pointerEvents = 'auto'; // Permite interactuar con el menú
        this.menuElement.style.minWidth = '150px';
        this.menuElement.style.maxWidth = '250px';
        this.portalContainer.appendChild(this.menuElement);
        
        // Configurar el manejador de eventos de clic global
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.dropdown-toggle') && 
                !e.target.closest('.dropdown-portal-menu')) {
                this.hideMenu();
            }
        });
        
        // Conectar los botones desplegables
        this.connectButtons();
    }
    
    connectButtons() {
        document.querySelectorAll('.dropdown-toggle').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                const dropdown = button.closest('.dropdown');
                const menu = dropdown.querySelector('.dropdown-menu');
                const id = dropdown.dataset.id || dropdown.closest('tr')?.dataset.id;
                
                // Si el menú ya está abierto, cerrarlo
                if (this.menuElement.dataset.currentId === id && 
                    this.menuElement.style.display === 'block') {
                    this.hideMenu();
                    return;
                }
                
                // Posicionar y mostrar el menú
                this.showMenu(button, menu, id);
            });
        });
    }
    
    showMenu(button, originalMenu, id) {
        // Obtener la posición del botón
        const rect = button.getBoundingClientRect();
        
        // Clonar el contenido del menú original
        this.menuElement.innerHTML = originalMenu.innerHTML;
        this.menuElement.dataset.currentId = id;
        
        // Posicionar el menú
        this.menuElement.style.top = (rect.bottom + window.scrollY) + 'px';
        this.menuElement.style.left = (rect.left + window.scrollX) + 'px';
        
        // Aplicar estilos a los elementos del menú
        this.menuElement.querySelectorAll('.dropdown-item').forEach(item => {
            item.style.display = 'block';
            item.style.padding = '10px 15px';
            item.style.textDecoration = 'none';
            item.style.color = '#333';
            item.style.borderBottom = '1px solid #f0f0f0';
        });
        
        // Asegurarse de que el menú no se salga de la pantalla
        setTimeout(() => {
            const menuRect = this.menuElement.getBoundingClientRect();
            if (menuRect.right > window.innerWidth) {
                this.menuElement.style.left = 'auto';
                this.menuElement.style.right = '10px';
            }
            if (menuRect.bottom > window.innerHeight) {
                this.menuElement.style.top = 'auto';
                this.menuElement.style.bottom = '10px';
            }
        }, 0);
        
        // Mostrar el menú
        this.menuElement.style.display = 'block';
        
        // Manejar los clics en los elementos del menú
        this.menuElement.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', (e) => {
                // Verificar si el elemento tiene un onclick
                const onclickAttr = item.getAttribute('onclick');
                if (onclickAttr) {
                    // No hacer nada, dejamos que el onclick original se ejecute
                } else {
                    // Para elementos como Accept/Cancel, simular el clic en el elemento original
                    const originalItem = originalMenu.querySelector(`[data-id="${item.dataset.id}"]`);
                    if (originalItem) {
                        originalItem.click();
                    }
                }
                
                // Cerrar el menú después de hacer clic
                this.hideMenu();
            });
        });
    }
    
    hideMenu() {
        this.menuElement.style.display = 'none';
        this.menuElement.innerHTML = '';
        this.menuElement.dataset.currentId = '';
    }
}

// Inicializar el portal de menús desplegables cuando la página esté cargada
document.addEventListener('DOMContentLoaded', () => {
    window.dropdownPortal = new DropdownPortal();
});
