import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                ios: {
                    lightBg: '#F5F5F7',
                    lightCard: '#FFFFFF',
                    lightText: '#1D1D1F',
                    lightMuted: '#86868B',
                    lightBorder: '#E5E5EA',
                    darkBg: '#000000',
                    darkCard: '#1C1C1E',
                    darkText: '#F5F5F7',
                    darkMuted: '#98989D',
                    darkBorder: '#2C2C2E',
                    blue: '#007AFF',
                    blueGlow: '#0A84FF',
                    gold: '#FF9F0A',
                    green: '#30D158',
                }
            },
            fontFamily: {
                sans: ['-apple-system', 'BlinkMacSystemFont', 'SF Pro Display', 'SF Pro Text', 'Inter', ...defaultTheme.fontFamily.sans],
                display: ['Outfit', '-apple-system', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                'ios': '16px',
            },
            boxShadow: {
                'ios-light': '0 4px 20px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.03)',
                'ios-dark': '0 4px 24px rgba(0, 0, 0, 0.6), 0 0 1px rgba(255, 255, 255, 0.15)',
                'ios-blue': '0 4px 16px rgba(0, 122, 255, 0.35)',
            }
        },
    },

    plugins: [forms],
};
