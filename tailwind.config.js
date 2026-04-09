export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: "#2563EB",
          dark: "#1E40AF",
          light: "#DBEAFE",
        },
        accent: {
          DEFAULT: "#FACC15",
          dark: "#EAB308",
          light: "#FEF9C3",
        },
        background: "#F8FAFC",
        textdark: "#0F172A",
        textgray: "#475569",
        bordercolor: "#E2E8F0",
      },
    },
  },
  plugins: [],
}