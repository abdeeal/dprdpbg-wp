/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './*.php',
    './inc/**/*.php',
    './template-parts/**/*.php',
    './src/js/**/*.js'
  ],
  theme: {
    extend: {
      colors: {
        main: '#FAFAF7',
        surface: '#F0EEE7',
        primary: {
          DEFAULT: '#A32B2E',
          light: '#FBEAEA',
        },
        secondary: '#33502F',
        accent: '#B3872E',
        ink: '#1E211D',
        body: '#251818',
        'body-secondary': '#584140',
        line: '#DEDAD0',
      },
      fontFamily: {
        display: ['var(--font-fraunces)', 'serif'],
        sans: ['var(--font-jakarta)', 'sans-serif'],
        mono: ['var(--font-jetbrains)', 'monospace'],
        montserrat: ['var(--font-montserrat)', 'sans-serif'],
      },
      borderRadius: {
        card: '12px',
        button: '8px',
        badge: '4px',
      },
    },
  },
  plugins: [],
};
