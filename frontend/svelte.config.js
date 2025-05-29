import { vitePreprocess } from '@sveltejs/vite-plugin-svelte';
import adapter from '@sveltejs/adapter-static';

/** @type {import('@sveltejs/kit').Config} */
const config = {
  preprocess: [vitePreprocess()],
  kit: {
    adapter: adapter({
      // Output SPA to Laravel public folder
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
