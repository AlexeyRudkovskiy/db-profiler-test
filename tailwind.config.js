/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      scale: {
        '80': '0.80',
        '85': '0.85',
        '95': '0.95',
        '103': '1.03'
      }
    }
  },
  plugins: [],
}
