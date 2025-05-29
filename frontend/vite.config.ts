import { defineConfig } from 'vite';
import { sveltekit } from '@sveltejs/kit/vite';
import { VitePWA } from 'vite-plugin-pwa';
import { resolve } from 'path';

export default defineConfig({
  plugins: [
    sveltekit(),
    VitePWA({
      // Используем автоматический режим вместо injectManifest для совместимости с SvelteKit
      base: '/',
      strategies: 'generateSW',
      registerType: 'autoUpdate',
      workbox: {
        globPatterns: ['**/*.{js,css,html,ico,png,svg,woff2}'],
        navigateFallback: '/'
      },
      includeAssets: ['favicon.png', 'pwa-192x192.png', 'pwa-512x512.png'],
      manifest: {
        name: 'Farmabot',
        short_name: 'Farmabot',
        description: 'Farmabot Application',
        theme_color: '#3b82f6',
        background_color: '#ffffff',
        display: 'standalone',
        icons: [
          {
            src: '/pwa-192x192.png',
            sizes: '192x192',
            type: 'image/png',
            purpose: 'any maskable'
          },
          {
            src: '/pwa-512x512.png',
            sizes: '512x512',
            type: 'image/png',
            purpose: 'any maskable'
          }
        ]
      },
      devOptions: {
        enabled: false,
        type: 'module'
      }
    })
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
    target: 'esnext'
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
