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
                border: 'hsl(var(--border, 240 5.9% 90%))',
                input: 'hsl(var(--input, 240 5.9% 90%))',
                ring: 'hsl(var(--ring, 240 5.9% 10%))',
                background: 'hsl(var(--background, 0 0% 100%))',
                foreground: 'hsl(var(--foreground, 240 10% 3.9%))',
                primary: {
                    DEFAULT: '#FFA500',
                    foreground: 'hsl(var(--primary-foreground, 0 0% 98%))',
                    50: '#FFF8E6',
                    100: '#FFECC2',
                    500: '#FFA500',
                    600: '#E69500',
                    700: '#CC8400',
                },
                secondary: {
                    DEFAULT: 'hsl(var(--secondary, 240 4.8% 95.9%))',
                    foreground: 'hsl(var(--secondary-foreground, 240 5.9% 10%))',
                },
                destructive: {
                    DEFAULT: 'hsl(var(--destructive, 0 84.2% 60.2%))',
                    foreground: 'hsl(var(--destructive-foreground, 0 0% 98%))',
                },
                muted: {
                    DEFAULT: 'hsl(var(--muted, 240 4.8% 95.9%))',
                    foreground: 'hsl(var(--muted-foreground, 240 3.8% 46.1%))',
                },
                accent: {
                    DEFAULT: 'hsl(var(--accent, 240 4.8% 95.9%))',
                    foreground: 'hsl(var(--accent-foreground, 240 5.9% 10%))',
                },
                popover: {
                    DEFAULT: 'hsl(var(--popover, 0 0% 100%))',
                    foreground: 'hsl(var(--popover-foreground, 240 10% 3.9%))',
                },
                card: {
                    DEFAULT: 'hsl(var(--card, 0 0% 100%))',
                    foreground: 'hsl(var(--card-foreground, 240 10% 3.9%))',
                },
                warning: '#FFA500',
                danger: '#EF4444',
                success: '#10B981',
                info: '#3B82F6',
            },
            borderRadius: {
                lg: 'var(--radius, 0.625rem)',
                md: 'calc(var(--radius, 0.625rem) - 2px)',
                sm: 'calc(var(--radius, 0.625rem) - 4px)',
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [],
}