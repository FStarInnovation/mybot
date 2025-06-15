import { sveltekit } from '@sveltejs/kit/vite';
import { defineConfig } from 'vite';

export default defineConfig({
	plugins: [sveltekit()],
	server: {
		fs: {
			allow: ['..'],
		}
	},
	base: '/' // ВАЖНО: базовый путь должен быть '/', а не '/build/'
});
