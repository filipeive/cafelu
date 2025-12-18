/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./app/View/Components/**/*.php",
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#FFA500',
                    50: '#FFF8E6',
                    100: '#FFECC2',
                    500: '#FFA500',
                    600: '#E69500',
                    700: '#CC8400',
                },
                warning: '#FFA500',
                danger: '#EF4444',
                success: '#10B981',
                info: '#3B82F6',
            },
        },
    },
    plugins: [],
}