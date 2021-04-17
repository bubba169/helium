module.exports = {
  mode: 'jit',
  purge: [
    './resources/**/*.{js,css,twig}',
    './vendor/bubba169/helium/resources/**/*.{js,css,twig}',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/tailwind.blade.php'
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
        transitionProperty: {
            'max-h': 'max-height',
        }
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
