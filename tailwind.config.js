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
            colors: {
                navy: {
                    50: '#eef2f8',
                    100: '#dce4f1',
                    200: '#b9c9e3',
                    300: '#8fa8d0',
                    400: '#5f80b5',
                    500: '#3d5f99',
                    600: '#2d4a80',
                    700: '#243d69',
                    800: '#1c2f52',
                    900: '#16243f',
                    950: '#0d1626',
                },
            },
        },
    },

    plugins: [forms],
};
