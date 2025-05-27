module.exports = {
  plugins: {
    tailwindcss: {},
    autoprefixer: {
      overrideBrowserslist: [
        "Chrome 109",
        "> 0.5%",
        "last 2 versions",
        "Firefox ESR",
        "not dead",
        "IE 11",
      ],
    },
  },
};
