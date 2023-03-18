/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./src/**/*.{vue,js,ts}'],
  theme: {
    extend: {},
  },
  plugins: [ require("daisyui") ],
  daisyui: {
    themes: [
      {
        mytheme: {
        
          "primary": "#10b981",
                  
          "secondary": "#1d4ed8",
                
          "accent": "#1FB2A5",
                  
          "neutral": "#191D24",
                  
          "base-100": "#2A303C",
                  
          "info": "#3ABFF8",
                  
          "success": "#36D399",
                  
          "warning": "#FBBD23",
                
          "error": "#F87272",
        },
      },
    ]
  }
}
