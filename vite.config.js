
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig(({ mode }) => ({
  plugins: [vue()],
  root: 'assets',
  base: mode === 'development'
    ? '/'
    : '/wp-content/themes/wp-corbidev-theme-starter/assets/dist/',
  build: {
    outDir: 'dist',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        front: path.resolve(__dirname, 'assets/vite/front.js'),
        admin: path.resolve(__dirname, 'assets/vite/admin.js'),
      }
    }
  },
  server: {
    port: 5173,
    strictPort: true
  }
}))
