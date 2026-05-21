import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import flowbite from 'flowbite/plugin'

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        'node_modules/flowbite/**/*.js' 
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans:    ['Plus Jakarta Sans', 'sans-serif'],
                display: ['Syne', 'sans-serif'],
                mono:    ['JetBrains Mono', 'monospace'],
            },
            colors: {
                primary: {
                    50:  '#eef2ff',
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#4338ca',
                },
                surface: {
                    900: '#0F172A',
                    800: '#1E293B',
                    700: '#334155',
                }
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
             animation: {
                'fade-in':    'fadeIn 0.4s ease forwards',
                'slide-in':   'slideIn 0.3s ease forwards',
                'pulse-slow': 'pulse 3s infinite',
            },
             keyframes: {
                fadeIn:  { from: { opacity: 0, transform: 'translateY(8px)' }, to: { opacity: 1, transform: 'translateY(0)' } },
                slideIn: { from: { opacity: 0, transform: 'translateX(-12px)' }, to: { opacity: 1, transform: 'translateX(0)' } },
            }
        },
    },

    plugins: [forms],
    plugins: [flowbite],
};
