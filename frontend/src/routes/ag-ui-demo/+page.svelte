<script lang="ts">
  import { onMount } from 'svelte';
  import ChatMessage from '$lib/components/ChatMessage.svelte';
  import { agUIStore } from '$lib/ag-ui/store';
  import { AgUIEventType } from '$lib/ag-ui/types';
  import type { AgUIFormField } from '$lib/ag-ui/types';
  
  let messages: any[] = [];
  
  // Подписываемся на события из хранилища AG-UI
  $: messages = $agUIStore;
  
  // Функция для отправки текстового сообщения пользователя
  function sendUserMessage(text: string) {
    agUIStore.sendUserText(text);
    
    // Демонстрационный ответ: после отправки пользователем сообщения
    // мы добавим несколько различных типов ответов бота
    setTimeout(() => {
      respondWithDifferentMessageTypes();
    }, 1000);
  }
  
  // Отправляем демонстрационные сообщения различных типов
  function respondWithDifferentMessageTypes() {
    // Сначала просто текстовый ответ
    agUIStore.addBotText("Привет! Я покажу различные типы сообщений AG-UI, которые поддерживает наш чат.", true);
    
    setTimeout(() => {
      // Добавляем карточку товара
      agUIStore.addCard({
        title: "Смартфон Pixel 7 Pro",
        description: "Флагманский смартфон Google с продвинутой камерой и чистым Android.",
        image: "https://lh3.googleusercontent.com/spp/AP8QK7f2WV-08q78UtHkQrz7-cRX93KQcCQlZiCkK9j4_qJlUVPrh0XS5ICE1Jxb8dVixHE6ulwLuR3_ISGmyQogpxxO2AiIwOUZHN0rgPCGTJ5sP91GAhQJfDx_9Z4XCq_mj0iYoC2-LLbXzXXkj7ZXnkHHRdJBBdY=w100-h243-rw-no",
        fields: [
          { name: "Цена", value: "79 990 ₽" },
          { name: "Память", value: "128 ГБ", inline: true },
          { name: "Цвет", value: "Чёрный", inline: true }
        ],
        actions: [
          { id: "buy", label: "Купить", style: "primary" },
          { id: "add_to_cart", label: "В корзину", style: "secondary" },
          { id: "compare", label: "Сравнить", style: "info" }
        ],
        footer: "Бесплатная доставка"
      });
      
      // Добавляем список
      setTimeout(() => {
        agUIStore.addList({
          title: "Популярные товары",
          items: [
            {
              id: "item1",
              title: "AirPods Pro",
              description: "Беспроводные наушники с шумоподавлением",
              image: "https://lh3.googleusercontent.com/spp/AP8QK7dX9z6PhmRE0HKKoiuKKMO-0qYrd31UtxeAQsLe7HdvDQQn7UxMrhJlE1YDvCWj2yQ8M61XYJ1gPD7gK-vdQWxpL9Mmv-wj9Pj4tZvA5KTfkNJDLfbHHYnHm1WR7QwHxOh0eL4GNrE8QiOe6cTd7nXzuqeH=w100-h100-rw-no",
              actions: [
                { id: "view_item1", label: "Посмотреть", icon: "👁️" }
              ]
            },
            {
              id: "item2",
              title: "MacBook Air M2",
              description: "Тонкий и лёгкий ноутбук с чипом M2",
              image: "https://lh3.googleusercontent.com/spp/AP8QK7fzxaX9JoQBPF1IhRoCRgUe44bianVXxay988QA4AzbBVYgJ20Mpdg4bZGFz-Qi9GHsF8TzkhxBmaHHH4DKgGEwqAUu6oOL5cfdY2RHsLzKNDLhZ1WbZFTYRWkEoxOITWNXJLKsXcWp-9zcqK-pZWGdRCUb=w100-h67-rw-no",
              actions: [
                { id: "view_item2", label: "Посмотреть", icon: "👁️" }
              ]
            },
            {
              id: "item3",
              title: "iPad Pro",
              description: "Планшет с процессором M2 и дисплеем Liquid Retina XDR",
              image: "https://lh3.googleusercontent.com/spp/AP8QK7eipA0CZTXYA1Hv_Kamy6bNjCt6n3fMQHawrYO6cV5bOXTH4byIKJCCCUGbJHzKkXtSFvokZ38i8WQ90OvNdMUDOdtztfWRZyM1wJUz4MSGOq8zomRVn2e8iFKnC5KO-xWtWkRFYSjCq56H80TqRNHKJaQ=w100-h76-rw-no",
              actions: [
                { id: "view_item3", label: "Посмотреть", icon: "👁️" }
              ]
            }
          ]
        });
        
        // Добавляем форму
        setTimeout(() => {
          const formFields: AgUIFormField[] = [
            {
              id: "name",
              label: "Имя",
              type: "text",
              placeholder: "Введите ваше имя",
              required: true
            },
            {
              id: "email",
              label: "Email",
              type: "email",
              placeholder: "example@email.com",
              required: true,
              validation: {
                pattern: "^[\\w-\\.]+@([\\w-]+\\.)+[\\w-]{2,4}$"
              }
            },
            {
              id: "phone",
              label: "Телефон",
              type: "text",
              placeholder: "+7 (___) ___-__-__"
            },
            {
              id: "product",
              label: "Интересующий товар",
              type: "select",
              options: [
                { label: "Смартфон", value: "smartphone" },
                { label: "Ноутбук", value: "laptop" },
                { label: "Планшет", value: "tablet" },
                { label: "Наушники", value: "headphones" }
              ]
            },
            {
              id: "message",
              label: "Сообщение",
              type: "textarea",
              placeholder: "Введите ваше сообщение"
            },
            {
              id: "agree",
              label: "Согласие с условиями",
              type: "checkbox",
              placeholder: "Я согласен с условиями обработки персональных данных",
              required: true
            }
          ];
          
          agUIStore.addForm({
            title: "Форма обратной связи",
            description: "Заполните форму, и мы свяжемся с вами в ближайшее время",
            fields: formFields,
            submitLabel: "Отправить",
            cancelLabel: "Отмена"
          });
          
          // Добавляем статус
          setTimeout(() => {
            agUIStore.addStatus("info", "Демонстрация типов сообщений AG-UI завершена!");
          }, 1000);
          
        }, 1000);
      }, 1000);
    }, 1000);
  }
  
  // Обработчики событий компонентов AG-UI
  function handleFormSubmit(event: CustomEvent) {
    const { formId, values } = event.detail;
    agUIStore.addBotText(`Форма отправлена! ID: ${formId}, данные: ${JSON.stringify(values)}`);
  }
  
  function handleFormCancel() {
    agUIStore.addBotText("Заполнение формы отменено");
  }
  
  function handleCardAction(event: CustomEvent) {
    const { actionId, cardId } = event.detail;
    agUIStore.addBotText(`Действие по карточке: ${actionId} для карточки ${cardId}`);
  }
  
  function handleListItemSelect(event: CustomEvent) {
    const { listId, itemId } = event.detail;
    agUIStore.addBotText(`Выбран элемент ${itemId} из списка ${listId}`);
  }
  
  function handleListAction(event: CustomEvent) {
    const { listId, itemId, actionId } = event.detail;
    agUIStore.addBotText(`Действие ${actionId} для элемента ${itemId} в списке ${listId}`);
  }
  
  // Инициализация демонстрации при загрузке страницы
  onMount(() => {
    agUIStore.reset();
    agUIStore.addBotText("Добро пожаловать в демонстрацию AG-UI! Напишите что-нибудь, чтобы увидеть различные типы сообщений.", true);
  });
  
  let userInput = "";
  
  function handleSubmit() {
    if (userInput.trim()) {
      sendUserMessage(userInput);
      userInput = "";
    }
  }
