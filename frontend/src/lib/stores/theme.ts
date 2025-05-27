import { writable } from 'svelte/store';
import { browser } from '$app/environment';

export type Theme = 'light' | 'dark';

const defaultTheme: Theme = 'light';
const initialTheme = browser ? (localStorage.getItem('theme') as Theme) || defaultTheme : defaultTheme;

export const theme = writable<Theme>(initialTheme);

theme.subscribe((value) => {
  if (browser) {
    localStorage.setItem('theme', value);
    document.documentElement.setAttribute('data-theme', value);
  }
});

export function toggleTheme() {
  theme.update((currentTheme) => (currentTheme === 'light' ? 'dark' : 'light'));
}
