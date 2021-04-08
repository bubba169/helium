module.exports = {
  mode: 'jit',
  purge: [
    './resources/**/*.{js,css,twig}',
    './vendor/bubba169/helium/resources/**/*.{js,css,twig}'
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
