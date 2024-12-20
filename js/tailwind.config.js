tailwind.config = {
  theme: {
    extend: {
      colors: {
        primary: "#044970",
        secondary: "#CC4644",
        white: "#FFFF",
        yellow: "#eab308",
      },
      fontFamily: {
        poppins: ["Poppins", "sans-serif"],
      },
      keyframes: {
        fadeIn: {
          "0%": { opacity: 0, transform: "translateY(-10px)" },
          "100%": { opacity: 1, transform: "translateY(0)" },
        },
        fadeOut: {
          "0%": { opacity: 1, transform: "translateY(0)" },
          "100%": { opacity: 0, transform: "translateY(-10px)" },
        },
      },
      animation: {
        fadeIn: "fadeIn 0.3s ease-out",
        fadeOut: "fadeOut 0.3s ease-in",
      },
    },
  },
  plugins: [
    function ({ addVariant, e }) {
      addVariant("hover-primary", ({ modifySelectors, separator }) => {
        modifySelectors(({ className }) => {
          return `.${e(`hover-primary${separator}${className}`)}:hover`;
        });
      });
      addVariant("hover-secondary", ({ modifySelectors, separator }) => {
        modifySelectors(({ className }) => {
          return `.${e(`hover-secondary${separator}${className}`)}:hover`;
        });
      });
      addVariant("hover-yellow", ({ modifySelectors, separator }) => {
        modifySelectors(({ className }) => {
          return `.${e(`hover-yellow${separator}${className}`)}:hover`;
        });
      });
      addVariant("hover-white", ({ modifySelectors, separator }) => {
        modifySelectors(({ className }) => {
          return `.${e(`hover-white${separator}${className}`)}:hover`;
        });
      });
    },
  ],
};
