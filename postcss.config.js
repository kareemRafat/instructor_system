module.exports = {
  plugins: {
    tailwindcss: {},
    "postcss-preset-env": {
      features: {
        "nesting-rules": true,
        "custom-properties": true,
        "custom-media-queries": true,
        "color-mod-function": true,
        "gap-properties": true,
        "logical-properties-and-values": true,
        "media-query-ranges": true,
        "custom-selectors": true,
        "is-pseudo-class": true,
        "focus-visible-pseudo-class": true,
        "focus-within-pseudo-class": true,
        "color-functional-notation": true,
      },
      autoprefixer: {
        flexbox: "no-2009",
      },
      stage: 3,
    },
    autoprefixer: {},
  },
};
