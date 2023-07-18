/** @type {import('tailwindcss').Config} */
const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
  content: ["./**/*.{html,js,php}"],
  theme: {
    fontFamily: {
      sans: ["Montserrat", ...defaultTheme.fontFamily.sans],
    },
    // allows us to use custom colors
    extend: {
      colors: {
        accent: "#991B1B",
        secondary: "#1746A2",
        grayish: "#9CA3AF",
        darkAccent: "#601010",
        greyish_black: "#333030",
        accentBlue: "#1746A2",
        postButton: '#60A5FA',
        postHoverButton: '#3B82F6',
        licorice: '#1A1110',
        dirtyWhite: 'E8E4C9',
      },
      fontWeight: {
        medium: '510',
      },
      outline: ['none'],
    },
  },
  plugins: [],
};
