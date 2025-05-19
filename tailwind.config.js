import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

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
                primary: {
                    50: '#EBF5FF',
                    100: '#D6EBFF',
                    200: '#A6D5FF',
                    300: '#75BFFF',
                    400: '#3AA9FF',
                    500: '#0A84FF',
                    600: '#0074E0',
                    700: '#005BB4',
                    800: '#004282',
                    900: '#002851',
                },
                secondary: {
                    50: '#F0F4F8',
                    100: '#D9E2EC',
                    200: '#BCCCDC',
                    300: '#9FB3C8',
                    400: '#829AB1',
                    500: '#627D98',
                    600: '#486581',
                    700: '#334E68',
                    800: '#243B53',
                    900: '#102A43',
                },
            },
            animation: {
                'bounce-slow': 'bounce 3s linear infinite',
                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'fade-in': 'fadeIn 0.5s ease-out',
                'slide-in': 'slideIn 0.5s ease-out',
                'slide-up': 'slideUp 0.5s ease-out',
                'zoom-in': 'zoomIn 0.3s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideIn: {
                    '0%': { transform: 'translateX(-20px)', opacity: '0' },
                    '100%': { transform: 'translateX(0)', opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                zoomIn: {
                    '0%': { transform: 'scale(0.95)', opacity: '0' },
                    '100%': { transform: 'scale(1)', opacity: '1' },
                },
            },
            boxShadow: {
                'blue-glow': '0 0 15px rgba(10, 132, 255, 0.5)',
                'hover-blue': '0 10px 15px -3px rgba(10, 132, 255, 0.3), 0 4px 6px -2px rgba(10, 132, 255, 0.15)',
            },
            transitionProperty: {
                'height': 'height',
                'spacing': 'margin, padding',
            }
        },
    },

    plugins: [forms, typography],
};
