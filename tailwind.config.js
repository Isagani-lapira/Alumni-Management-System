/** @type {import('tailwindcss').Config} */
const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
  content: ["!./**/node_modules/**", "./**/*.{html,js,php}"],
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
        postButton: "#60A5FA",
        postHoverButton: "#3B82F6",
        licorice: "#1A1110",
        dirtyWhite: "#6C6C6C",
        lightDirtyWhite: "#6A6A6A",
      },
      fontWeight: {
        medium: "510",
      },
      outline: ["none"],
    },
  },
  plugins: [
    require("daisyui"),
    require("@tailwindcss/forms")({
      strategy: "class",
    }),
  ],
  // daisyUI config (optional - here are the default values)
  daisyui: {
    themes: [], // true: all themes | false: only light + dark | array: specific themes like this ["light", "dark", "cupcake"]
    darkTheme: "dark", // name of one of the included themes for dark mode
    base: false, // applies background color and foreground color for root element by default
    styled: false, // include daisyUI colors and design decisions for all components
    utils: true, // adds responsive and modifier utility classes
    rtl: false, // rotate style direction from left-to-right to right-to-left. You also need to add dir="rtl" to your html tag and install `tailwindcss-flip` plugin for Tailwind CSS.
    prefix: "daisy-", // prefix for daisyUI classnames (components, modifiers and responsive class names. Not colors)
    logs: true, // Shows info about daisyUI version and used config in the console when building your CSS
  },
};
