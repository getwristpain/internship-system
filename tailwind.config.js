import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
		fontFamily: {
		        sans: [
		            "Inter",
		            ...defaultTheme.fontFamily.sans,
		        ],
		        heading: [
		            "Paytone One",
		            ...defaultTheme.fontFamily.sans,
		        ],
		    },
            colors: {
		yellow: {
		  100: '#FFF5CC',
		  200: '#FFE799',
		  300: '#FFDA66',
		  400: '#FFCC33',
		  500: '#FFBF00',
		  600: '#CC9900',
		  700: '#997300',
		  800: '#664C00',
		  900: '#332600',
		  950: '#1A1300',
		},
		green: {
		  100: '#DFFFE6',
		  200: '#BFFFD4',
		  300: '#9FFFC1',
		  400: '#7FFFAD',
		  500: '#5FFFA0',
		  600: '#4CCC80',
		  700: '#399960',
		  800: '#266640',
		  900: '#133320',
		  950: '#0A1910',
		},
		blue: {
		  100: '#CCF2FF',
		  200: '#99E5FF',
		  300: '#66D9FF',
		  400: '#33CCFF',
		  500: '#00BFFF',
		  600: '#0099CC',
		  700: '#007399',
		  800: '#004C66',
		  900: '#002633',
		  950: '#00131A',
		},
		brown: {
		  100: '#EED4CB',
		  200: '#DDB4A0',
		  300: '#CC9375',
		  400: '#BB724A',
		  500: '#AA5220',
		  600: '#883F19',
		  700: '#662B13',
		  800: '#44180C',
		  900: '#220C06',
		  950: '#110603',
		},
		red: {
		  100: '#FFE6E6',
		  200: '#FFBFBF',
		  300: '#FF9999',
		  400: '#FF7373',
		  500: '#FF4D4D',
		  600: '#CC3E3E',
		  700: '#992E2E',
		  800: '#661F1F',
		  900: '#330F0F',
		  950: '#190808',
		},
		gray: {
		  100: '#F9F9F9',
		  200: '#E0E0E0',
		  300: '#C9C9C9',
		  400: '#B2B2B2',
		  500: '#9B9B9B',
		  600: '#7F7F7F',
		  700: '#6E6E6E',
		  800: '#5C5C5C',
		  900: '#4A4A4A',
		  950: '#3C3C3C',
		}
	    },
        },
    },

    plugins: [
        forms,
    ],
};
