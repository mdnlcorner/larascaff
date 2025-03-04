import defaultTheme from 'tailwindcss/defaultTheme';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            transitionProperty: {
                width: 'width',
                height: 'height',
                margin: 'margin',
            },
            keyframes: {
                'fade-in-up': {
                    '0%': {
                        opacity: '0',
                        transform: 'translateY(10px)',
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateY(0)',
                    },
                },
                'face-in-down': {
                    '0%': {
                        opacity: '0',
                        transform: 'translateY(-10px)',
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateY(0)',
                    },
                },
                'fade-in': {
                    '0%': {
                        opacity: '0',
                    },
                    '100%': {
                        opacity: '1',
                    },
                },
                'fade-out': {
                    '0%': {
                        opacity: '1',
                    },
                    '100%': {
                        opacity: '0',
                    },
                },
            },
            animation: {
                'fade-in-up': 'fade-in-up 250ms ease-in-out',
                'fade-in-down': 'fade-in-down 250ms ease-in-out',
                'fade-in': 'fade-in 250ms ease-in-out',
                'fade-out': 'fade-out 250ms ease-in-out',
            },
            colors: {
                border: "var(--border)",
                input: "hsl(var(--input))",
                ring: "hsl(var(--ring))",
                background: {
                    DEFAULT: "var(--background)",
                    content: "var(--card)"
                },
                foreground: "hsl(var(--foreground))",
                primary: {
                    DEFAULT: "rgba(var(--primary-500), <alpha-value>)",
                    50: 'rgba(var(--primary-50), <alpha-value>)',
                    100: 'rgba(var(--primary-100), <alpha-value>)',
                    200: 'rgba(var(--primary-200), <alpha-value>)',
                    300: 'rgba(var(--primary-300), <alpha-value>)',
                    400: 'rgba(var(--primary-400), <alpha-value>)',
                    500: 'rgba(var(--primary-500), <alpha-value>)',
                    600: 'rgba(var(--primary-600), <alpha-value>)',
                    700: 'rgba(var(--primary-700), <alpha-value>)',
                    800: 'rgba(var(--primary-800), <alpha-value>)',
                    900: 'rgba(var(--primary-900), <alpha-value>)',
                    950: 'rgba(var(--primary-950), <alpha-value>)',
                },
                secondary: {
                    DEFAULT: "rgba(var(--secondary-500), <alpha-value>)",
                    50: 'rgba(var(--secondary-50), <alpha-value>)',
                    100: 'rgba(var(--secondary-100), <alpha-value>)',
                    200: 'rgba(var(--secondary-200), <alpha-value>)',
                    300: 'rgba(var(--secondary-300), <alpha-value>)',
                    400: 'rgba(var(--secondary-400), <alpha-value>)',
                    500: 'rgba(var(--secondary-500), <alpha-value>)',
                    600: 'rgba(var(--secondary-600), <alpha-value>)',
                    700: 'rgba(var(--secondary-700), <alpha-value>)',
                    800: 'rgba(var(--secondary-800), <alpha-value>)',
                    900: 'rgba(var(--secondary-900), <alpha-value>)',
                    950: 'rgba(var(--secondary-950), <alpha-value>)',
                },
                success: {
                    DEFAULT: "rgba(var(--success-500), <alpha-value>)",
                    50: 'rgba(var(--success-50), <alpha-value>)',
                    100: 'rgba(var(--success-100), <alpha-value>)',
                    200: 'rgba(var(--success-200), <alpha-value>)',
                    300: 'rgba(var(--success-300), <alpha-value>)',
                    400: 'rgba(var(--success-400), <alpha-value>)',
                    500: 'rgba(var(--success-500), <alpha-value>)',
                    600: 'rgba(var(--success-600), <alpha-value>)',
                    700: 'rgba(var(--success-700), <alpha-value>)',
                    800: 'rgba(var(--success-800), <alpha-value>)',
                    900: 'rgba(var(--success-900), <alpha-value>)',
                    950: 'rgba(var(--success-950), <alpha-value>)',
                },
                warning: {
                    DEFAULT: "rgba(var(--warning-500), <alpha-value>)",
                    50: 'rgba(var(--warning-50), <alpha-value>)',
                    100: 'rgba(var(--warning-100), <alpha-value>)',
                    200: 'rgba(var(--warning-200), <alpha-value>)',
                    300: 'rgba(var(--warning-300), <alpha-value>)',
                    400: 'rgba(var(--warning-400), <alpha-value>)',
                    500: 'rgba(var(--warning-500), <alpha-value>)',
                    600: 'rgba(var(--warning-600), <alpha-value>)',
                    700: 'rgba(var(--warning-700), <alpha-value>)',
                    800: 'rgba(var(--warning-800), <alpha-value>)',
                    900: 'rgba(var(--warning-900), <alpha-value>)',
                    950: 'rgba(var(--warning-950), <alpha-value>)',
                },
                danger: {
                    DEFAULT: "rgba(var(--danger-500), <alpha-value>)",
                    50: 'rgba(var(--danger-50), <alpha-value>)',
                    100: 'rgba(var(--danger-100), <alpha-value>)',
                    200: 'rgba(var(--danger-200), <alpha-value>)',
                    300: 'rgba(var(--danger-300), <alpha-value>)',
                    400: 'rgba(var(--danger-400), <alpha-value>)',
                    500: 'rgba(var(--danger-500), <alpha-value>)',
                    600: 'rgba(var(--danger-600), <alpha-value>)',
                    700: 'rgba(var(--danger-700), <alpha-value>)',
                    800: 'rgba(var(--danger-800), <alpha-value>)',
                    900: 'rgba(var(--danger-900), <alpha-value>)',
                    950: 'rgba(var(--danger-950), <alpha-value>)',
                },
                info: {
                    DEFAULT: "rgba(var(--info-500), <alpha-value>)",
                    50: 'rgba(var(--info-50), <alpha-value>)',
                    100: 'rgba(var(--info-100), <alpha-value>)',
                    200: 'rgba(var(--info-200), <alpha-value>)',
                    300: 'rgba(var(--info-300), <alpha-value>)',
                    400: 'rgba(var(--info-400), <alpha-value>)',
                    500: 'rgba(var(--info-500), <alpha-value>)',
                    600: 'rgba(var(--info-600), <alpha-value>)',
                    700: 'rgba(var(--info-700), <alpha-value>)',
                    800: 'rgba(var(--info-800), <alpha-value>)',
                    900: 'rgba(var(--info-900), <alpha-value>)',
                    950: 'rgba(var(--info-950), <alpha-value>)',
                },
                dark: {
                    DEFAULT: "rgba(var(--dark-500), <alpha-value>)",
                    50: 'rgba(var(--dark-50), <alpha-value>)',
                    100: 'rgba(var(--dark-100), <alpha-value>)',
                    200: 'rgba(var(--dark-200), <alpha-value>)',
                    300: 'rgba(var(--dark-300), <alpha-value>)',
                    400: 'rgba(var(--dark-400), <alpha-value>)',
                    500: 'rgba(var(--dark-500), <alpha-value>)',
                    600: 'rgba(var(--dark-600), <alpha-value>)',
                    700: 'rgba(var(--dark-700), <alpha-value>)',
                    800: 'rgba(var(--dark-800), <alpha-value>)',
                    900: 'rgba(var(--dark-900), <alpha-value>)',
                    950: 'rgba(var(--dark-950), <alpha-value>)',
                },
                muted: {
                    DEFAULT: "var(--muted)",
                    foreground: "hsl(var(--muted-foreground))",
                },
                popover: {
                    DEFAULT: "hsl(var(--popover))",
                    foreground: "hsl(var(--popover-foreground))",
                },
                card: {
                    DEFAULT: "var(--card)",
                    foreground: "hsl(var(--card-foreground))",
                },
            },
            borderRadius: {
                primary: '0.4rem',
                lg: `var(--radius)`,
                md: `calc(var(--radius) - 2px)`,
                sm: "calc(var(--radius) - 4px)",
            },
        },
        container: {
            center: true,
            padding: "1rem",
        }
    },

    plugins: [
        typography,
    ],
}