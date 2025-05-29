import { c as create_ssr_component, a as subscribe, b as each, d as add_attribute, v as validate_component } from "../../../chunks/ssr.js";
import { A as AgUIEventType, C as ChatMessage } from "../../../chunks/ChatMessage.js";
import { w as writable } from "../../../chunks/index2.js";
function createAgUIStore() {
  const { subscribe: subscribe2, update, set } = writable([]);
  return {
    subscribe: subscribe2,
    // Добавление события
    addEvent: (event) => {
      update((events) => [...events, event]);
    },
    // Обновление события
    updateEvent: (id, eventData) => {
      update((events) => events.map(
        (event) => event.id === id ? { ...event, ...eventData } : event
      ));
    },
    // Удаление события
    removeEvent: (id) => {
      update((events) => events.filter((event) => event.id !== id));
    },
    // Отправка текстового сообщения от пользователя
    sendUserText: (text) => {
      const event = {
        type: AgUIEventType.TEXT,
        id: `user-${Date.now()}`,
        timestamp: (/* @__PURE__ */ new Date()).toISOString(),
        sender: "user",
        text,
        markdown: false
      };
      update((events) => [...events, event]);
      return event;
    },
    // Добавление текстового сообщения от бота
    addBotText: (text, markdown = false) => {
      const event = {
        type: AgUIEventType.TEXT,
        id: `bot-${Date.now()}`,
        timestamp: (/* @__PURE__ */ new Date()).toISOString(),
        sender: "bot",
        text,
        markdown
      };
      update((events) => [...events, event]);
      return event;
    },
    // Добавление карточки
    addCard: (card) => {
      const event = {
        type: AgUIEventType.CARD,
        id: `card-${Date.now()}`,
        timestamp: (/* @__PURE__ */ new Date()).toISOString(),
        sender: "bot",
        card
      };
      update((events) => [...events, event]);
      return event;
    },
    // Добавление формы
    addForm: (formData) => {
      const event = {
        type: AgUIEventType.FORM,
        id: `form-${Date.now()}`,
        timestamp: (/* @__PURE__ */ new Date()).toISOString(),
        sender: "bot",
        title: formData.title,
        description: formData.description,
        fields: formData.fields,
        submitLabel: formData.submitLabel,
        cancelLabel: formData.cancelLabel
      };
      update((events) => [...events, event]);
      return event;
    },
    // Добавление списка
    addList: (listData) => {
      const event = {
        type: AgUIEventType.LIST,
        id: `list-${Date.now()}`,
        timestamp: (/* @__PURE__ */ new Date()).toISOString(),
        sender: "bot",
        title: listData.title,
        items: listData.items
      };
      update((events) => [...events, event]);
      return event;
    },
    // Добавление вызова инструмента
    addToolCall: (tool, args) => {
      const event = {
        type: AgUIEventType.TOOL_CALL,
        id: `tool-call-${Date.now()}`,
        timestamp: (/* @__PURE__ */ new Date()).toISOString(),
        sender: "bot",
        tool,
        args
      };
      update((events) => [...events, event]);
      return event;
    },
    // Добавление результата инструмента
    addToolResult: (tool, result) => {
      const event = {
        type: AgUIEventType.TOOL_RESULT,
        id: `tool-result-${Date.now()}`,
        timestamp: (/* @__PURE__ */ new Date()).toISOString(),
        sender: "user",
        tool,
        result
      };
      update((events) => [...events, event]);
      return event;
    },
    // Добавление статуса
    addStatus: (status, message) => {
      const event = {
        type: AgUIEventType.STATUS,
        id: `status-${Date.now()}`,
        timestamp: (/* @__PURE__ */ new Date()).toISOString(),
        sender: "bot",
        status,
        message
      };
      update((events) => [...events, event]);
      return event;
    },
    // Сброс хранилища
    reset: () => set([])
  };
}
const agUIStore = createAgUIStore();
const css = {
  code: ".demo-container.svelte-y9w2zs.svelte-y9w2zs{max-width:900px;margin:0 auto;padding:2rem;font-family:system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif}.demo-header.svelte-y9w2zs.svelte-y9w2zs{text-align:center;margin-bottom:2rem}.demo-header.svelte-y9w2zs h1.svelte-y9w2zs{font-size:1.8rem;margin-bottom:0.5rem;color:var(--text-primary)}.demo-header.svelte-y9w2zs p.svelte-y9w2zs{color:var(--text-secondary);font-size:1rem}.chat-container.svelte-y9w2zs.svelte-y9w2zs{display:flex;flex-direction:column;height:70vh;border-radius:12px;overflow:hidden;border:1px solid var(--border-color);background-color:var(--bg-primary);box-shadow:0 4px 20px rgba(0, 0, 0, 0.05)}.messages-container.svelte-y9w2zs.svelte-y9w2zs{flex-grow:1;overflow-y:auto;padding:1.5rem;display:flex;flex-direction:column;gap:1rem}.input-container.svelte-y9w2zs.svelte-y9w2zs{padding:1rem;border-top:1px solid var(--border-color);background-color:var(--bg-secondary)}.input-container.svelte-y9w2zs form.svelte-y9w2zs{display:flex;gap:0.5rem}.input-container.svelte-y9w2zs input.svelte-y9w2zs{flex-grow:1;padding:0.75rem 1rem;border-radius:8px;border:1px solid var(--border-color);font-size:0.95rem;background-color:var(--bg-primary);color:var(--text-primary)}.input-container.svelte-y9w2zs input.svelte-y9w2zs:focus{outline:none;border-color:var(--accent-primary);box-shadow:0 0 0 2px rgba(var(--accent-primary-rgb), 0.1)}.input-container.svelte-y9w2zs button.svelte-y9w2zs{padding:0.75rem 1.25rem;border-radius:8px;border:none;background-color:var(--accent-primary);color:var(--button-text);font-weight:500;cursor:pointer;transition:background-color 0.2s ease, transform 0.2s ease}.input-container.svelte-y9w2zs button.svelte-y9w2zs:hover{background-color:var(--button-hover-bg);transform:translateY(-1px)}.input-container.svelte-y9w2zs button.svelte-y9w2zs:active{transform:translateY(0)}.messages-container.svelte-y9w2zs.svelte-y9w2zs::-webkit-scrollbar{width:6px}.messages-container.svelte-y9w2zs.svelte-y9w2zs::-webkit-scrollbar-track{background:transparent}.messages-container.svelte-y9w2zs.svelte-y9w2zs::-webkit-scrollbar-thumb{background-color:var(--border-color);border-radius:6px}.messages-container.svelte-y9w2zs.svelte-y9w2zs::-webkit-scrollbar-thumb:hover{background-color:var(--text-muted)}",
  map: '{"version":3,"file":"+page.svelte","sources":["+page.svelte"],"sourcesContent":["<script lang=\\"ts\\">\\"use strict\\";\\nimport { onMount } from \\"svelte\\";\\nimport ChatMessage from \\"$lib/components/ChatMessage.svelte\\";\\nimport { agUIStore } from \\"$lib/ag-ui/store\\";\\nimport { AgUIEventType } from \\"$lib/ag-ui/types\\";\\nlet messages = [];\\n$: messages = $agUIStore;\\nfunction sendUserMessage(text) {\\n  agUIStore.sendUserText(text);\\n  setTimeout(() => {\\n    respondWithDifferentMessageTypes();\\n  }, 1e3);\\n}\\nfunction respondWithDifferentMessageTypes() {\\n  agUIStore.addBotText(\\"\\\\u041F\\\\u0440\\\\u0438\\\\u0432\\\\u0435\\\\u0442! \\\\u042F \\\\u043F\\\\u043E\\\\u043A\\\\u0430\\\\u0436\\\\u0443 \\\\u0440\\\\u0430\\\\u0437\\\\u043B\\\\u0438\\\\u0447\\\\u043D\\\\u044B\\\\u0435 \\\\u0442\\\\u0438\\\\u043F\\\\u044B \\\\u0441\\\\u043E\\\\u043E\\\\u0431\\\\u0449\\\\u0435\\\\u043D\\\\u0438\\\\u0439 AG-UI, \\\\u043A\\\\u043E\\\\u0442\\\\u043E\\\\u0440\\\\u044B\\\\u0435 \\\\u043F\\\\u043E\\\\u0434\\\\u0434\\\\u0435\\\\u0440\\\\u0436\\\\u0438\\\\u0432\\\\u0430\\\\u0435\\\\u0442 \\\\u043D\\\\u0430\\\\u0448 \\\\u0447\\\\u0430\\\\u0442.\\", true);\\n  setTimeout(() => {\\n    agUIStore.addCard({\\n      title: \\"\\\\u0421\\\\u043C\\\\u0430\\\\u0440\\\\u0442\\\\u0444\\\\u043E\\\\u043D Pixel 7 Pro\\",\\n      description: \\"\\\\u0424\\\\u043B\\\\u0430\\\\u0433\\\\u043C\\\\u0430\\\\u043D\\\\u0441\\\\u043A\\\\u0438\\\\u0439 \\\\u0441\\\\u043C\\\\u0430\\\\u0440\\\\u0442\\\\u0444\\\\u043E\\\\u043D Google \\\\u0441 \\\\u043F\\\\u0440\\\\u043E\\\\u0434\\\\u0432\\\\u0438\\\\u043D\\\\u0443\\\\u0442\\\\u043E\\\\u0439 \\\\u043A\\\\u0430\\\\u043C\\\\u0435\\\\u0440\\\\u043E\\\\u0439 \\\\u0438 \\\\u0447\\\\u0438\\\\u0441\\\\u0442\\\\u044B\\\\u043C Android.\\",\\n      image: \\"https://lh3.googleusercontent.com/spp/AP8QK7f2WV-08q78UtHkQrz7-cRX93KQcCQlZiCkK9j4_qJlUVPrh0XS5ICE1Jxb8dVixHE6ulwLuR3_ISGmyQogpxxO2AiIwOUZHN0rgPCGTJ5sP91GAhQJfDx_9Z4XCq_mj0iYoC2-LLbXzXXkj7ZXnkHHRdJBBdY=w100-h243-rw-no\\",\\n      fields: [\\n        { name: \\"\\\\u0426\\\\u0435\\\\u043D\\\\u0430\\", value: \\"79 990 \\\\u20BD\\" },\\n        { name: \\"\\\\u041F\\\\u0430\\\\u043C\\\\u044F\\\\u0442\\\\u044C\\", value: \\"128 \\\\u0413\\\\u0411\\", inline: true },\\n        { name: \\"\\\\u0426\\\\u0432\\\\u0435\\\\u0442\\", value: \\"\\\\u0427\\\\u0451\\\\u0440\\\\u043D\\\\u044B\\\\u0439\\", inline: true }\\n      ],\\n      actions: [\\n        { id: \\"buy\\", label: \\"\\\\u041A\\\\u0443\\\\u043F\\\\u0438\\\\u0442\\\\u044C\\", style: \\"primary\\" },\\n        { id: \\"add_to_cart\\", label: \\"\\\\u0412 \\\\u043A\\\\u043E\\\\u0440\\\\u0437\\\\u0438\\\\u043D\\\\u0443\\", style: \\"secondary\\" },\\n        { id: \\"compare\\", label: \\"\\\\u0421\\\\u0440\\\\u0430\\\\u0432\\\\u043D\\\\u0438\\\\u0442\\\\u044C\\", style: \\"info\\" }\\n      ],\\n      footer: \\"\\\\u0411\\\\u0435\\\\u0441\\\\u043F\\\\u043B\\\\u0430\\\\u0442\\\\u043D\\\\u0430\\\\u044F \\\\u0434\\\\u043E\\\\u0441\\\\u0442\\\\u0430\\\\u0432\\\\u043A\\\\u0430\\"\\n    });\\n    setTimeout(() => {\\n      agUIStore.addList({\\n        title: \\"\\\\u041F\\\\u043E\\\\u043F\\\\u0443\\\\u043B\\\\u044F\\\\u0440\\\\u043D\\\\u044B\\\\u0435 \\\\u0442\\\\u043E\\\\u0432\\\\u0430\\\\u0440\\\\u044B\\",\\n        items: [\\n          {\\n            id: \\"item1\\",\\n            title: \\"AirPods Pro\\",\\n            description: \\"\\\\u0411\\\\u0435\\\\u0441\\\\u043F\\\\u0440\\\\u043E\\\\u0432\\\\u043E\\\\u0434\\\\u043D\\\\u044B\\\\u0435 \\\\u043D\\\\u0430\\\\u0443\\\\u0448\\\\u043D\\\\u0438\\\\u043A\\\\u0438 \\\\u0441 \\\\u0448\\\\u0443\\\\u043C\\\\u043E\\\\u043F\\\\u043E\\\\u0434\\\\u0430\\\\u0432\\\\u043B\\\\u0435\\\\u043D\\\\u0438\\\\u0435\\\\u043C\\",\\n            image: \\"https://lh3.googleusercontent.com/spp/AP8QK7dX9z6PhmRE0HKKoiuKKMO-0qYrd31UtxeAQsLe7HdvDQQn7UxMrhJlE1YDvCWj2yQ8M61XYJ1gPD7gK-vdQWxpL9Mmv-wj9Pj4tZvA5KTfkNJDLfbHHYnHm1WR7QwHxOh0eL4GNrE8QiOe6cTd7nXzuqeH=w100-h100-rw-no\\",\\n            actions: [\\n              { id: \\"view_item1\\", label: \\"\\\\u041F\\\\u043E\\\\u0441\\\\u043C\\\\u043E\\\\u0442\\\\u0440\\\\u0435\\\\u0442\\\\u044C\\", icon: \\"\\\\u{1F441}\\\\uFE0F\\" }\\n            ]\\n          },\\n          {\\n            id: \\"item2\\",\\n            title: \\"MacBook Air M2\\",\\n            description: \\"\\\\u0422\\\\u043E\\\\u043D\\\\u043A\\\\u0438\\\\u0439 \\\\u0438 \\\\u043B\\\\u0451\\\\u0433\\\\u043A\\\\u0438\\\\u0439 \\\\u043D\\\\u043E\\\\u0443\\\\u0442\\\\u0431\\\\u0443\\\\u043A \\\\u0441 \\\\u0447\\\\u0438\\\\u043F\\\\u043E\\\\u043C M2\\",\\n            image: \\"https://lh3.googleusercontent.com/spp/AP8QK7fzxaX9JoQBPF1IhRoCRgUe44bianVXxay988QA4AzbBVYgJ20Mpdg4bZGFz-Qi9GHsF8TzkhxBmaHHH4DKgGEwqAUu6oOL5cfdY2RHsLzKNDLhZ1WbZFTYRWkEoxOITWNXJLKsXcWp-9zcqK-pZWGdRCUb=w100-h67-rw-no\\",\\n            actions: [\\n              { id: \\"view_item2\\", label: \\"\\\\u041F\\\\u043E\\\\u0441\\\\u043C\\\\u043E\\\\u0442\\\\u0440\\\\u0435\\\\u0442\\\\u044C\\", icon: \\"\\\\u{1F441}\\\\uFE0F\\" }\\n            ]\\n          },\\n          {\\n            id: \\"item3\\",\\n            title: \\"iPad Pro\\",\\n            description: \\"\\\\u041F\\\\u043B\\\\u0430\\\\u043D\\\\u0448\\\\u0435\\\\u0442 \\\\u0441 \\\\u043F\\\\u0440\\\\u043E\\\\u0446\\\\u0435\\\\u0441\\\\u0441\\\\u043E\\\\u0440\\\\u043E\\\\u043C M2 \\\\u0438 \\\\u0434\\\\u0438\\\\u0441\\\\u043F\\\\u043B\\\\u0435\\\\u0435\\\\u043C Liquid Retina XDR\\",\\n            image: \\"https://lh3.googleusercontent.com/spp/AP8QK7eipA0CZTXYA1Hv_Kamy6bNjCt6n3fMQHawrYO6cV5bOXTH4byIKJCCCUGbJHzKkXtSFvokZ38i8WQ90OvNdMUDOdtztfWRZyM1wJUz4MSGOq8zomRVn2e8iFKnC5KO-xWtWkRFYSjCq56H80TqRNHKJaQ=w100-h76-rw-no\\",\\n            actions: [\\n              { id: \\"view_item3\\", label: \\"\\\\u041F\\\\u043E\\\\u0441\\\\u043C\\\\u043E\\\\u0442\\\\u0440\\\\u0435\\\\u0442\\\\u044C\\", icon: \\"\\\\u{1F441}\\\\uFE0F\\" }\\n            ]\\n          }\\n        ]\\n      });\\n      setTimeout(() => {\\n        const formFields = [\\n          {\\n            id: \\"name\\",\\n            label: \\"\\\\u0418\\\\u043C\\\\u044F\\",\\n            type: \\"text\\",\\n            placeholder: \\"\\\\u0412\\\\u0432\\\\u0435\\\\u0434\\\\u0438\\\\u0442\\\\u0435 \\\\u0432\\\\u0430\\\\u0448\\\\u0435 \\\\u0438\\\\u043C\\\\u044F\\",\\n            required: true\\n          },\\n          {\\n            id: \\"email\\",\\n            label: \\"Email\\",\\n            type: \\"email\\",\\n            placeholder: \\"example@email.com\\",\\n            required: true,\\n            validation: {\\n              pattern: \\"^[\\\\\\\\w-\\\\\\\\.]+@([\\\\\\\\w-]+\\\\\\\\.)+[\\\\\\\\w-]{2,4}$\\"\\n            }\\n          },\\n          {\\n            id: \\"phone\\",\\n            label: \\"\\\\u0422\\\\u0435\\\\u043B\\\\u0435\\\\u0444\\\\u043E\\\\u043D\\",\\n            type: \\"text\\",\\n            placeholder: \\"+7 (___) ___-__-__\\"\\n          },\\n          {\\n            id: \\"product\\",\\n            label: \\"\\\\u0418\\\\u043D\\\\u0442\\\\u0435\\\\u0440\\\\u0435\\\\u0441\\\\u0443\\\\u044E\\\\u0449\\\\u0438\\\\u0439 \\\\u0442\\\\u043E\\\\u0432\\\\u0430\\\\u0440\\",\\n            type: \\"select\\",\\n            options: [\\n              { label: \\"\\\\u0421\\\\u043C\\\\u0430\\\\u0440\\\\u0442\\\\u0444\\\\u043E\\\\u043D\\", value: \\"smartphone\\" },\\n              { label: \\"\\\\u041D\\\\u043E\\\\u0443\\\\u0442\\\\u0431\\\\u0443\\\\u043A\\", value: \\"laptop\\" },\\n              { label: \\"\\\\u041F\\\\u043B\\\\u0430\\\\u043D\\\\u0448\\\\u0435\\\\u0442\\", value: \\"tablet\\" },\\n              { label: \\"\\\\u041D\\\\u0430\\\\u0443\\\\u0448\\\\u043D\\\\u0438\\\\u043A\\\\u0438\\", value: \\"headphones\\" }\\n            ]\\n          },\\n          {\\n            id: \\"message\\",\\n            label: \\"\\\\u0421\\\\u043E\\\\u043E\\\\u0431\\\\u0449\\\\u0435\\\\u043D\\\\u0438\\\\u0435\\",\\n            type: \\"textarea\\",\\n            placeholder: \\"\\\\u0412\\\\u0432\\\\u0435\\\\u0434\\\\u0438\\\\u0442\\\\u0435 \\\\u0432\\\\u0430\\\\u0448\\\\u0435 \\\\u0441\\\\u043E\\\\u043E\\\\u0431\\\\u0449\\\\u0435\\\\u043D\\\\u0438\\\\u0435\\"\\n          },\\n          {\\n            id: \\"agree\\",\\n            label: \\"\\\\u0421\\\\u043E\\\\u0433\\\\u043B\\\\u0430\\\\u0441\\\\u0438\\\\u0435 \\\\u0441 \\\\u0443\\\\u0441\\\\u043B\\\\u043E\\\\u0432\\\\u0438\\\\u044F\\\\u043C\\\\u0438\\",\\n            type: \\"checkbox\\",\\n            placeholder: \\"\\\\u042F \\\\u0441\\\\u043E\\\\u0433\\\\u043B\\\\u0430\\\\u0441\\\\u0435\\\\u043D \\\\u0441 \\\\u0443\\\\u0441\\\\u043B\\\\u043E\\\\u0432\\\\u0438\\\\u044F\\\\u043C\\\\u0438 \\\\u043E\\\\u0431\\\\u0440\\\\u0430\\\\u0431\\\\u043E\\\\u0442\\\\u043A\\\\u0438 \\\\u043F\\\\u0435\\\\u0440\\\\u0441\\\\u043E\\\\u043D\\\\u0430\\\\u043B\\\\u044C\\\\u043D\\\\u044B\\\\u0445 \\\\u0434\\\\u0430\\\\u043D\\\\u043D\\\\u044B\\\\u0445\\",\\n            required: true\\n          }\\n        ];\\n        agUIStore.addForm({\\n          title: \\"\\\\u0424\\\\u043E\\\\u0440\\\\u043C\\\\u0430 \\\\u043E\\\\u0431\\\\u0440\\\\u0430\\\\u0442\\\\u043D\\\\u043E\\\\u0439 \\\\u0441\\\\u0432\\\\u044F\\\\u0437\\\\u0438\\",\\n          description: \\"\\\\u0417\\\\u0430\\\\u043F\\\\u043E\\\\u043B\\\\u043D\\\\u0438\\\\u0442\\\\u0435 \\\\u0444\\\\u043E\\\\u0440\\\\u043C\\\\u0443, \\\\u0438 \\\\u043C\\\\u044B \\\\u0441\\\\u0432\\\\u044F\\\\u0436\\\\u0435\\\\u043C\\\\u0441\\\\u044F \\\\u0441 \\\\u0432\\\\u0430\\\\u043C\\\\u0438 \\\\u0432 \\\\u0431\\\\u043B\\\\u0438\\\\u0436\\\\u0430\\\\u0439\\\\u0448\\\\u0435\\\\u0435 \\\\u0432\\\\u0440\\\\u0435\\\\u043C\\\\u044F\\",\\n          fields: formFields,\\n          submitLabel: \\"\\\\u041E\\\\u0442\\\\u043F\\\\u0440\\\\u0430\\\\u0432\\\\u0438\\\\u0442\\\\u044C\\",\\n          cancelLabel: \\"\\\\u041E\\\\u0442\\\\u043C\\\\u0435\\\\u043D\\\\u0430\\"\\n        });\\n        setTimeout(() => {\\n          agUIStore.addStatus(\\"info\\", \\"\\\\u0414\\\\u0435\\\\u043C\\\\u043E\\\\u043D\\\\u0441\\\\u0442\\\\u0440\\\\u0430\\\\u0446\\\\u0438\\\\u044F \\\\u0442\\\\u0438\\\\u043F\\\\u043E\\\\u0432 \\\\u0441\\\\u043E\\\\u043E\\\\u0431\\\\u0449\\\\u0435\\\\u043D\\\\u0438\\\\u0439 AG-UI \\\\u0437\\\\u0430\\\\u0432\\\\u0435\\\\u0440\\\\u0448\\\\u0435\\\\u043D\\\\u0430!\\");\\n        }, 1e3);\\n      }, 1e3);\\n    }, 1e3);\\n  }, 1e3);\\n}\\nfunction handleFormSubmit(event) {\\n  const { formId, values } = event.detail;\\n  agUIStore.addBotText(`\\\\u0424\\\\u043E\\\\u0440\\\\u043C\\\\u0430 \\\\u043E\\\\u0442\\\\u043F\\\\u0440\\\\u0430\\\\u0432\\\\u043B\\\\u0435\\\\u043D\\\\u0430! ID: ${formId}, \\\\u0434\\\\u0430\\\\u043D\\\\u043D\\\\u044B\\\\u0435: ${JSON.stringify(values)}`);\\n}\\nfunction handleFormCancel() {\\n  agUIStore.addBotText(\\"\\\\u0417\\\\u0430\\\\u043F\\\\u043E\\\\u043B\\\\u043D\\\\u0435\\\\u043D\\\\u0438\\\\u0435 \\\\u0444\\\\u043E\\\\u0440\\\\u043C\\\\u044B \\\\u043E\\\\u0442\\\\u043C\\\\u0435\\\\u043D\\\\u0435\\\\u043D\\\\u043E\\");\\n}\\nfunction handleCardAction(event) {\\n  const { actionId, cardId } = event.detail;\\n  agUIStore.addBotText(`\\\\u0414\\\\u0435\\\\u0439\\\\u0441\\\\u0442\\\\u0432\\\\u0438\\\\u0435 \\\\u043F\\\\u043E \\\\u043A\\\\u0430\\\\u0440\\\\u0442\\\\u043E\\\\u0447\\\\u043A\\\\u0435: ${actionId} \\\\u0434\\\\u043B\\\\u044F \\\\u043A\\\\u0430\\\\u0440\\\\u0442\\\\u043E\\\\u0447\\\\u043A\\\\u0438 ${cardId}`);\\n}\\nfunction handleListItemSelect(event) {\\n  const { listId, itemId } = event.detail;\\n  agUIStore.addBotText(`\\\\u0412\\\\u044B\\\\u0431\\\\u0440\\\\u0430\\\\u043D \\\\u044D\\\\u043B\\\\u0435\\\\u043C\\\\u0435\\\\u043D\\\\u0442 ${itemId} \\\\u0438\\\\u0437 \\\\u0441\\\\u043F\\\\u0438\\\\u0441\\\\u043A\\\\u0430 ${listId}`);\\n}\\nfunction handleListAction(event) {\\n  const { listId, itemId, actionId } = event.detail;\\n  agUIStore.addBotText(`\\\\u0414\\\\u0435\\\\u0439\\\\u0441\\\\u0442\\\\u0432\\\\u0438\\\\u0435 ${actionId} \\\\u0434\\\\u043B\\\\u044F \\\\u044D\\\\u043B\\\\u0435\\\\u043C\\\\u0435\\\\u043D\\\\u0442\\\\u0430 ${itemId} \\\\u0432 \\\\u0441\\\\u043F\\\\u0438\\\\u0441\\\\u043A\\\\u0435 ${listId}`);\\n}\\nonMount(() => {\\n  agUIStore.reset();\\n  agUIStore.addBotText(\\"\\\\u0414\\\\u043E\\\\u0431\\\\u0440\\\\u043E \\\\u043F\\\\u043E\\\\u0436\\\\u0430\\\\u043B\\\\u043E\\\\u0432\\\\u0430\\\\u0442\\\\u044C \\\\u0432 \\\\u0434\\\\u0435\\\\u043C\\\\u043E\\\\u043D\\\\u0441\\\\u0442\\\\u0440\\\\u0430\\\\u0446\\\\u0438\\\\u044E AG-UI! \\\\u041D\\\\u0430\\\\u043F\\\\u0438\\\\u0448\\\\u0438\\\\u0442\\\\u0435 \\\\u0447\\\\u0442\\\\u043E-\\\\u043D\\\\u0438\\\\u0431\\\\u0443\\\\u0434\\\\u044C, \\\\u0447\\\\u0442\\\\u043E\\\\u0431\\\\u044B \\\\u0443\\\\u0432\\\\u0438\\\\u0434\\\\u0435\\\\u0442\\\\u044C \\\\u0440\\\\u0430\\\\u0437\\\\u043B\\\\u0438\\\\u0447\\\\u043D\\\\u044B\\\\u0435 \\\\u0442\\\\u0438\\\\u043F\\\\u044B \\\\u0441\\\\u043E\\\\u043E\\\\u0431\\\\u0449\\\\u0435\\\\u043D\\\\u0438\\\\u0439.\\", true);\\n});\\nlet userInput = \\"\\";\\nfunction handleSubmit() {\\n  if (userInput.trim()) {\\n    sendUserMessage(userInput);\\n    userInput = \\"\\";\\n  }\\n}\\n<\/script>\\n\\n<svelte:head>\\n  <title>AG-UI Демонстрация</title>\\n</svelte:head>\\n\\n<div class=\\"demo-container\\">\\n  <header class=\\"demo-header\\">\\n    <h1>Демонстрация AG-UI в чате</h1>\\n    <p>Посмотрите, как работают различные типы сообщений AG-UI</p>\\n  </header>\\n  \\n  <div class=\\"chat-container\\">\\n    <div class=\\"messages-container\\">\\n      {#each messages as message (message.id)}\\n        {#if message.type === AgUIEventType.TEXT}\\n          <ChatMessage \\n            sender={message.sender} \\n            text={message.text} \\n            timestamp={message.timestamp}\\n            messageType={AgUIEventType.TEXT}\\n          />\\n        {:else if message.type === AgUIEventType.CARD}\\n          <ChatMessage \\n            sender={message.sender}\\n            timestamp={message.timestamp}\\n            messageType={AgUIEventType.CARD}\\n            card={message.card}\\n            on:cardAction={handleCardAction}\\n          />\\n        {:else if message.type === AgUIEventType.FORM}\\n          <ChatMessage \\n            sender={message.sender}\\n            timestamp={message.timestamp}\\n            messageType={AgUIEventType.FORM}\\n            formId={message.id}\\n            formTitle={message.title}\\n            formDescription={message.description}\\n            formFields={message.fields}\\n            on:formSubmit={handleFormSubmit}\\n            on:formCancel={handleFormCancel}\\n          />\\n        {:else if message.type === AgUIEventType.LIST}\\n          <ChatMessage \\n            sender={message.sender}\\n            timestamp={message.timestamp}\\n            messageType={AgUIEventType.LIST}\\n            listData={{\\n              id: message.id,\\n              title: message.title,\\n              items: message.items\\n            }}\\n            on:listItemSelect={handleListItemSelect}\\n            on:listAction={handleListAction}\\n          />\\n        {:else if message.type === AgUIEventType.STATUS}\\n          <ChatMessage \\n            sender={message.sender}\\n            timestamp={message.timestamp}\\n            messageType={AgUIEventType.STATUS}\\n            statusData={{\\n              status: message.status,\\n              message: message.message\\n            }}\\n          />\\n        {/if}\\n      {/each}\\n    </div>\\n    \\n    <div class=\\"input-container\\">\\n      <form on:submit|preventDefault={handleSubmit}>\\n        <input \\n          type=\\"text\\" \\n          bind:value={userInput} \\n          placeholder=\\"Напишите сообщение...\\" \\n          aria-label=\\"Сообщение\\"\\n        />\\n        <button type=\\"submit\\">Отправить</button>\\n      </form>\\n    </div>\\n  </div>\\n</div>\\n\\n<style>\\n  .demo-container {\\n    max-width: 900px;\\n    margin: 0 auto;\\n    padding: 2rem;\\n    font-family: system-ui, -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen, Ubuntu, Cantarell, \'Open Sans\', \'Helvetica Neue\', sans-serif;\\n  }\\n  \\n  .demo-header {\\n    text-align: center;\\n    margin-bottom: 2rem;\\n  }\\n  \\n  .demo-header h1 {\\n    font-size: 1.8rem;\\n    margin-bottom: 0.5rem;\\n    color: var(--text-primary);\\n  }\\n  \\n  .demo-header p {\\n    color: var(--text-secondary);\\n    font-size: 1rem;\\n  }\\n  \\n  .chat-container {\\n    display: flex;\\n    flex-direction: column;\\n    height: 70vh;\\n    border-radius: 12px;\\n    overflow: hidden;\\n    border: 1px solid var(--border-color);\\n    background-color: var(--bg-primary);\\n    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);\\n  }\\n  \\n  .messages-container {\\n    flex-grow: 1;\\n    overflow-y: auto;\\n    padding: 1.5rem;\\n    display: flex;\\n    flex-direction: column;\\n    gap: 1rem;\\n  }\\n  \\n  .input-container {\\n    padding: 1rem;\\n    border-top: 1px solid var(--border-color);\\n    background-color: var(--bg-secondary);\\n  }\\n  \\n  .input-container form {\\n    display: flex;\\n    gap: 0.5rem;\\n  }\\n  \\n  .input-container input {\\n    flex-grow: 1;\\n    padding: 0.75rem 1rem;\\n    border-radius: 8px;\\n    border: 1px solid var(--border-color);\\n    font-size: 0.95rem;\\n    background-color: var(--bg-primary);\\n    color: var(--text-primary);\\n  }\\n  \\n  .input-container input:focus {\\n    outline: none;\\n    border-color: var(--accent-primary);\\n    box-shadow: 0 0 0 2px rgba(var(--accent-primary-rgb), 0.1);\\n  }\\n  \\n  .input-container button {\\n    padding: 0.75rem 1.25rem;\\n    border-radius: 8px;\\n    border: none;\\n    background-color: var(--accent-primary);\\n    color: var(--button-text);\\n    font-weight: 500;\\n    cursor: pointer;\\n    transition: background-color 0.2s ease, transform 0.2s ease;\\n  }\\n  \\n  .input-container button:hover {\\n    background-color: var(--button-hover-bg);\\n    transform: translateY(-1px);\\n  }\\n  \\n  .input-container button:active {\\n    transform: translateY(0);\\n  }\\n  \\n  /* Скроллбар */\\n  .messages-container::-webkit-scrollbar {\\n    width: 6px;\\n  }\\n  \\n  .messages-container::-webkit-scrollbar-track {\\n    background: transparent;\\n  }\\n  \\n  .messages-container::-webkit-scrollbar-thumb {\\n    background-color: var(--border-color);\\n    border-radius: 6px;\\n  }\\n  \\n  .messages-container::-webkit-scrollbar-thumb:hover {\\n    background-color: var(--text-muted);\\n  }\\n</style>\\n"],"names":[],"mappings":"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"}'
};
const Page = create_ssr_component(($$result, $$props, $$bindings, slots) => {
  let $agUIStore, $$unsubscribe_agUIStore;
  $$unsubscribe_agUIStore = subscribe(agUIStore, (value) => $agUIStore = value);
  let messages = [];
  let userInput = "";
  $$result.css.add(css);
  messages = $agUIStore;
  $$unsubscribe_agUIStore();
  return `${$$result.head += `<!-- HEAD_svelte-37n4e2_START -->${$$result.title = `<title>AG-UI Демонстрация</title>`, ""}<!-- HEAD_svelte-37n4e2_END -->`, ""} <div class="demo-container svelte-y9w2zs"><header class="demo-header svelte-y9w2zs" data-svelte-h="svelte-1karr7q"><h1 class="svelte-y9w2zs">Демонстрация AG-UI в чате</h1> <p class="svelte-y9w2zs">Посмотрите, как работают различные типы сообщений AG-UI</p></header> <div class="chat-container svelte-y9w2zs"><div class="messages-container svelte-y9w2zs">${each(messages, (message) => {
    return `${message.type === AgUIEventType.TEXT ? `${validate_component(ChatMessage, "ChatMessage").$$render(
      $$result,
      {
        sender: message.sender,
        text: message.text,
        timestamp: message.timestamp,
        messageType: AgUIEventType.TEXT
      },
      {},
      {}
    )}` : `${message.type === AgUIEventType.CARD ? `${validate_component(ChatMessage, "ChatMessage").$$render(
      $$result,
      {
        sender: message.sender,
        timestamp: message.timestamp,
        messageType: AgUIEventType.CARD,
        card: message.card
      },
      {},
      {}
    )}` : `${message.type === AgUIEventType.FORM ? `${validate_component(ChatMessage, "ChatMessage").$$render(
      $$result,
      {
        sender: message.sender,
        timestamp: message.timestamp,
        messageType: AgUIEventType.FORM,
        formId: message.id,
        formTitle: message.title,
        formDescription: message.description,
        formFields: message.fields
      },
      {},
      {}
    )}` : `${message.type === AgUIEventType.LIST ? `${validate_component(ChatMessage, "ChatMessage").$$render(
      $$result,
      {
        sender: message.sender,
        timestamp: message.timestamp,
        messageType: AgUIEventType.LIST,
        listData: {
          id: message.id,
          title: message.title,
          items: message.items
        }
      },
      {},
      {}
    )}` : `${message.type === AgUIEventType.STATUS ? `${validate_component(ChatMessage, "ChatMessage").$$render(
      $$result,
      {
        sender: message.sender,
        timestamp: message.timestamp,
        messageType: AgUIEventType.STATUS,
        statusData: {
          status: message.status,
          message: message.message
        }
      },
      {},
      {}
    )}` : ``}`}`}`}`}`;
  })}</div> <div class="input-container svelte-y9w2zs"><form class="svelte-y9w2zs"><input type="text" placeholder="Напишите сообщение..." aria-label="Сообщение" class="svelte-y9w2zs"${add_attribute("value", userInput, 0)}> <button type="submit" class="svelte-y9w2zs" data-svelte-h="svelte-1hi4heg">Отправить</button></form></div></div> </div>`;
});
export {
  Page as default
};
