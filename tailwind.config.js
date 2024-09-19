import defaultTheme from "tailwindcss/defaultTheme";
import primeUi from "tailwindcss-primeui";

export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        container: {
            center: true,
            padding: {
                DEFAULT: "1rem",
                sm: "2rem",
                lg: "4rem",
                xl: "5rem",
                "2xl": "6rem",
            },
        },
        extend: {
            fontFamily: {
                roboto: ["Roboto", ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [primeUi],
};
