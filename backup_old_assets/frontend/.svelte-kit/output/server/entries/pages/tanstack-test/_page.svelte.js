import { c as create_ssr_component, a as subscribe, n as noop, e as escape, b as each, d as add_attribute } from "../../../chunks/ssr.js";
import { u as useProducts } from "../../../chunks/product-queries.js";
const Page = create_ssr_component(($$result, $$props, $$bindings, slots) => {
  let $productsQuery, $$unsubscribe_productsQuery;
  let $$unsubscribe_singleProductQuery = noop, $$subscribe_singleProductQuery = () => ($$unsubscribe_singleProductQuery(), $$unsubscribe_singleProductQuery = subscribe(singleProductQuery, ($$value) => $$value), singleProductQuery);
  const productsQuery = useProducts({ page: 1, limit: 5 });
  $$unsubscribe_productsQuery = subscribe(productsQuery, (value) => $productsQuery = value);
  let productToFetchIdInput = "1";
  let singleProductQuery = null;
  $$subscribe_singleProductQuery();
  {
    {
      $$subscribe_singleProductQuery(singleProductQuery = null);
    }
  }
  $$unsubscribe_productsQuery();
  $$unsubscribe_singleProductQuery();
  return `${$$result.head += `<!-- HEAD_svelte-1gjgzaj_START -->${$$result.title = `<title>TanStack Query Test Page</title>`, ""}<!-- HEAD_svelte-1gjgzaj_END -->`, ""} <div class="container mx-auto p-4"><h1 class="text-2xl font-bold mb-6" data-svelte-h="svelte-icz0p4">TanStack Query Test Page</h1> <div class="mb-6"><button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" data-svelte-h="svelte-2cb43">Setup/Reset Test Database</button> <p class="text-sm text-gray-600 mt-1" data-svelte-h="svelte-hmsg62">Click this to ensure test products are in the database.</p></div>  <section class="mb-8 p-4 border rounded-lg shadow"><h2 class="text-xl font-semibold mb-3" data-svelte-h="svelte-r7j4rw">Fetch All Products (First 5)</h2> ${$productsQuery.isLoading ? `<p data-svelte-h="svelte-2zn42q">Loading products...</p>` : `${$productsQuery.isError ? `<p class="text-red-500">Error fetching products: ${escape($productsQuery.error?.message)}</p>` : `${$productsQuery.data ? `<ul class="list-disc pl-5">${each($productsQuery.data.products, (product) => {
    return `<li>${escape(product.name)} (ID: ${escape(product.id)}) - Price: $${escape(product.price)}</li>`;
  })}</ul> ${$productsQuery.data.products.length === 0 ? `<p data-svelte-h="svelte-qeq3ex">No products found. Try setting up the test database.</p>` : ``}` : `<p data-svelte-h="svelte-1d0ta83">No data.</p>`}`}`} <button ${$productsQuery.isFetching ? "disabled" : ""} class="mt-3 bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">${$productsQuery.isFetching ? `Refetching...` : `Refetch Products List`}</button></section>  <section class="p-4 border rounded-lg shadow"><h2 class="text-xl font-semibold mb-3" data-svelte-h="svelte-1q2r97c">Fetch Single Product by ID</h2> <div class="flex items-center space-x-2 mb-3"><input type="number" placeholder="Enter Product ID" class="border p-2 rounded w-48"${add_attribute("value", productToFetchIdInput, 0)}> <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" data-svelte-h="svelte-1y5rz5p">Fetch Product</button></div> ${`<p class="text-gray-500" data-svelte-h="svelte-9lpqbs">Enter an ID and click &quot;Fetch Product&quot;.</p>`}</section></div>`;
});
export {
  Page as default
};
