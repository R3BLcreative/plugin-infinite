/** @type {import('tailwindcss').Config} */
module.exports = {
	content: ['./admin/**/*.{php,js}', './extensions/*.php', './**/*.svg', './config/*.json'],
	theme: {
		screens: {
			mobile: '0px',
			tablet: '767px',
			laptop: '991px',
			desktop: '1281px',
		},
		fontFamily: {
			display: ['Raleway', 'sans-serif'],
			body: ['Roboto', 'sans-serif'],
		},
		extend: {
			colors: {
				primary: {
					DEFAULT: '#DD3E42',
					50: '#F8DADB',
					100: '#F5C9CA',
					200: '#EFA6A8',
					300: '#E98386',
					400: '#E36164',
					600: '#C12226',
					700: '#911A1D',
					800: '#621113',
					900: '#32090A',
					950: '#1A0505',
				},
				secondary: {
					DEFAULT: '#3EA3DE',
					50: '#DBEEF9',
					100: '#C9E5F6',
					200: '#A7D5F0',
					300: '#84C4EA',
					400: '#61B4E4',
					500: '#3EA3DE',
					600: '#2187C3',
					700: '#196693',
					800: '#114563',
					900: '#092333',
					950: '#05131B',
				},
				body: {
					DEFAULT: '#CCD2DA',
					50: '#FFFFFF',
					100: '#FFFFFF',
					200: '#FFFFFF',
					300: '#FBFCFC',
					400: '#E4E7EB',
					600: '#ABB5C2',
					700: '#8B99AB',
					800: '#6B7C93',
					900: '#536173',
					950: '#475362',
				},
				surface: {
					DEFAULT: '#28364E',
					50: '#D0D8E7',
					100: '#B8C5DB',
					200: '#899FC3',
					300: '#5978AB',
					400: '#40577D',
					500: '#28364E',
					600: '#1f2a3d',
					700: '#161f2c',
					800: '#111822',
					900: '#05070A',
					950: '#010102',
				},
				success: {
					DEFAULT: '#66BB6A',
					50: '#E5F3E6',
					100: '#D7EDD8',
					200: '#BBE1BD',
					300: '#9ED4A1',
					400: '#82C886',
					600: '#48A14C',
					700: '#367A3A',
					800: '#255427',
					900: '#142D15',
				},
				warning: {
					DEFAULT: '#FFA726',
					50: '#FFF1DE',
					100: '#FFE9C9',
					200: '#FFD9A0',
					300: '#FFC878',
					400: '#FFB84F',
					600: '#ED8D00',
					700: '#B56B00',
					800: '#7D4A00',
					900: '#452900',
				},
				error: {
					DEFAULT: '#F44336',
					50: '#FEE6E4',
					100: '#FCD4D1',
					200: '#FAB0AA',
					300: '#F88B83',
					400: '#F6675D',
					600: '#E51B0D',
					700: '#B0150A',
					800: '#7B0F07',
					900: '#460804',
				},
			},
		},
	},
	plugins: [require('@tailwindcss/forms')],
};
