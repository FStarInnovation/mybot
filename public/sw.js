/**
 * Copyright 2018 Google Inc. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *     http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// If the loader is already loaded, just stop.
if (!self.define) {
  let registry = {};

  // Used for `eval` and `importScripts` where we can't get script URL by other means.
  // In both cases, it's safe to use a global var because those functions are synchronous.
  let nextDefineUri;

  const singleRequire = (uri, parentUri) => {
    uri = new URL(uri + ".js", parentUri).href;
    return registry[uri] || (
      
        new Promise(resolve => {
          if ("document" in self) {
            const script = document.createElement("script");
            script.src = uri;
            script.onload = resolve;
            document.head.appendChild(script);
          } else {
            nextDefineUri = uri;
            importScripts(uri);
            resolve();
          }
        })
      
      .then(() => {
        let promise = registry[uri];
        if (!promise) {
          throw new Error(`Module ${uri} didn’t register its module`);
        }
        return promise;
      })
    );
  };

  self.define = (depsNames, factory) => {
    const uri = nextDefineUri || ("document" in self ? document.currentScript.src : "") || location.href;
    if (registry[uri]) {
      // Module is already loading or loaded.
      return;
    }
    let exports = {};
    const require = depUri => singleRequire(depUri, uri);
    const specialDeps = {
      module: { uri },
      exports,
      require
    };
    registry[uri] = Promise.all(depsNames.map(
      depName => specialDeps[depName] || require(depName)
    )).then(deps => {
      factory(...deps);
      return exports;
    });
  };
}
define(['./workbox-10cc9e8c'], (function (workbox) { 'use strict';

  self.skipWaiting();
  workbox.clientsClaim();

  /**
   * The precacheAndRoute() method efficiently caches and responds to
   * requests for URLs in the manifest.
   * See https://goo.gl/S9QRab
   */
  workbox.precacheAndRoute([{
    "url": "_app/immutable/assets/0.DdC7Ipbr.css",
    "revision": "f313dad1e11b64af92eb2780939047f2"
  }, {
    "url": "_app/immutable/assets/2.TIzTRg2W.css",
    "revision": "fa160cad4603f706a5d400eb43de8fb5"
  }, {
    "url": "_app/immutable/assets/3.C-IzXNFj.css",
    "revision": "994124db76b1337d9a7f099d0cc9c63a"
  }, {
    "url": "_app/immutable/assets/4.Da-0dl0j.css",
    "revision": "aef821cdc71a9b17a7385c1855cfd6b2"
  }, {
    "url": "_app/immutable/assets/5.BLCfz8HU.css",
    "revision": "e47180122df4d40410b06224b8bd1a8f"
  }, {
    "url": "_app/immutable/assets/6.DWRTBfN0.css",
    "revision": "c413f9fcd2670797b6c3b958bfd41d5a"
  }, {
    "url": "_app/immutable/assets/7.CdLl7wPD.css",
    "revision": "ba3dfddc3d43d32e6d946594e4947d76"
  }, {
    "url": "_app/immutable/assets/ChatMessage.FiBhqMH9.css",
    "revision": "8ea6d1690af77d85c6920ee7e323126a"
  }, {
    "url": "_app/immutable/assets/ProductCard.Bo-Z5wnb.css",
    "revision": "41e2cf79aa2bfcae811c52f95960b566"
  }, {
    "url": "_app/immutable/chunks/0wcAgnwN.js",
    "revision": "c97a60477c873162ee3302781b2d1e03"
  }, {
    "url": "_app/immutable/chunks/BillStMv.js",
    "revision": "62d8e2362db8d191d4706a1445d00d70"
  }, {
    "url": "_app/immutable/chunks/Bj4bCeCv.js",
    "revision": "52e9e72d38589a43541019c54a97a418"
  }, {
    "url": "_app/immutable/chunks/CwQsjPDU.js",
    "revision": "74fb5135cdaf18d8189a8266c08fec29"
  }, {
    "url": "_app/immutable/chunks/CyngZJ1P.js",
    "revision": "37c2f98235f8a1f6cca6252bf5bf7575"
  }, {
    "url": "_app/immutable/chunks/DCjZxZmo.js",
    "revision": "33fd228e58fc693669ba267e1bdbafb7"
  }, {
    "url": "_app/immutable/chunks/DLWv14wD.js",
    "revision": "6de41fee1364896f3aba4ebde8031036"
  }, {
    "url": "_app/immutable/chunks/DMvx5HM6.js",
    "revision": "2eecb7417d329ca9897e5b779d23e829"
  }, {
    "url": "_app/immutable/chunks/DSj4aDZx.js",
    "revision": "6df3b6e433136659c8103d6259f3bece"
  }, {
    "url": "_app/immutable/chunks/Dym3oGXa.js",
    "revision": "d9b90ae332e8655eb445880b3884552a"
  }, {
    "url": "_app/immutable/chunks/tEWEukYF.js",
    "revision": "0a14e11faf1f74276c27f99338b55d0f"
  }, {
    "url": "_app/immutable/entry/app.D0X5bk1v.js",
    "revision": "8fe6d4d01460b4dbe83e027386b1b7de"
  }, {
    "url": "_app/immutable/entry/start.pNOFgd7y.js",
    "revision": "156d577d230b668a22b6af18f6b49447"
  }, {
    "url": "_app/immutable/nodes/0.CJtH08Jf.js",
    "revision": "c924064952adc213547a213048c3d1df"
  }, {
    "url": "_app/immutable/nodes/1.Du1s9TQN.js",
    "revision": "3da93c34f22ccace19e9c609a5e37098"
  }, {
    "url": "_app/immutable/nodes/2.BiuKsi7l.js",
    "revision": "b1a0092102fcff7730566096d698c1e5"
  }, {
    "url": "_app/immutable/nodes/3.BLDGhT0U.js",
    "revision": "e175e4a7ac3fc05e1751125f5e328488"
  }, {
    "url": "_app/immutable/nodes/4.Cm3HsCTO.js",
    "revision": "3c887adc36d845a1e305e9fd1c5c142b"
  }, {
    "url": "_app/immutable/nodes/5.XJCTi8zF.js",
    "revision": "3cd7735d0ba1d604a6fa3d63a27d3552"
  }, {
    "url": "_app/immutable/nodes/6.BFqKwKjW.js",
    "revision": "23ffec49f899c5b584cee25646c864b7"
  }, {
    "url": "_app/immutable/nodes/7.mhfDkXl6.js",
    "revision": "44b71c1c2b92ff496601be4c2652884a"
  }, {
    "url": "_app/immutable/nodes/8.DDI9JbLF.js",
    "revision": "bcc0d1de0cc829e3e11dcf57a743b1c1"
  }, {
    "url": "favicon.png",
    "revision": "78807cf3a224b06b63790e900b75cfd9"
  }, {
    "url": "offline.html",
    "revision": "c0f87332ebb825b93772fee0e4f32a6d"
  }, {
    "url": "pwa-192x192.png",
    "revision": "78807cf3a224b06b63790e900b75cfd9"
  }, {
    "url": "pwa-512x512.png",
    "revision": "78807cf3a224b06b63790e900b75cfd9"
  }, {
    "url": "registerSW.js",
    "revision": "1872c500de691dce40960bb85481de07"
  }, {
    "url": "favicon.png",
    "revision": "78807cf3a224b06b63790e900b75cfd9"
  }, {
    "url": "pwa-192x192.png",
    "revision": "78807cf3a224b06b63790e900b75cfd9"
  }, {
    "url": "pwa-512x512.png",
    "revision": "78807cf3a224b06b63790e900b75cfd9"
  }, {
    "url": "manifest.webmanifest",
    "revision": "ebb3b725a9a2e51f95042e205fc96736"
  }], {});
  workbox.cleanupOutdatedCaches();

}));
