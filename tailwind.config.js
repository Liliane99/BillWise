/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      backgroundColor: {
        'custom-nav-color': 'rgba(217, 217, 217, 0.14)',
        'custom-card-color': 'rgba(217, 217, 217, 0.54)',
        'custom-purple': 'rgba(201, 82, 229, 0.64)',
        'body-color': 'rgba(229, 231, 235, 1.0)',
        'black-opacity-56': 'rgba(0, 0, 0, 0.56)',
        'blue-color': 'rgb( 15 , 5 , 107)', 
        'blue-color-opacityy': 'rgba(15, 5, 107, 0.6)',
        'blue-color-opacity': '#e0f2fe',
      },
      textColor: {
        'blue-color': 'rgb( 15 , 5 , 107)', 
        'btext-color': 'rgba(229, 231, 235, 1.0)',
        'blue-color-opacity': '#e0f2fe'
      },
      width: {
        'category-card': '200px'
      },
      fontFamily: {
        nunito: ['Nunito', 'sans-serif'],
      },
      spacing: {
        '8': '3rem', 
      },
    },
  },
  plugins: [],
  darkMode: 'class',
}



