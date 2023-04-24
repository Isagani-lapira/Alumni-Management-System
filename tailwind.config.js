/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{html,js}"],
  theme: {
    // allows us to use custom colors
    colors: {
      accent: "#991B1B",
      secondary: "#1746A2",
      white: "#F6F6F6",
      textColor: "#474645",
      darkAccent: "#671212",
    },
    fontFamily: {
      Montserrat: ["Montserrat"],
    },
    extend: {},
  },
  plugins: [],
};
