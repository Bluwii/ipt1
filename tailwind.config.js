import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            screens: {
                // Small phone (up to 479px)
                'sphone': { 'max': '479px' },
                // Regular phone (up to 699px)
                'phone': { 'max': '699px' },
                // Tablet range (700px - 1024px)
                'tablet': { 'min': '768px', 'max': '1024px' },
                // Desktop breakpoints
                'desktop1100': '1100px',
                'desktop1400': '1400px',
            },
        },
    },

    plugins: [forms],
};
