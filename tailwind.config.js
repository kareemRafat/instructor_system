/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./js/*.js", // All .js files directly inside js/
    "./design/**/*.php", // All PHP files in design folder
    "./functions/**/*.php", // All PHP files in functions folder
    "./helpers/**/*.php", // All PHP files in helpers folder
    "./*.php" // All PHP files in root folder
  ],
  darkMode: "false",
  theme: {
    extend: {},
  },
  plugins: [],
};
