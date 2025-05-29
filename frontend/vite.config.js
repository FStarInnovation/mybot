import { sveltekit } from '@sveltejs/kit/vite';
import { resolve } from 'path';

/** @type {import('vite').UserConfig} */
export default {
  plugins: [sveltekit()],
  server: {
    port: 3002
  },
  build: {
    outDir: '../public',
    emptyOutDir: true
  },
  resolve: {
    alias: {
      '$lib': resolve('./src/lib'),
      '$components': resolve('./src/lib/components'),
      '$styles': resolve('./src/styles')
    }
  }
};
