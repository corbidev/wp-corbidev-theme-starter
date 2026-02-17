export default {
  darkMode: 'class',
  content: [
    "./assets/js/**/*.vue",
    "./assets/js/**/*.js",
    "./templates/**/*.php"
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          light: '#3b82f6',
          DEFAULT: '#1d4ed8',
          dark: '#1e3a8a'
        },
        background: {
          light: '#f8fafc',
          dark: '#0f172a'
        },
        surface: {
          light: '#ffffff',
          dark: '#1e293b'
        },
        text: {
          light: '#0f172a',
          dark: '#f1f5f9'
        }
      }
    }
  },
  plugins: []
}
