import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            transitionDuration: {
                '300': '300ms',
                '500': '500ms',
            },
            transformOrigin: {
                'top': 'top',
            },
        },
    },
    plugins: [],
}

