

export const index = 6;
let component_cache;
export const component = async () => component_cache ??= (await import('../entries/pages/dashboard/_page.svelte.js')).default;
export const imports = ["_app/immutable/nodes/6.BFqKwKjW.js","_app/immutable/chunks/tEWEukYF.js","_app/immutable/chunks/0wcAgnwN.js","_app/immutable/chunks/DMvx5HM6.js","_app/immutable/chunks/DSj4aDZx.js"];
export const stylesheets = ["_app/immutable/assets/6.DWRTBfN0.css"];
export const fonts = [];
