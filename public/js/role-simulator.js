/**
 * Role Simulator for Admin Layout
 * This script simulates different user roles (admin/doctor) for testing purposes
 */

// Default role is 'admin', but can be changed for testing
let currentRole = localStorage.getItem('simulatedRole') || 'admin';

// Function to set the role
function setRole(role) {
    if (role === 'admin' || role === 'doctor') {
        currentRole = role;
        localStorage.setItem('simulatedRole', role);
        alert(`Rol cambiado a: ${role}`);
        return true;
    } else {
        alert('Rol no válido. Use "admin" o "doctor"');
        return false;
    }
}

// Function to get the current role
function getRole() {
    return currentRole;
}

// Function to toggle dropdown menu
function toggleDropdown(dropdown) {
    const menu = dropdown.querySelector('.dropdown-menu');
    if (menu) {
        menu.classList.toggle('show');
    }
}

// Show alert when entering admin layout
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're in the admin layout
    if (document.querySelector('.admin-container')) {
        // Show alert with current role
        alert(`Has ingresado con el rol: ${currentRole}`);
        
        // Add role indicator to the UI
        const userInfo = document.querySelector('.user-info');
        if (userInfo) {
            // Make dropdown toggle work properly
            userInfo.addEventListener('click', function(e) {
                e.stopPropagation();
                const dropdown = this.closest('.dropdown');
                if (dropdown) {
                    toggleDropdown(dropdown);
                }
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                const dropdowns = document.querySelectorAll('.dropdown');
                dropdowns.forEach(dropdown => {
                    if (!dropdown.contains(e.target)) {
                        const menu = dropdown.querySelector('.dropdown-menu');
                        if (menu && menu.classList.contains('show')) {
                            menu.classList.remove('show');
                        }
                    }
                });
            });
            const roleIndicator = document.createElement('div');
            roleIndicator.className = 'role-indicator';
            roleIndicator.innerHTML = `
                <span class="role-badge ${currentRole === 'admin' ? 'role-admin' : 'role-doctor'}">
                    ${currentRole.toUpperCase()}
                </span>
            `;
            userInfo.appendChild(roleIndicator);
        }
        
        // Add role switcher to dropdown menu
        const dropdownMenu = document.querySelector('.dropdown-menu');
        if (dropdownMenu) {
            const roleSwitcher = document.createElement('div');
            roleSwitcher.className = 'dropdown-item role-switcher';
            roleSwitcher.innerHTML = `
                <i class="fas fa-exchange-alt"></i> Cambiar Rol
                <select id="role-selector">
                    <option value="admin" ${currentRole === 'admin' ? 'selected' : ''}>Administrador</option>
                    <option value="doctor" ${currentRole === 'doctor' ? 'selected' : ''}>Doctor</option>
                </select>
            `;
            
            // Insert before the logout option
            const logoutLink = dropdownMenu.querySelector('a[href*="logout"]');
            if (logoutLink) {
                dropdownMenu.insertBefore(roleSwitcher, logoutLink.previousElementSibling);
            } else {
                dropdownMenu.appendChild(roleSwitcher);
            }
            
            // Prevent dropdown from closing when clicking on the role switcher
            roleSwitcher.addEventListener('click', function(e) {
                e.stopPropagation();
            });
            
            // Add event listener to the role selector
            document.getElementById('role-selector').addEventListener('change', function(e) {
                e.stopPropagation(); // Prevent event bubbling
                setRole(e.target.value);
                location.reload(); // Reload to apply changes
            });
        }
        
        // Apply role-specific UI changes
        applyRoleBasedChanges();
    }
});

// Apply changes based on the current role
function applyRoleBasedChanges() {
    if (currentRole === 'doctor') {
        // Hide admin-only menu items for doctors
        const adminOnlyItems = [
            'a[href*="admin/doctores"]',
            'a[href*="admin/reportes/generar"]',
            'a[href*="admin/historial-facturas"]',
            'a[href*="admin/generar-facturas"]'
        ];
        
        adminOnlyItems.forEach(selector => {
            const items = document.querySelectorAll(selector);
            items.forEach(item => {
                const menuItem = item.closest('.sidebar-menu-item');
                if (menuItem) {
                    menuItem.style.display = 'none';
                }
            });
        });
    }
}

// Expose functions to global scope for console testing
window.roleSimulator = {
    setRole,
    getRole
};

// Console instructions for testing
console.log(`
=== ROLE SIMULATOR ACTIVE ===
Current role: ${currentRole}

To change roles, use one of these methods:
1. Use the dropdown menu in the user profile
2. Run in console: roleSimulator.setRole('admin') or roleSimulator.setRole('doctor')
3. Check current role: roleSimulator.getRole()
`);
