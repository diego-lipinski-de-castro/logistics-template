const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');
const flattenColorPalette = require('tailwindcss/lib/util/flattenColorPalette').default;
const path = require('path');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        path.resolve(__dirname, './node_modules/litepie-datepicker/**/*.js')
    ],

    darkMode: false,

    theme: {
        extend: {
            colors: {
                ...colors,
                green: colors.emerald,
                yellow: colors.amber,
                purple: colors.violet,
                speedapp: {
                  orange: {
                    50: '#FEEFE0',
                    100: '#FCD8B3',
                    200: '#FABE80',
                    300: '#F8A34D',
                    400: '#F79026',
                    500: '#F57C00',
                    600: '#F47400',
                    700: '#F26900',
                    800: '#F05F00',
                    900: '#EE4C00',
                  },
                  red: {
                    50: '#F5E2E0',
                    100: '#E6B7B3',
                    200: '#D58880',
                    300: '#C4584D',
                    400: '#B73426',
                    500: '#AA1000',
                    600: '#A30E00',
                    700: '#990C00',
                    800: '#900900',
                    900: '#7F0500',
                  },
                  white: {
                    50: '#FCFBFC',
                    100: '#F7F6F8',
                    200: '#F2F0F3',
                    300: '#EDEAEE',
                    400: '#E9E6EA',
                    500: '#E5E1E6',
                    600: '#E2DDE3',
                    700: '#DED9DF',
                    800: '#DAD5DB',
                    900: '#D3CDD5',
                  },
                  gray: {
                    50: '#F3F3F3',
                    100: '#E2E1E2',
                    200: '#CECECF',
                    300: '#BABABB',
                    400: '#ACABAD',
                    500: '#9D9C9E',
                    600: '#959496',
                    700: '#8B8A8C',
                    800: '#818082',
                    900: '#6F6E70',
                  },
                  black: {
                    50: '#E2E3E4',
                    100: '#B7BABC',
                    200: '#888C90',
                    300: '#585D63',
                    400: '#343B41',
                    500: '#101820',
                    600: '#0E151C',
                    700: '#0C1118',
                    800: '#090E13',
                    900: '#05080B',
                  },
                },
                'litepie-primary': colors.lightBlue,
                'litepie-secondary': colors.coolGray,
            },
            fontFamily: {
                sans: ['Rubik'],
            },
        },
    },

    plugins: [
      require('@tailwindcss/forms'),
      require('@tailwindcss/typography'),
      ({ addUtilities, e, theme, variants }) => {
        let colors = flattenColorPalette(theme('borderColor'));
        delete colors['default'];

        // Replace or Add custom colors
        if(this.theme?.extend?.colors !== undefined){
            colors = Object.assign(colors, this.theme.extend.colors);
        }

        const colorMap = Object.keys(colors)
            .map(color => ({
                [`.border-t-${color}`]: {borderTopColor: colors[color]},
                [`.border-r-${color}`]: {borderRightColor: colors[color]},
                [`.border-b-${color}`]: {borderBottomColor: colors[color]},
                [`.border-l-${color}`]: {borderLeftColor: colors[color]},
            }));
        const utilities = Object.assign({}, ...colorMap);

        addUtilities(utilities, variants('borderColor'));
      },
    ],
};
