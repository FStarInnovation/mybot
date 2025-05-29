import { c as create_ssr_component, o as onDestroy, v as validate_component } from "../../chunks/ssr.js";
import { w as writable } from "../../chunks/index2.js";
import { B as BROWSER } from "../../chunks/false.js";
import { QueryClient } from "@tanstack/query-core";
import { s as setQueryClientContext } from "../../chunks/context.js";
const browser = BROWSER;
new Event("pwa:install");
new Event("pwa:update");
if (typeof window !== "undefined" && "serviceWorker" in navigator) {
  navigator.serviceWorker.addEventListener("message", (event) => {
    if (event.data?.type === "SKIP_WAITING") {
      navigator.serviceWorker.getRegistration().then((registration) => {
        if (registration?.waiting) {
          registration.waiting.postMessage({ type: "SKIP_WAITING" });
        }
      });
    }
  });
}
const css$2 = {
  code: ".pwa-prompt.svelte-ke7wan.svelte-ke7wan{position:fixed;bottom:1rem;right:1rem;max-width:320px;background:white;border-radius:8px;box-shadow:0 4px 12px rgba(0, 0, 0, 0.15);padding:1rem;z-index:1000}.pwa-content.svelte-ke7wan.svelte-ke7wan{display:flex;flex-direction:column;gap:0.75rem}.pwa-content.svelte-ke7wan h3.svelte-ke7wan{margin:0;font-size:1.1rem;font-weight:600}.pwa-content.svelte-ke7wan p.svelte-ke7wan{margin:0;font-size:0.9rem;color:#4b5563}.pwa-buttons.svelte-ke7wan.svelte-ke7wan{display:flex;gap:0.5rem;margin-top:0.5rem}.btn.svelte-ke7wan.svelte-ke7wan{padding:0.5rem 1rem;border-radius:4px;font-size:0.9rem;font-weight:500;cursor:pointer;border:none}.btn-primary.svelte-ke7wan.svelte-ke7wan{background-color:#3b82f6;color:white}.btn-primary.svelte-ke7wan.svelte-ke7wan:hover{background-color:#2563eb}.btn-secondary.svelte-ke7wan.svelte-ke7wan{background-color:#e5e7eb;color:#4b5563}.btn-secondary.svelte-ke7wan.svelte-ke7wan:hover{background-color:#d1d5db}",
  map: '{"version":3,"file":"PWA.svelte","sources":["PWA.svelte"],"sourcesContent":["<script lang=\\"ts\\">\\"use strict\\";\\nimport { onMount } from \\"svelte\\";\\nimport { pwaInstallEvent, pwaUpdateEvent } from \\"$lib/registerSW\\";\\nlet deferredPrompt = null;\\nlet showInstallPrompt = false;\\nlet showUpdatePrompt = false;\\nonMount(() => {\\n  window.addEventListener(\\"beforeinstallprompt\\", handleBeforeInstallPrompt);\\n  document.addEventListener(\\"pwa:install\\", handlePWAInstall);\\n  document.addEventListener(\\"pwa:update\\", handlePWAUpdate);\\n  return () => {\\n    window.removeEventListener(\\"beforeinstallprompt\\", handleBeforeInstallPrompt);\\n    document.removeEventListener(\\"pwa:install\\", handlePWAInstall);\\n    document.removeEventListener(\\"pwa:update\\", handlePWAUpdate);\\n  };\\n});\\nfunction handleBeforeInstallPrompt(e) {\\n  e.preventDefault();\\n  deferredPrompt = e;\\n  showInstallPrompt = true;\\n}\\nfunction handlePWAInstall() {\\n  console.log(\\"App ready to be installed\\");\\n  showInstallPrompt = true;\\n}\\nfunction handlePWAUpdate() {\\n  console.log(\\"New version available!\\");\\n  showUpdatePrompt = true;\\n}\\nasync function installApp() {\\n  if (!deferredPrompt) return;\\n  deferredPrompt.prompt();\\n  const { outcome } = await deferredPrompt.userChoice;\\n  console.log(`User response to the install prompt: ${outcome}`);\\n  deferredPrompt = null;\\n  showInstallPrompt = false;\\n}\\nfunction updateApp() {\\n  if (\\"serviceWorker\\" in navigator) {\\n    navigator.serviceWorker.getRegistration().then((registration) => {\\n      if (registration?.waiting) {\\n        registration.waiting.postMessage({ type: \\"SKIP_WAITING\\" });\\n      }\\n    });\\n  }\\n  showUpdatePrompt = false;\\n  window.location.reload();\\n}\\n<\/script>\\n\\n{#if showInstallPrompt}\\n  <div class=\\"pwa-prompt\\">\\n    <div class=\\"pwa-content\\">\\n      <h3>Install MyBot</h3>\\n      <p>Add MyBot to your home screen for a better experience</p>\\n      <div class=\\"pwa-buttons\\">\\n        <button on:click={installApp} class=\\"btn btn-primary\\">Install</button>\\n        <button on:click={() => showInstallPrompt = false} class=\\"btn btn-secondary\\">Not Now</button>\\n      </div>\\n    </div>\\n  </div>\\n{/if}\\n\\n{#if showUpdatePrompt}\\n  <div class=\\"pwa-prompt\\">\\n    <div class=\\"pwa-content\\">\\n      <h3>Update Available</h3>\\n      <p>A new version of MyBot is available. Update now?</p>\\n      <div class=\\"pwa-buttons\\">\\n        <button on:click={updateApp} class=\\"btn btn-primary\\">Update</button>\\n        <button on:click={() => showUpdatePrompt = false} class=\\"btn btn-secondary\\">Later</button>\\n      </div>\\n    </div>\\n  </div>\\n{/if}\\n\\n<style>\\n  .pwa-prompt {\\n    position: fixed;\\n    bottom: 1rem;\\n    right: 1rem;\\n    max-width: 320px;\\n    background: white;\\n    border-radius: 8px;\\n    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);\\n    padding: 1rem;\\n    z-index: 1000;\\n  }\\n\\n  .pwa-content {\\n    display: flex;\\n    flex-direction: column;\\n    gap: 0.75rem;\\n  }\\n\\n  .pwa-content h3 {\\n    margin: 0;\\n    font-size: 1.1rem;\\n    font-weight: 600;\\n  }\\n\\n  .pwa-content p {\\n    margin: 0;\\n    font-size: 0.9rem;\\n    color: #4b5563;\\n  }\\n\\n  .pwa-buttons {\\n    display: flex;\\n    gap: 0.5rem;\\n    margin-top: 0.5rem;\\n  }\\n\\n  .btn {\\n    padding: 0.5rem 1rem;\\n    border-radius: 4px;\\n    font-size: 0.9rem;\\n    font-weight: 500;\\n    cursor: pointer;\\n    border: none;\\n  }\\n\\n  .btn-primary {\\n    background-color: #3b82f6;\\n    color: white;\\n  }\\n\\n  .btn-primary:hover {\\n    background-color: #2563eb;\\n  }\\n\\n  .btn-secondary {\\n    background-color: #e5e7eb;\\n    color: #4b5563;\\n  }\\n\\n  .btn-secondary:hover {\\n    background-color: #d1d5db;\\n  }\\n</style>\\n"],"names":[],"mappings":"AA6EE,uCAAY,CACV,QAAQ,CAAE,KAAK,CACf,MAAM,CAAE,IAAI,CACZ,KAAK,CAAE,IAAI,CACX,SAAS,CAAE,KAAK,CAChB,UAAU,CAAE,KAAK,CACjB,aAAa,CAAE,GAAG,CAClB,UAAU,CAAE,CAAC,CAAC,GAAG,CAAC,IAAI,CAAC,KAAK,CAAC,CAAC,CAAC,CAAC,CAAC,CAAC,CAAC,CAAC,CAAC,IAAI,CAAC,CAC1C,OAAO,CAAE,IAAI,CACb,OAAO,CAAE,IACX,CAEA,wCAAa,CACX,OAAO,CAAE,IAAI,CACb,cAAc,CAAE,MAAM,CACtB,GAAG,CAAE,OACP,CAEA,0BAAY,CAAC,gBAAG,CACd,MAAM,CAAE,CAAC,CACT,SAAS,CAAE,MAAM,CACjB,WAAW,CAAE,GACf,CAEA,0BAAY,CAAC,eAAE,CACb,MAAM,CAAE,CAAC,CACT,SAAS,CAAE,MAAM,CACjB,KAAK,CAAE,OACT,CAEA,wCAAa,CACX,OAAO,CAAE,IAAI,CACb,GAAG,CAAE,MAAM,CACX,UAAU,CAAE,MACd,CAEA,gCAAK,CACH,OAAO,CAAE,MAAM,CAAC,IAAI,CACpB,aAAa,CAAE,GAAG,CAClB,SAAS,CAAE,MAAM,CACjB,WAAW,CAAE,GAAG,CAChB,MAAM,CAAE,OAAO,CACf,MAAM,CAAE,IACV,CAEA,wCAAa,CACX,gBAAgB,CAAE,OAAO,CACzB,KAAK,CAAE,KACT,CAEA,wCAAY,MAAO,CACjB,gBAAgB,CAAE,OACpB,CAEA,0CAAe,CACb,gBAAgB,CAAE,OAAO,CACzB,KAAK,CAAE,OACT,CAEA,0CAAc,MAAO,CACnB,gBAAgB,CAAE,OACpB"}'
};
const PWA = create_ssr_component(($$result, $$props, $$bindings, slots) => {
  $$result.css.add(css$2);
  return `${``} ${``}`;
});
const defaultTheme = "light";
const initialTheme = defaultTheme;
const theme = writable(initialTheme);
theme.subscribe((value) => {
});
const css$1 = {
  code: ".theme-toggle.svelte-acy0wk.svelte-acy0wk{background:none;border:none;cursor:pointer;padding:0.5rem;border-radius:50%;display:flex;align-items:center;justify-content:center;transition:background-color 0.2s ease;position:relative;width:2.5rem;height:2.5rem}.theme-toggle.svelte-acy0wk.svelte-acy0wk:hover{background-color:var(--theme-toggle-hover-bg, #f0f0f0)}.icon.svelte-acy0wk.svelte-acy0wk{font-size:1.25rem;line-height:1;transition:transform 0.3s ease-out, opacity 0.3s ease-out;position:absolute}.theme-toggle.svelte-acy0wk .light-icon.svelte-acy0wk{opacity:var(--theme-light-icon-opacity, 1);transform:var(--theme-light-icon-transform, scale(1) rotate(0deg))}.theme-toggle.svelte-acy0wk .dark-icon.svelte-acy0wk{opacity:var(--theme-dark-icon-opacity, 1);transform:var(--theme-dark-icon-transform, scale(1) rotate(0deg))}[data-theme='dark'] .light-icon.svelte-acy0wk.svelte-acy0wk{opacity:0;transform:scale(0.5) rotate(-90deg)}[data-theme='light'] .dark-icon.svelte-acy0wk.svelte-acy0wk{opacity:0;transform:scale(0.5) rotate(90deg)}",
  map: `{"version":3,"file":"ThemeToggle.svelte","sources":["ThemeToggle.svelte"],"sourcesContent":["<script lang=\\"ts\\">\\"use strict\\";\\nimport { theme, toggleTheme } from \\"$lib/stores/theme\\";\\nimport { onMount } from \\"svelte\\";\\nlet currentTheme;\\nlet mounted = false;\\ntheme.subscribe((value) => {\\n  currentTheme = value;\\n});\\nonMount(() => {\\n  mounted = true;\\n});\\nfunction handleToggle() {\\n  toggleTheme();\\n}\\n<\/script>\\n\\n{#if mounted}\\n  <button \\n    class=\\"theme-toggle\\"\\n    on:click={handleToggle} \\n    aria-label=\\"Toggle theme\\"\\n    title=\\"Toggle theme\\"\\n  >\\n    {#if currentTheme === 'light'}\\n      <span class=\\"icon light-icon\\" role=\\"img\\" aria-label=\\"Light mode icon\\">☀️</span>\\n    {:else}\\n      <span class=\\"icon dark-icon\\" role=\\"img\\" aria-label=\\"Dark mode icon\\">🌙</span>\\n    {/if}\\n  </button>\\n{/if}\\n\\n<style>\\n  .theme-toggle {\\n    background: none;\\n    border: none;\\n    cursor: pointer;\\n    padding: 0.5rem;\\n    border-radius: 50%;\\n    display: flex;\\n    align-items: center;\\n    justify-content: center;\\n    transition: background-color 0.2s ease;\\n    position: relative;\\n    width: 2.5rem; /* 40px */\\n    height: 2.5rem; /* 40px */\\n  }\\n\\n  .theme-toggle:hover {\\n    background-color: var(--theme-toggle-hover-bg, #f0f0f0);\\n  }\\n\\n  .icon {\\n    font-size: 1.25rem; /* 20px */\\n    line-height: 1;\\n    transition: transform 0.3s ease-out, opacity 0.3s ease-out;\\n    position: absolute;\\n  }\\n\\n  .theme-toggle .light-icon {\\n    opacity: var(--theme-light-icon-opacity, 1);\\n    transform: var(--theme-light-icon-transform, scale(1) rotate(0deg));\\n  }\\n\\n  .theme-toggle .dark-icon {\\n    opacity: var(--theme-dark-icon-opacity, 1);\\n    transform: var(--theme-dark-icon-transform, scale(1) rotate(0deg));\\n  }\\n\\n  /* Hide icons based on theme */\\n  :global([data-theme='dark']) .light-icon {\\n    opacity: 0;\\n    transform: scale(0.5) rotate(-90deg);\\n  }\\n\\n  :global([data-theme='light']) .dark-icon {\\n    opacity: 0;\\n    transform: scale(0.5) rotate(90deg);\\n  }\\n</style>\\n"],"names":[],"mappings":"AAgCE,yCAAc,CACZ,UAAU,CAAE,IAAI,CAChB,MAAM,CAAE,IAAI,CACZ,MAAM,CAAE,OAAO,CACf,OAAO,CAAE,MAAM,CACf,aAAa,CAAE,GAAG,CAClB,OAAO,CAAE,IAAI,CACb,WAAW,CAAE,MAAM,CACnB,eAAe,CAAE,MAAM,CACvB,UAAU,CAAE,gBAAgB,CAAC,IAAI,CAAC,IAAI,CACtC,QAAQ,CAAE,QAAQ,CAClB,KAAK,CAAE,MAAM,CACb,MAAM,CAAE,MACV,CAEA,yCAAa,MAAO,CAClB,gBAAgB,CAAE,IAAI,uBAAuB,CAAC,QAAQ,CACxD,CAEA,iCAAM,CACJ,SAAS,CAAE,OAAO,CAClB,WAAW,CAAE,CAAC,CACd,UAAU,CAAE,SAAS,CAAC,IAAI,CAAC,QAAQ,CAAC,CAAC,OAAO,CAAC,IAAI,CAAC,QAAQ,CAC1D,QAAQ,CAAE,QACZ,CAEA,2BAAa,CAAC,yBAAY,CACxB,OAAO,CAAE,IAAI,0BAA0B,CAAC,EAAE,CAAC,CAC3C,SAAS,CAAE,IAAI,4BAA4B,CAAC,sBAAsB,CACpE,CAEA,2BAAa,CAAC,wBAAW,CACvB,OAAO,CAAE,IAAI,yBAAyB,CAAC,EAAE,CAAC,CAC1C,SAAS,CAAE,IAAI,2BAA2B,CAAC,sBAAsB,CACnE,CAGQ,mBAAoB,CAAC,uCAAY,CACvC,OAAO,CAAE,CAAC,CACV,SAAS,CAAE,MAAM,GAAG,CAAC,CAAC,OAAO,MAAM,CACrC,CAEQ,oBAAqB,CAAC,sCAAW,CACvC,OAAO,CAAE,CAAC,CACV,SAAS,CAAE,MAAM,GAAG,CAAC,CAAC,OAAO,KAAK,CACpC"}`
};
const ThemeToggle = create_ssr_component(($$result, $$props, $$bindings, slots) => {
  theme.subscribe((value) => {
  });
  $$result.css.add(css$1);
  return `${``}`;
});
const QueryClientProvider = create_ssr_component(($$result, $$props, $$bindings, slots) => {
  let { client = new QueryClient() } = $$props;
  setQueryClientContext(client);
  onDestroy(() => {
    client.unmount();
  });
  if ($$props.client === void 0 && $$bindings.client && client !== void 0) $$bindings.client(client);
  return `${slots.default ? slots.default({}) : ``}`;
});
const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      enabled: browser,
      // Запросы активны только в браузере
      refetchOnWindowFocus: true,
      // Обновление данных при фокусе окна
      staleTime: 1e3 * 60 * 5,
      // Данные считаются устаревшими через 5 минут
      retry: 1
      // Повторная попытка при ошибке (только один раз)
    }
  }
});
const css = {
  code: "html, body{margin:0;padding:0;width:100%;height:100%;overflow-x:hidden}#svelte{min-height:100vh;display:flex;flex-direction:column}.theme-toggle-container.svelte-1yls8b5{position:fixed;top:1rem;right:1rem;z-index:1000}.app-container.svelte-1yls8b5{flex:1;display:flex;flex-direction:column}",
  map: `{"version":3,"file":"+layout.svelte","sources":["+layout.svelte"],"sourcesContent":["<script lang=\\"ts\\">\\"use strict\\";\\nimport \\"../app.css\\";\\nimport { registerSW } from \\"$lib/registerSW\\";\\nimport PWA from \\"$lib/components/PWA.svelte\\";\\nimport PWASplash from \\"$lib/components/PWASplash.svelte\\";\\nimport ThemeToggle from \\"$lib/components/ThemeToggle.svelte\\";\\nimport { onMount } from \\"svelte\\";\\nimport { QueryClientProvider } from \\"@tanstack/svelte-query\\";\\nimport { queryClient } from \\"$lib/tanstack/client\\";\\nlet isPWA = false;\\nonMount(() => {\\n  if (typeof window !== \\"undefined\\") {\\n    registerSW({\\n      onOfflineReady: () => {\\n        console.log(\\"App ready to work offline\\");\\n      },\\n      onNeedRefresh: () => {\\n        console.log(\\"New content available, please refresh\\");\\n      }\\n    });\\n    isPWA = window.matchMedia(\\"(display-mode: standalone)\\").matches || window.navigator.standalone === true || document.referrer.includes(\\"android-app://\\");\\n  }\\n});\\n<\/script>\\n\\n<!-- PWA Splash Screen -->\\n{#if isPWA}\\n  <PWASplash />\\n{/if}\\n\\n<QueryClientProvider client={queryClient}>\\n  <main class=\\"app-container\\">\\n    <slot />\\n  </main>\\n</QueryClientProvider>\\n\\n<!-- PWA Install/Update Prompt -->\\n<PWA />\\n\\n<div class=\\"theme-toggle-container\\">\\n  <ThemeToggle />\\n</div>\\n\\n<style>\\n  :global(html, body) {\\n    margin: 0;\\n    padding: 0;\\n    width: 100%;\\n    height: 100%;\\n    overflow-x: hidden;\\n  }\\n  \\n  :global(#svelte) {\\n    min-height: 100vh;\\n    display: flex;\\n    flex-direction: column;\\n  }\\n  \\n  .theme-toggle-container {\\n    position: fixed;\\n    top: 1rem;\\n    right: 1rem;\\n    z-index: 1000; /* Ensure it's above other content */\\n  }\\n\\n  .app-container {\\n    flex: 1;\\n    display: flex;\\n    flex-direction: column;\\n  }\\n</style>\\n"],"names":[],"mappings":"AA4CU,UAAY,CAClB,MAAM,CAAE,CAAC,CACT,OAAO,CAAE,CAAC,CACV,KAAK,CAAE,IAAI,CACX,MAAM,CAAE,IAAI,CACZ,UAAU,CAAE,MACd,CAEQ,OAAS,CACf,UAAU,CAAE,KAAK,CACjB,OAAO,CAAE,IAAI,CACb,cAAc,CAAE,MAClB,CAEA,sCAAwB,CACtB,QAAQ,CAAE,KAAK,CACf,GAAG,CAAE,IAAI,CACT,KAAK,CAAE,IAAI,CACX,OAAO,CAAE,IACX,CAEA,6BAAe,CACb,IAAI,CAAE,CAAC,CACP,OAAO,CAAE,IAAI,CACb,cAAc,CAAE,MAClB"}`
};
const Layout = create_ssr_component(($$result, $$props, $$bindings, slots) => {
  $$result.css.add(css);
  return ` ${``} ${validate_component(QueryClientProvider, "QueryClientProvider").$$render($$result, { client: queryClient }, {}, {
    default: () => {
      return `<main class="app-container svelte-1yls8b5">${slots.default ? slots.default({}) : ``}</main>`;
    }
  })}  ${validate_component(PWA, "PWA").$$render($$result, {}, {}, {})} <div class="theme-toggle-container svelte-1yls8b5">${validate_component(ThemeToggle, "ThemeToggle").$$render($$result, {}, {}, {})} </div>`;
});
export {
  Layout as default
};
