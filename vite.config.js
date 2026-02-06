import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path'

export default defineConfig({
  plugins: [vue()],

  build: {
    outDir: 'assets/dist',
    emptyOutDir: true,
    manifest: true,

    rollupOptions: {
      input: {
        front: resolve(__dirname, 'assets/vite/front.js'),
        admin: resolve(__dirname, 'assets/vite/admin.js'),
      },
      output: {
        manualChunks: {
          'vue-vendor': ['vue'],
        },
        entryFileNames: 'assets/[name]-[hash].js',
        chunkFileNames: 'assets/[name]-[hash].js',
        assetFileNames: 'assets/[name]-[hash].[ext]',
        format: 'es',
      },
    },

    minify: 'terser',
    terserOptions: {
      compress: {
        drop_console: true,
        drop_debugger: true,
        passes: 2,
        pure_funcs: ['console.log', 'console.info', 'console.debug'],
      },
      format: {
        comments: false,
      },
    },

    chunkSizeWarningLimit: 500,
    sourcemap: false,
    target: 'es2020',
    cssCodeSplit: true,
  },

  /**
   * IMPORTANT :
   * ❌ Aucun Tailwind / Autoprefixer ici
   * ✅ Tout passe par postcss.config.js
   */
  css: {
    postcss: {},
  },

  optimizeDeps: {
    include: ['vue'],
  },

  server: {
    port: 3000,
    strictPort: true,
    hmr: {
      overlay: true,
    },
    headers: {
      'Access-Control-Allow-Origin': '*',
    },
  },

  esbuild: {
    drop: ['debugger'],
    minifyIdentifiers: true,
    minifySyntax: true,
    minifyWhitespace: true,
  },
})
