import { defineConfig } from 'vite';
import legacy from '@vitejs/plugin-legacy';

export default defineConfig({
  plugins: [
    legacy({
      targets: ['defaults', 'not IE 11']
    })
  ],
  build: {
    outDir: 'public/dist',
    rollupOptions: {
      input: {
        main: 'assets/js/main.js',
        admin: 'assets/js/admin.js'
      },
      output: {
        entryFileNames: 'js/[name]-[hash].js',
        chunkFileNames: 'js/[name]-[hash].js',
        assetFileNames: 'css/[name]-[hash][extname]',
        manualChunks: undefined, // Bundle everything into entry files
      }
    },
    manifest: true,
    emptyOutDir: true
  },
  server: {
    port: 3000,
    host: '0.0.0.0',
    strictPort: true,
    watch: {
      usePolling: true,
    },
    cors: {
      origin: [
        'http://localhost:8080',
        'http://localhost:8000',
        'http://host.docker.internal:8080'
      ],
      credentials: true
    },
    hmr: {
      port: 3000,
      host: 'localhost'
    }
  }
});