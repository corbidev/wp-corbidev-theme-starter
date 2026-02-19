import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig(({ mode }) => ({
  plugins: [vue()],
  build: {
    outDir: 'assets/dist',
    emptyOutDir: true,
    manifest: true,

    // 🔥 Toujours générer les maps
    sourcemap: true,

    // 🔥 Minifier uniquement en prod
    minify: mode === 'production',

    rollupOptions: {
      input: {
        app: path.resolve(__dirname, 'assets/src/main.js'),
      }
    }
  }
}))