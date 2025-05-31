import { defineConfig } from 'vite';
import { sveltekit } from '@sveltejs/kit/vite';

import { resolve } from 'path';

export default defineConfig({
  plugins: [
    sveltekit(),
        // VitePWA plugin completely removed for debugging
  ],
  server: {
    port: 3002,
    proxy: {
      // '/api': {
      //   target: 'http://127.0.0.1:8000',
      //   changeOrigin: true,
      //   rewrite: (path) => path.replace(/^\/_api/, '')
      // }
    },
    fs: {
      // Allow serving files from one level up from the package root
      allow: ['..']
    }
  },
  build: {
    outDir: '../public/build',
    emptyOutDir: true,
    target: 'esnext',
    // rollupOptions настраивается автоматически через SvelteKit
  },
  optimizeDeps: {
    include: ['@sveltejs/kit']
  },
  resolve: {
    alias: {
      $lib: resolve(__dirname, 'src/lib'),
      $components: resolve(__dirname, 'src/lib/components'),
      $styles: resolve(__dirname, 'src/styles')
    }
  }
});
