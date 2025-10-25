const defaultTheme = require("tailwindcss/defaultTheme");
const forms = require("@tailwindcss/forms");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Brand Orange Palette
                primary: {
                    50: "#fef7f0",
                    100: "#feede0",
                    200: "#fdd7c1",
                    300: "#fcba97",
                    400: "#fa936b",
                    500: "#f36c21", // Main brand color
                    600: "#e45a1a",
                    700: "#c04815",
                    800: "#9c3a16",
                    900: "#7e3215",
                    950: "#441708",
                },
                // Legacy brand-orange for backward compatibility
                "brand-orange": "#f36c21",

                // Extended color palette based on brand orange
                orange: {
                    50: "#fef7f0",
                    100: "#feede0",
                    200: "#fdd7c1",
                    300: "#fcba97",
                    400: "#fa936b",
                    500: "#f36c21",
                    600: "#e45a1a",
                    700: "#c04815",
                    800: "#9c3a16",
                    900: "#7e3215",
                    950: "#441708",
                },
            },
        },
    },

    plugins: [forms],
};
