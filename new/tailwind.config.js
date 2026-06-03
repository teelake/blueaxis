/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './app/Views/**/*.php',
    './public/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          navy: '#102A56',
          'navy-dark': '#0B1D3F',
          gold: '#C59E5F',
          'gold-light': '#D4B07A',
          'gold-muted': '#E8D4B8',
        },
      },
      fontFamily: {
        sans: ['DM Sans', 'system-ui', 'sans-serif'],
        display: ['DM Sans', 'system-ui', 'sans-serif'],
      },
      boxShadow: {
        card: '0 4px 24px rgba(16, 42, 86, 0.08)',
        elevated: '0 12px 40px rgba(16, 42, 86, 0.12)',
      },
    },
  },
  plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
