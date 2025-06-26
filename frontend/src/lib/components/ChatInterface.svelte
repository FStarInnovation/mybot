<script lang="ts">
  import ChatMessage from './ChatMessage.svelte';
  import ChatInput from './ChatInput.svelte';
  import { onMount, tick } from 'svelte';
  import { fade, fly } from 'svelte/transition';
  import type { AgUIEventType, AgUICard, AgUIFormField, AgUIListEvent } from '../ag-ui/types'; // Expanded imports
  import { AgUIEventType as AgUIEventTypesEnum } from '../ag-ui/types'; // For using enum values

  // Определяем свой тип Message, так как AgUIEvent не включает нужные нам поля
  type Message = {
    id: string;
    sender: 'user' | 'bot';
    timestamp: string;
    type: AgUIEventTypesEnum;
    text?: string; 
    card?: AgUICard;
    formFields?: AgUIFormField[];
    formId?: string;
    formTitle?: string;
    formDescription?: string;
    listData?: AgUIListEvent;
    toolCall?: { tool: string, args: any };
    toolResult?: { tool: string, result: any };
    statusData?: { status: 'info' | 'success' | 'warning' | 'error', message: string };
    customComponentData?: { componentName: string, props: Record<string, any> };
  };

  let messages: Message[] = [];

  const API_BASE = import.meta.env.PUBLIC_API_BASE ?? '';

  let chatContainer: HTMLElement;
  let isTyping = false;

  async function scrollToBottom() {
    await tick();
    if (chatContainer) {
      chatContainer.scrollTop = chatContainer.scrollHeight;
    }
  }

  onMount(async () => {
    try {
      const res = await fetch(`${API_BASE}/api/chat/history`);
      if (res.ok) {
        const data = await res.json();
        messages = data.history?.map((m: any, idx: number) => ({
          id: idx.toString(),
          sender: m.role === 'assistant' ? 'bot' : 'user',
          type: AgUIEventTypesEnum.TEXT,
          text: m.content,
          timestamp: m.ts ?? new Date().toISOString()
        })) ?? [];
        await scrollToBottom();
      }
    } catch (e) {
      console.error('Failed to load history', e);
    }
  });

  async function handleSendMessage(event: CustomEvent<{ text: string }>) {
    const userMsg: Message = {
      id: Date.now().toString(),
      sender: 'user',
      type: AgUIEventTypesEnum.TEXT,
      text: event.detail.text,
      timestamp: new Date().toISOString(),
    };
    messages = [...messages, userMsg];
    await scrollToBottom();

    isTyping = true;
    try {
      const res = await fetch(`${API_BASE}/api/chat/send`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: event.detail.text })
      });

      if (res.ok) {
        const data = await res.json();
        const replies = data.messages ?? [];
        messages = [...messages, ...replies.map((r: any, idx: number) => ({
          id: (Date.now() + idx + 1).toString(),
          sender: 'bot',
          type: AgUIEventTypesEnum.TEXT,
          text: r.content ?? r,
          timestamp: new Date().toISOString()
        }))];
      } else {
        messages = [...messages, { id: Date.now().toString(), sender: 'bot', type: AgUIEventTypesEnum.TEXT, text: 'Ошибка сервера', timestamp: new Date().toISOString() }];
      }
    } catch (err) {
      console.error(err);
      messages = [...messages, { id: Date.now().toString(), sender: 'bot', type: AgUIEventTypesEnum.TEXT, text: 'Не удалось связаться с сервером', timestamp: new Date().toISOString() }];
    } finally {
      isTyping = false;
      await scrollToBottom();
    }
  }
  
  // Функция для показа карточки ибупрофена
  function testProductCard() {
    // Карточка с фиксированным ID товара
    // Здесь используем ID=23, который должен быть ибупрофеном
    const productCardMessage: Message = {
      id: Date.now().toString(),
      sender: 'bot',
      type: AgUIEventTypesEnum.CUSTOM,
      timestamp: new Date().toISOString(),
      customComponentData: {
        componentName: 'ProductCard',
        props: { 
          productId: 23, // Используем фиксированный ID ибупрофена
          showPricePerTablet: true  // Показывать цену за таблетку
        }
      }
    };
    
    messages = [...messages, productCardMessage];
    scrollToBottom();
    console.log('Показываю карточку товара с ID:', 23);
  }
</script>

