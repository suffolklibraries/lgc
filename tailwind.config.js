module.exports = {
    prefix: 'tw-',
  mode: 'jit',
  purge: {
    content: [
      './resources/**/*.antlers.html',
      './resources/**/*.blade.php',
      './content/**/*.md'
    ]
  },
  important: true,
  theme: {
    extend: {},
  },
  variants: {},
  plugins: [],
}
