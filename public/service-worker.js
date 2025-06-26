const s=location.pathname.split("/").slice(0,-1).join("/"),o=[s+"/_app/immutable/entry/app.Cnr43odm.js",s+"/_app/immutable/nodes/0.BogSIWt5.js",s+"/_app/immutable/assets/0.CNC58I01.css",s+"/_app/immutable/nodes/1.B-BDAfnp.js",s+"/_app/immutable/nodes/2.DK6SYFA4.js",s+"/_app/immutable/assets/2.TIzTRg2W.css",s+"/_app/immutable/nodes/3.UBljEEFQ.js",s+"/_app/immutable/assets/3.C-IzXNFj.css",s+"/_app/immutable/nodes/4.DOPFaBH-.js",s+"/_app/immutable/assets/4.Da-0dl0j.css",s+"/_app/immutable/nodes/5.BcqSSaDd.js",s+"/_app/immutable/assets/5.cgDP7Mw4.css",s+"/_app/immutable/nodes/6.CTRYfzeb.js",s+"/_app/immutable/assets/6.DW2oQtLm.css",s+"/_app/immutable/nodes/7.Za1N-sVZ.js",s+"/_app/immutable/assets/7.BW7VhtCN.css",s+"/_app/immutable/nodes/8.fw0BP7i0.js",s+"/_app/immutable/assets/8.CDalKeAC.css",s+"/_app/immutable/nodes/9.Ct8qKugc.js",s+"/_app/immutable/assets/ProductCard.RwsYY4LA.css",s+"/_app/immutable/assets/ChatMessage.Bbr4Ac2t.css",s+"/_app/immutable/chunks/2RcMFXR-.js",s+"/_app/immutable/chunks/B9R_48nD.js",s+"/_app/immutable/chunks/BBLyfC8K.js",s+"/_app/immutable/chunks/BS73ZcEi.js",s+"/_app/immutable/chunks/CD-Pxlr0.js",s+"/_app/immutable/chunks/Ch6yRt5W.js",s+"/_app/immutable/chunks/CukK43zF.js",s+"/_app/immutable/chunks/DXQd-9eQ.js",s+"/_app/immutable/chunks/DzjKHtkU.js",s+"/_app/immutable/chunks/IUfontf9.js",s+"/_app/immutable/chunks/zifXhSmD.js",s+"/_app/immutable/entry/start.BpwB3W6i.js"],u=[s+"/.env",s+"/favicon.png",s+"/manifest.json",s+"/offline.html",s+"/pwa-192x192.png",s+"/pwa-512x512.png",s+"/sw.js",s+"/vite.config.ts"],m="1751062532000",l=`cache-${m}`,r=[...o,...u];self.addEventListener("install", t => {
  t.waitUntil(
    caches.open(l).then(async cache => {
      // Более устойчивый метод кэширования. Пропускаем файлы, которые не удалось загрузить
      const failedItems = [];
      for (const url of r) {
        try {
          await cache.add(url);
        } catch (error) {
          console.warn('Could not cache:', url, error);
          failedItems.push(url);
        }
      }
      console.log('Service Worker installation complete. Failed items:', failedItems);
    })
  );
});
self.addEventListener("activate",t=>{t.waitUntil(caches.keys().then(async a=>{for(const e of a)e!==l&&await caches.delete(e)}))});self.addEventListener("fetch",t=>{t.request.url.startsWith("http")&&(t.request.url.includes("/api/")||t.request.url.includes("/llm/")||t.respondWith(caches.match(t.request).then(a=>a||fetch(t.request).then(e=>caches.open(l).then(i=>(i.put(t.request,e.clone()),e))))))});self.addEventListener("push",t=>{let a={};if(t.data)try{a=t.data.json()}catch{a={title:"MyBot",body:t.data.text()}}const e=a.title??"MyBot",i={body:a.body??"",icon:a.icon??"/icon-192x192.png",badge:a.badge??"/icon-72x72.png",data:{url:a.url??"/"}};t.waitUntil(self.registration.showNotification(e,i))});self.addEventListener("notificationclick",t=>{var i;const e=((i=t.notification.data)==null?void 0:i.url)||"/";t.notification.close(),t.waitUntil(self.clients.matchAll({type:"window",includeUncontrolled:!0}).then(p=>{for(const c of p){const n=c;if(n.url===e&&"focus"in n)return n.focus()}if(self.clients.openWindow)return self.clients.openWindow(e)}))});