<div class="chat-interface">
  <div class="chat-header">
    <div class="header-content">
      <h2>MyBot Chat</h2>
      <div class="status-indicator" title="Online">
        <span class="status-dot"></span>
        <span class="status-text">Online</span>
      </div>
    </div>
  </div>
  <div class="chat-messages-container" bind:this={chatContainer}>
    {#each messages as message, i (message.id)}
      <div 
        in:fly="{{ y: 20, duration: 300, delay: 50 * (messages.length - i - 1) }}" 
        out:fade="{{ duration: 200 }}"
        class="message-wrapper"
      >
        <ChatMessage 
          sender={message.sender} 
          messageType={message.type}
          text={message.text || ''}
          timestamp={message.timestamp}
          card={message.card}
          formFields={message.formFields}
          formId={message.formId}
          formTitle={message.formTitle}
          formDescription={message.formDescription}
          listData={message.listData}
          toolCall={message.toolCall}
          toolResult={message.toolResult}
          statusData={message.statusData}
          customComponentData={message.customComponentData}
        />
      </div>
    {/each}
    {#if isTyping}
      <div class="typing-indicator" in:fade="{{ duration: 200 }}">
        <ChatMessage sender="bot" text="<span class='dot'>.</span><span class='dot'>.</span><span class='dot'>.</span>" />
      </div>
    {/if}
  </div>
  <div class="chat-input-area">
    <div class="test-buttons">
      <button class="test-button" on:click={testProductCard}>mejor precio ibuprofeno {Date.now()}</button>
    </div>
    <ChatInput on:sendMessage={handleSendMessage} />
  </div>
</div>

<style>
  .test-buttons {
    display: flex;
    justify-content: center;
    margin-bottom: 0.5rem;
  }
  
  .test-button {
    background-color: var(--accent-secondary);
    color: var(--button-text);
    border: none;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
    cursor: pointer;
    transition: background-color 0.2s ease;
  }
  
  .test-button:hover {
    background-color: var(--accent-primary);
  }
  .chat-interface {
    display: flex;
    flex-direction: column;
    height: 100%; /* Fill available height */
    max-height: 100vh; /* Ensure it doesn't exceed viewport height */
    background-color: var(--bg-primary);
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    overflow: hidden; /* Important to contain children */
    position: relative;
  }
  
  /* Subtle border effect */
  .chat-interface::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 16px;
    padding: 1px; /* Border width */
    background: linear-gradient(
      135deg, 
      transparent 0%, 
      rgba(var(--accent-primary-rgb), 0.1) 50%,
      transparent 100%
    );
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    pointer-events: none;
    opacity: 0.3;
  }

  .chat-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    text-align: left;
  }

  .header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .chat-header h2 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    position: relative;
  }
  
  .status-indicator {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.75rem;
    color: var(--text-secondary);
  }
  
  .status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #4CAF50; /* Green for online */
    position: relative;
  }
  
  /* Pulsating effect for status dot - keeping this as requested */
  .status-dot::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 50%;
    background-color: #4CAF50;
    opacity: 0.4;
    animation: pulse 2s ease-in-out infinite;
    transform-origin: center;
  }
  
  @keyframes pulse {
    0% { transform: scale(1); opacity: 0.4; }
    50% { transform: scale(2.5); opacity: 0; }
    100% { transform: scale(1); opacity: 0.4; }
  }

  .chat-messages-container {
    flex-grow: 1;
    padding: 1.5rem;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    scroll-behavior: smooth; /* Smooth scrolling for better UX */
    background-color: var(--bg-primary);
  }
  
  .message-wrapper {
    width: 100%;
    will-change: transform, opacity; /* Optimize for animations */
  }

  /* Styling for the typing indicator dots - Gemini style */
  .typing-indicator :global(.dot) {
    display: inline-block;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: var(--accent-primary);
    margin: 0 2px;
    animation: typing-blink 1.4s infinite both;
    position: relative;
  }
  
  .typing-indicator :global(.dot::after) {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 50%;
    background-color: var(--accent-primary);
    filter: blur(2px);
    opacity: 0.3;
    animation: typing-glow 1.4s infinite both;
  }
  
  .typing-indicator :global(.dot:nth-child(2)) {
    animation-delay: 0.2s;
  }
  .typing-indicator :global(.dot:nth-child(2)::after) {
    animation-delay: 0.2s;
  }
  
  .typing-indicator :global(.dot:nth-child(3)) {
    animation-delay: 0.4s;
  }
  .typing-indicator :global(.dot:nth-child(3)::after) {
    animation-delay: 0.4s;
  }

  @keyframes typing-blink {
    0% { opacity: 0.4; transform: scale(0.8); }
    20% { opacity: 1; transform: scale(1); }
    100% { opacity: 0.4; transform: scale(0.8); }
  }
  
  @keyframes typing-glow {
    0% { opacity: 0.2; transform: scale(1); }
    20% { opacity: 0.6; transform: scale(1.5); }
    100% { opacity: 0.2; transform: scale(1); }
  }

  .chat-input-area {
    /* ChatInput component already has border-top */
  }
</style>
