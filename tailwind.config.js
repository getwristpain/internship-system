import defaultTheme from "tailwindcss/defaultTheme";

export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./resources/views/**/*.blade.php",
    ],
    daisyui: {
        themes: [
            {
                light: {
                    ...require("daisyui/src/theming/themes")["bumblebee"],

                    "--rounded-box": "1.25rem",
                    "--rounded-btn": "0.75rem",
                    "--rounded-badge": "1.9rem",
                    "--animation-btn": "0.25s",
                    "--animation-input": "0.2s",
                    "--btn-focus-scale": "0.95",
                    "--border-btn": "1px",
                    "--tab-border": "1px",
                    "--tab-radius": "0.75rem",
                },
            },
        ],
    },
    theme: {
        extend: {
            animation: {
                blink: "blink 0.5s infinite",
            },
            borderStyle: {
                "dashed-3": "3px dashed",
                "dashed-5": "5px dashed",
                "dashed-10": "10px dashed",
            },
            fontFamily: {
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
                heading: ["Paytone One", ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                blink: {
                    "0%, 100%": { opacity: 1 },
                    "50%": { opacity: 0 },
                },
            },
        },
    },

    plugins: [require("daisyui")],
};
