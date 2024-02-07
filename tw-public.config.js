/** @type {import('tailwindcss').Config} */
module.exports = {
	prefix: 'tw-',
	content: ['./public/**/*.{php,js}'],
	theme: {
		extend: {},
	},
	plugins: [require('@tailwindcss/forms')],
};
