import { notifyManager, QueryObserver } from "@tanstack/query-core";
import { r as readable, d as derived } from "./index2.js";
import { g as getIsRestoringContext, a as getQueryClientContext } from "./context.js";
import { h as get_store_value } from "./ssr.js";
function useIsRestoring() {
  return getIsRestoringContext();
}
function useQueryClient(queryClient) {
  return getQueryClientContext();
}
function isSvelteStore(obj) {
  return "subscribe" in obj && typeof obj.subscribe === "function";
}
function noop() {
}
function createBaseQuery(options, Observer, queryClient) {
  const client = useQueryClient();
  const isRestoring = useIsRestoring();
  const optionsStore = isSvelteStore(options) ? options : readable(options);
  const defaultedOptionsStore = derived([optionsStore, isRestoring], ([$optionsStore, $isRestoring]) => {
    const defaultedOptions = client.defaultQueryOptions($optionsStore);
    defaultedOptions._optimisticResults = $isRestoring ? "isRestoring" : "optimistic";
    return defaultedOptions;
  });
  const observer = new Observer(client, get_store_value(defaultedOptionsStore));
  defaultedOptionsStore.subscribe(($defaultedOptions) => {
    observer.setOptions($defaultedOptions);
  });
  const result = derived(isRestoring, ($isRestoring, set) => {
    const unsubscribe = $isRestoring ? noop : observer.subscribe(notifyManager.batchCalls(set));
    observer.updateResult();
    return unsubscribe;
  });
  const { subscribe } = derived([result, defaultedOptionsStore], ([$result, $defaultedOptionsStore]) => {
    $result = observer.getOptimisticResult($defaultedOptionsStore);
    return !$defaultedOptionsStore.notifyOnChangeProps ? observer.trackResult($result) : $result;
  });
  return { subscribe };
}
function createQuery(options, queryClient) {
  return createBaseQuery(options, QueryObserver);
}
const API_BASE_URL = "/api";
async function getProducts(params) {
  const queryParams = new URLSearchParams();
  if (params?.page) queryParams.append("page", params.page.toString());
  if (params?.limit) queryParams.append("limit", params.limit.toString());
  if (params?.category) queryParams.append("category", params.category);
  if (params?.search) queryParams.append("search", params.search);
  if (params?.sort) queryParams.append("sort", params.sort);
  const url = `${API_BASE_URL}/products?${queryParams.toString()}`;
  const response = await fetch(url);
  if (!response.ok) {
    throw new Error(`Ошибка при получении списка товаров: ${response.status}`);
  }
  return await response.json();
}
async function getProductById(id) {
  const url = `${API_BASE_URL}/products/${id}`;
  const response = await fetch(url);
  if (!response.ok) {
    throw new Error(`Товар с ID ${id} не найден`);
  }
  return await response.json();
}
function useProduct(id) {
  return createQuery({
    queryKey: ["product", id],
    queryFn: () => getProductById(id)
  });
}
function useProducts(params) {
  return createQuery({
    queryKey: ["products", params],
    queryFn: () => getProducts(params)
  });
}
export {
  useProduct as a,
  useProducts as u
};
