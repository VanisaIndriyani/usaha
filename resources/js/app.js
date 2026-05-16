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

const normalizeDigits = (value) => String(value ?? '').replace(/[^\d]/g, '');

const formatWithDots = (digits) => {
    const normalized = normalizeDigits(digits);
    if (normalized === '') return '';
    return normalized.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
};

const applyMoneyFormat = (input) => {
    const formatted = formatWithDots(input.value);
    input.value = formatted;
};

document.addEventListener('input', (e) => {
    const input = e.target;
    if (!(input instanceof HTMLInputElement)) return;
    if (!input.matches('input[data-money], input.money-input')) return;

    applyMoneyFormat(input);
    const len = input.value.length;
    input.setSelectionRange(len, len);
});

document.addEventListener(
    'submit',
    (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;

        const inputs = form.querySelectorAll('input[data-money], input.money-input');
        for (const input of inputs) {
            if (!(input instanceof HTMLInputElement)) continue;
            input.value = normalizeDigits(input.value);
        }
    },
    true
);

document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('input[data-money], input.money-input');
    for (const input of inputs) {
        if (!(input instanceof HTMLInputElement)) continue;
        applyMoneyFormat(input);
    }
});

Alpine.start();
