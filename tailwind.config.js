// tailwind.config.js
import flowbite from 'flowbite/plugin'

export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        'node_modules/flowbite/**/*.js'
    ],
    theme: {
        extend: {
            fontFamily: {
                sans:    ['Plus Jakarta Sans', 'sans-serif'],
                display: ['Syne', 'sans-serif'],
                mono:    ['JetBrains Mono', 'monospace'],
            },
            colors: {
                brand: {
                    50:  '#eef2ff',
                    100: '#e0e7ff',
                    400: '#818cf8',
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#4338ca',
                },
                surface: {
                    50:  '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                }
            },
            animation: {
                'fade-in':    'fadeIn 0.4s ease forwards',
                'slide-in':   'slideIn 0.3s ease forwards',
            },
            keyframes: {
                fadeIn:  { from: { opacity: 0, transform: 'translateY(8px)' }, to: { opacity: 1, transform: 'translateY(0)' } },
                slideIn: { from: { opacity: 0, transform: 'translateX(-12px)' }, to: { opacity: 1, transform: 'translateX(0)' } },
            }
        },
    },
    plugins: [flowbite],
}