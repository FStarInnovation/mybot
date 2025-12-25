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
      fallback: 'index.html',
      precompress: false,
      strict: true
    }),
    alias: {
      '$lib': './src/lib',
      '$components': './src/lib/components',
      '$styles': './src/styles'
    },
    appDir: '_app',
    paths: {
      base: '',
      relative: false
    },
    version: {
      name: Date.now().toString()
    }
  }
};

export default config;