import colors from 'tailwindcss/colors';
/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    content: [],
    theme: {
        extend: {
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
                    DEFAULT: "#6366F1",
                    ...colors.indigo
                },
                secondary: {
                    DEFAULT: '#6b7280',
                    ...colors.gray
                },
                success: {
                    DEFAULT: '#10b981',
                    ...colors.emerald
                },
                warning: {
                    DEFAULT: '#f59e0b',
                    ...colors.amber
                },
                danger: {
                    DEFAULT: '#f43f5e',
                    ...colors.rose
                },
                info: {
                    DEFAULT: '#0ea5e9',
                    ...colors.sky
                },
                dark: {
                    DEFAULT: '#64748b',
                    ...colors.slate
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
            screens: {
                "2xl": "80rem",
            }
        }
    },
    plugins: [],
}

