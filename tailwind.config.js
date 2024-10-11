/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./*.html", "./*.js", "./*.php"],
  theme: {
    extend: {
      fontFamily: {
        DB_Med: ["DB_Med", "sans-serif"],
        DB_v4: ["DB_v4", "sans-serif"],
      },
    },
  },
  plugins: [require("tailwindcss-animated")],
};
