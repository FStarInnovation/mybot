// frontend/svelte.config.js
import adapter from '@sveltejs/adapter-static';
import { vitePreprocess } from '@sveltejs/vite-plugin-svelte';

/** @type {import('@sveltejs/kit').Config} */
const config = {
  preprocess: vitePreprocess(),
  kit: {
    adapter: adapter({
      // Generate root-level index.html into /public
      pages: '../public',
      // Place all JS/CSS assets under /public/_app for caching
      assets: '../public/_app',
      fallback: 'index.html'
    }),
    alias: {
      '$lib': './src/lib',
      '$components': './src/lib/components',
      '$styles': './src/styles'
    },
    files: {
      assets: 'static'
    },
    paths: {
      base: '',
      relative: false
    }
  }
};

export default config;