/** @type {import('tailwindcss').Config} */
const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
  content: ["./**/*.{html,js}"],
  theme: {
    fontFamily: {
      sans: ["Montserrat", ...defaultTheme.fontFamily.sans],
    },
    // allows us to use custom colors
    extend: {
      colors: {
        accent: "#991B1B",
        secondary: "#1746A2",
        grayish: "#666666",
      },
    },
  },
  plugins: [],
};
