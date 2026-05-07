import './bootstrap';
import 'flowbite';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import ApexCharts from 'apexcharts';

window.Alpine = Alpine;
window.Swal = Swal;
window.ApexCharts = ApexCharts;

document.documentElement.classList.remove('dark');
localStorage.setItem('theme', 'light');

document.addEventListener('alpine:init', () => {
    Alpine.store('ui', {
        toast(message, type = 'success') {
            const bg = type === 'error' ? 'bg-red-600' : type === 'warning' ? 'bg-amber-600' : 'bg-emerald-600';
            const el = document.createElement('div');
            el.className = `fixed right-4 top-4 z-[100] ${bg} text-white px-4 py-3 rounded-xl shadow-xl shadow-black/20 text-sm font-semibold`;
            el.textContent = message;
            document.body.appendChild(el);
            window.setTimeout(() => el.classList.add('opacity-0', 'translate-y-1'), 2000);
            window.setTimeout(() => el.remove(), 2400);
        },
        confirm({ title = 'Yakin?', text = 'Tindakan ini tidak bisa dibatalkan.', confirmText = 'Ya', cancelText = 'Batal' } = {}) {
            return Swal.fire({
                title,
                text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'btn-primary',
                    cancelButton: 'btn-ghost',
                },
                buttonsStyling: false,
            });
        },
    });
});

Alpine.start();
