/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./private/**/*.twig"],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter var'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}