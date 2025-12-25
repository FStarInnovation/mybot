/// <reference types="vite/client" />
import { sveltekit } from '@sveltejs/kit/vite';
import { defineConfig } from 'vite';

/** @type {import('vite').UserConfig} */
export default defineConfig({
  plugins: [sveltekit()],
  build: {
    // Add hash to filenames for cache busting
    rollupOptions: {
      output: {
        entryFileNames: `entry/[name].[hash].js`,
        chunkFileNames: `chunks/[name].[hash].js`,
        assetFileNames: `assets/[name].[hash].[ext]`
      }
    }
  }
});
