// frontend/svelte.config.js
import adapter from '@sveltejs/adapter-static';
import { vitePreprocess } from '@sveltejs/vite-plugin-svelte';

/** @type {import('@sveltejs/kit').Config} */
const config = {
  preprocess: vitePreprocess(),
  kit: {
    adapter: adapter({
      pages: '../public',
      assets: '../public',
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