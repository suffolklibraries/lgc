module.exports = {
    prefix: 'tw-',
  mode: 'jit',
  purge: {
    content: [
      './resources/**/*.antlers.html',
      './resources/**/*.blade.php',
      './resources/**/*.js',
      './content/**/*.md'
    ]
  },
  important: true,
  theme: {
    extend: {
        colors: {
            'brand-grey': {
                DEFAULT: '#333333'
            },
            'brand-teal': {
                DEFAULT: '#3AA19A'
            },
            'brand-pink': {
                DEFAULT: '#C02E5A'
            }
        },
        boxShadow: {
            'brand': '0px 4px 8px 0px rgba(0,0,0,0.058)'
        },
        typography: {
            DEFAULT: {
                css: {
                    '--tw-prose-bullets': '#333333',
                    '--tw-prose-counters': '#333333',
                    li: {
                        p: {
                            margin: 0,
                        },
                    },
                }

            }
        }
    },
  },
  variants: {},
  plugins: [
    require('@tailwindcss/typography'),
    require('tailwind-scrollbar')({ nocompatible: true }),
  ],
}
