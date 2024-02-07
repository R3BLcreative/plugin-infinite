/** @type {import('tailwindcss').Config} */
module.exports = {
	prefix: 'tw-',
	content: ['./admin/**/*.{php,js}'],
	theme: {
		fontFamily: {
			display: ['Quicksand', 'sans-serif'],
			body: ['Lexend Deca', 'sans-serif'],
		},
		extend: {
			colors: {
				primary: '#1b98b9',
				secondary: '#f17f51',
				text: {
					DEFAULT: '#7b7b7b',
					50: '#7b8ea5',
					100: '#7b7b7b',
					200: '#0b2230',
				},
				surface: {
					DEFAULT: '#b9e5f5',
					100: '#F7FAFC',
					500: '#0b2230',
					800: '#131615',
				},
			},
		},
	},
	plugins: [require('@tailwindcss/forms')],
};
