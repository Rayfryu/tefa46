// import './bootstrap';
// resources/js/app.js
import 'flowbite';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Sidebar mobile toggle
window.toggleSidebar = function () {
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebar-overlay');
    const isOpen   = !sidebar.classList.contains('-translate-x-full');

    if (isOpen) {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    } else {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    }
}

window.closeSidebar = function () {
    document.getElementById('sidebar').classList.add('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.add('hidden');
}