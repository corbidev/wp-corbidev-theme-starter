import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig(({ mode }) => ({
  plugins: [vue()],
  build: {
    outDir: 'assets/dist',
    emptyOutDir: true,
    manifest: true,
    sourcemap: true,
    minify: mode === 'production',

    rollupOptions: {
      input: {
        app: 'assets/src/main.js',
      }
    }
  }
}))