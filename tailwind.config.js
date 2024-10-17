/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './public/**/*.{html,js,php}',
    './js/**/*.{html,css,js,php}'
  ],
  daisyui: {
    themes: ["light"]
  },
  theme: {
    extend: {},
  },
  plugins: [require("daisyui")],
}