</script>

<svelte:head>
  <title>AG-UI Демонстрация</title>
</svelte:head>

<div class="demo-container">
  <header class="demo-header">
    <h1>Демонстрация AG-UI в чате</h1>
    <p>Посмотрите, как работают различные типы сообщений AG-UI</p>
  </header>
  
  <div class="chat-container">
    <div class="messages-container">
      {#each messages as message (message.id)}
        {#if message.type === AgUIEventType.TEXT}
          <ChatMessage 
            sender={message.sender} 
            text={message.text} 
            timestamp={message.timestamp}
            messageType={AgUIEventType.TEXT}
          />
        {:else if message.type === AgUIEventType.CARD}
          <ChatMessage 
            sender={message.sender}
            timestamp={message.timestamp}
            messageType={AgUIEventType.CARD}
            card={message.card}
            on:cardAction={handleCardAction}
          />
        {:else if message.type === AgUIEventType.FORM}
          <ChatMessage 
            sender={message.sender}
            timestamp={message.timestamp}
            messageType={AgUIEventType.FORM}
            formId={message.id}
            formTitle={message.title}
            formDescription={message.description}
            formFields={message.fields}
            on:formSubmit={handleFormSubmit}
            on:formCancel={handleFormCancel}
          />
        {:else if message.type === AgUIEventType.LIST}
          <ChatMessage 
            sender={message.sender}
            timestamp={message.timestamp}
            messageType={AgUIEventType.LIST}
            listData={{
              id: message.id,
              title: message.title,
              items: message.items
            }}
            on:listItemSelect={handleListItemSelect}
            on:listAction={handleListAction}
          />
        {:else if message.type === AgUIEventType.STATUS}
          <ChatMessage 
            sender={message.sender}
            timestamp={message.timestamp}
            messageType={AgUIEventType.STATUS}
            statusData={{
              status: message.status,
              message: message.message
            }}
          />
        {/if}
      {/each}
    </div>
    
    <div class="input-container">
      <form on:submit|preventDefault={handleSubmit}>
        <input 
          type="text" 
          bind:value={userInput} 
          placeholder="Напишите сообщение..." 
          aria-label="Сообщение"
        />
        <button type="submit">Отправить</button>
      </form>
    </div>
  </div>
</div>

<style>
  .demo-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  }
  
  .demo-header {
    text-align: center;
    margin-bottom: 2rem;
  }
  
  .demo-header h1 {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
  }
  
  .demo-header p {
    color: var(--text-secondary);
    font-size: 1rem;
  }
  
  .chat-container {
    display: flex;
    flex-direction: column;
    height: 70vh;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--border-color);
    background-color: var(--bg-primary);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  }
  
  .messages-container {
    flex-grow: 1;
    overflow-y: auto;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  
  .input-container {
    padding: 1rem;
    border-top: 1px solid var(--border-color);
    background-color: var(--bg-secondary);
  }
  
  .input-container form {
    display: flex;
    gap: 0.5rem;
  }
  
  .input-container input {
    flex-grow: 1;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    border: 1px solid var(--border-color);
    font-size: 0.95rem;
    background-color: var(--bg-primary);
    color: var(--text-primary);
  }
  
  .input-container input:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 2px rgba(var(--accent-primary-rgb), 0.1);
  }
  
  .input-container button {
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    border: none;
    background-color: var(--accent-primary);
    color: var(--button-text);
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease;
  }
  
  .input-container button:hover {
    background-color: var(--button-hover-bg);
    transform: translateY(-1px);
  }
  
  .input-container button:active {
    transform: translateY(0);
  }
  
  /* Скроллбар */
  .messages-container::-webkit-scrollbar {
    width: 6px;
  }
  
  .messages-container::-webkit-scrollbar-track {
    background: transparent;
  }
  
  .messages-container::-webkit-scrollbar-thumb {
    background-color: var(--border-color);
    border-radius: 6px;
  }
  
  .messages-container::-webkit-scrollbar-thumb:hover {
    background-color: var(--text-muted);
  }
</style>
