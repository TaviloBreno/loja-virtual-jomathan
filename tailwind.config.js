/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/views/**/*.php",
    "./app/Presentation/Components/**/*.php",
    "./public/assets/js/**/*.js",
    "./resources/js/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        // Paleta Neon Futurista
        primary: {
          50: '#f3f0ff',
          100: '#e9e2ff',
          200: '#d6c9ff',
          300: '#b8a5ff',
          400: '#9575ff',
          500: '#7C3AED', // Roxo elétrico - cor principal
          600: '#6d28d9',
          700: '#5b21b6',
          800: '#4c1d95',
          900: '#3c1a78',
          950: '#2a0f5c'
        },
        neon: {
          50: '#f0fff4',
          100: '#dcfce7',
          200: '#bbf7d0',
          300: '#86efac',
          400: '#4ade80',
          500: '#39FF14', // Verde neon - destaque ousado
          600: '#16a34a',
          700: '#15803d',
          800: '#166534',
          900: '#14532d',
          950: '#052e16'
        },
        dark: {
          50: '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          800: '#2E2E2E', // Cinza médio - secundário
          900: '#0f172a',
          950: '#000000'  // Preto absoluto - fundo sofisticado
        },
        // Aliases para facilitar o uso
        electric: '#7C3AED',
        'neon-green': '#39FF14',
        'absolute-black': '#000000',
        'medium-gray': '#2E2E2E'
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
        mono: ['JetBrains Mono', 'Consolas', 'monospace']
      },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
        '128': '32rem'
      },
      borderRadius: {
        'xl': '1rem',
        '2xl': '1.5rem',
        '3xl': '2rem'
      },
      boxShadow: {
        'neon': '0 0 20px rgba(57, 255, 20, 0.5)',
        'electric': '0 0 20px rgba(124, 58, 237, 0.5)',
        'dark-lg': '0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.1)'
      },
      animation: {
        'pulse-neon': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'glow': 'glow 2s ease-in-out infinite alternate'
      },
      keyframes: {
        glow: {
          '0%': { boxShadow: '0 0 5px rgba(57, 255, 20, 0.5)' },
          '100%': { boxShadow: '0 0 20px rgba(57, 255, 20, 0.8), 0 0 30px rgba(57, 255, 20, 0.6)' }
        }
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography')
  ],
  darkMode: 'class'
}