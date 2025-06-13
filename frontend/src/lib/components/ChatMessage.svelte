<script lang="ts">
  import { createEventDispatcher } from 'svelte';
  import { AgUIEventType } from '../ag-ui/types';
  import type { AgUIEvent, AgUICard, AgUIFormField, AgUIListEvent } from '../ag-ui/types';
  import CardMessage from './ui/CardMessage.svelte';
  import FormMessage from './ui/FormMessage.svelte';
  import ListMessage from './ui/ListMessage.svelte';
  import ProductCard from './ui/ProductCard.svelte';

  export let sender: 'user' | 'bot' = 'bot';
  export let text: string = '';
  export let timestamp: string | null = null;
  export let messageType: AgUIEventType = AgUIEventType.TEXT;
  export let card: AgUICard | null = null;
  export let formFields: AgUIFormField[] | null = null;
  export let formId: string | null = null;
  export let formTitle: string | null = null;
  export let formDescription: string | null = null;
  export let listData: AgUIListEvent | null = null;
  export let toolCall: { tool: string, args: any } | null = null;
  export let toolResult: { tool: string, result: any } | null = null;
  export let statusData: { status: 'info' | 'success' | 'warning' | 'error', message: string } | null = null;
  export let customComponentData: { componentName: string, props: Record<string, any> } | null = null;

  const dispatch = createEventDispatcher();

  const timeForm = new Intl.DateTimeFormat('default', {
    hour: 'numeric',
    minute: 'numeric',
  });

  let formattedTimestamp = timestamp ? timeForm.format(new Date(timestamp)) : '';

  // Обработчик отправки формы
  function handleFormSubmit(event: CustomEvent) {
    dispatch('formSubmit', event.detail);
  }

  // Обработчик отмены формы
  function handleFormCancel(event: CustomEvent) {
    dispatch('formCancel', event.detail);
  }

  // Обработчик клика по элементу списка
  function handleListItemSelect(event: CustomEvent) {
    dispatch('listItemSelect', event.detail);
  }

  // Обработчик действия в списке
  function handleListAction(event: CustomEvent) {
    dispatch('listAction', event.detail);
  }

  // Обработчик действия карточки
  function handleCardAction(event: CustomEvent) {
    dispatch('cardAction', event.detail);
  }
</script>

<div class="chat-message" class:user={sender === 'user'} class:bot={sender === 'bot'}>
  {#if messageType === AgUIEventType.TEXT}
    <div class="message-bubble">
      <div class="message-text">{@html text}</div>
      {#if formattedTimestamp}
        <div class="message-timestamp">{formattedTimestamp}</div>
      {/if}
    </div>
  {:else if messageType === AgUIEventType.CARD && card}
    <div class="card-container">
      <CardMessage {card} on:action={handleCardAction} />
      {#if formattedTimestamp}
        <div class="message-timestamp">{formattedTimestamp}</div>
      {/if}
    </div>
  {:else if messageType === AgUIEventType.FORM && formFields && formId}
    <div class="form-container">
      <FormMessage 
        id={formId} 
        title={formTitle || ''} 
        description={formDescription || ''} 
        fields={formFields}
        on:submit={handleFormSubmit}
        on:cancel={handleFormCancel}
      />
      {#if formattedTimestamp}
        <div class="message-timestamp">{formattedTimestamp}</div>
      {/if}
    </div>
  {:else if messageType === AgUIEventType.LIST && listData}
    <div class="list-container">
      <ListMessage 
        list={listData} 
        on:itemSelect={handleListItemSelect} 
        on:action={handleListAction} 
      />
      {#if formattedTimestamp}
        <div class="message-timestamp">{formattedTimestamp}</div>
      {/if}
    </div>
  {:else if messageType === AgUIEventType.TOOL_CALL && toolCall}
    <div class="message-bubble tool-call">
      <div class="tool-call-header">⚙️ Вызов инструмента: {toolCall.tool}</div>
      <div class="tool-call-args">
        <pre>{JSON.stringify(toolCall.args, null, 2)}</pre>
      </div>
      {#if formattedTimestamp}
        <div class="message-timestamp">{formattedTimestamp}</div>
      {/if}
    </div>
  {:else if messageType === AgUIEventType.TOOL_RESULT && toolResult}
    <div class="message-bubble tool-result">
      <div class="tool-result-header">✅ Результат: {toolResult.tool}</div>
      <div class="tool-result-content">
        <pre>{JSON.stringify(toolResult.result, null, 2)}</pre>
      </div>
      {#if formattedTimestamp}
        <div class="message-timestamp">{formattedTimestamp}</div>
      {/if}
    </div>
  {:else if messageType === AgUIEventType.STATUS && statusData}
    <div class="message-bubble status" class:status-info={statusData.status === 'info'} class:status-success={statusData.status === 'success'} class:status-warning={statusData.status === 'warning'} class:status-error={statusData.status === 'error'}>
      <div class="status-icon">
        {#if statusData.status === 'info'}
          ℹ️
        {:else if statusData.status === 'success'}
          ✅
        {:else if statusData.status === 'warning'}
          ⚠️
        {:else if statusData.status === 'error'}
          ❌
        {/if}
      </div>
      <div class="status-message">{statusData.message}</div>
      {#if formattedTimestamp}
        <div class="message-timestamp">{formattedTimestamp}</div>
      {/if}
    </div>
  {:else if messageType === AgUIEventType.CUSTOM && customComponentData}
    <div class="custom-component-container">
      {#if customComponentData.componentName === 'ProductCard' && customComponentData.props && (typeof customComponentData.props.productId === 'number' || (customComponentData.props.title && customComponentData.props.price))}
        <div class="product-card-wrapper">
          <ProductCard 
              productId={customComponentData.props.productId}
              title={customComponentData.props.title}
              price={customComponentData.props.price}
              image={customComponentData.props.image}
              url={customComponentData.props.url}
              clickable={false}
            />
          {#if formattedTimestamp}
            <div class="message-timestamp">{formattedTimestamp}</div>
          {/if}
        </div>
      {:else}
        <div class="message-bubble error">
          <div class="message-text">
            Received custom component: {customComponentData.componentName || 'Unknown'} (Unable to render or missing valid productId)
          </div>
          {#if formattedTimestamp}
            <div class="message-timestamp">{formattedTimestamp}</div>
          {/if}
        </div>
      {/if}
    </div>
  {:else}
    <!-- Fallback for unsupported message types -->
    <div class="message-bubble">
      <div class="message-text">{@html text}</div>
      {#if formattedTimestamp}
        <div class="message-timestamp">{formattedTimestamp}</div>
      {/if}
    </div>
  {/if}
</div>

<style>
  .chat-message {
    display: flex;
    margin-bottom: 0.5rem;
    max-width: 85%;
  }

  .chat-message.user {
    margin-left: auto;
    justify-content: flex-end;
  }

  .chat-message.bot {
    margin-right: auto;
    justify-content: flex-start;
  }

  /* Avatar styles removed */

  .message-bubble {
    padding: 0.75rem 1rem;
    border-radius: 12px;
    line-height: 1.4;
    word-wrap: break-word;
    box-shadow: none;
    position: relative;
    transition: all 0.2s ease;
  }
  
  /* Subtle hover effect */
  .message-bubble:hover {
    transform: translateY(-1px);
  }

  .chat-message.user .message-bubble {
    background-color: var(--accent-primary);
    color: var(--button-text);
    border-radius: 18px;
    position: relative;
  }
  
  /* Gemini-style subtle glow effect for user messages */
  .chat-message.user .message-bubble::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 18px;
    box-shadow: 0 0 8px rgba(var(--accent-primary-rgb), 0.2);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
  }
  
  .chat-message.user .message-bubble:hover::after {
    opacity: 1;
  }

  .chat-message.bot .message-bubble {
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    border-radius: 18px;
    position: relative;
  }
  
  /* Subtle border effect for bot messages on hover */
  .chat-message.bot .message-bubble::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 18px;
    padding: 1px;
    background: linear-gradient(135deg, transparent, rgba(var(--accent-primary-rgb), 0.2), transparent);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
  }
  
  .chat-message.bot .message-bubble:hover::before {
    opacity: 1;
  }

  .message-text {
    white-space: pre-wrap; /* Preserve line breaks and spaces */
  }
  
  .message-text :global(p:last-child) {
      margin-bottom: 0;
  }

  .message-timestamp {
    font-size: 0.65rem;
    margin-top: 0.3rem;
    text-align: right;
    opacity: 0.5; /* Even more muted by default */
    transition: opacity 0.2s ease;
  }
  
  /* Show timestamp more clearly on hover */
  .message-bubble:hover .message-timestamp,
  .card-container:hover .message-timestamp,
  .form-container:hover .message-timestamp,
  .list-container:hover .message-timestamp {
    opacity: 0.8;
  }
  
  /* Card container styles */
  .card-container,
  .form-container,
  .list-container {
    max-width: 100%;
    position: relative;
  }
  
  /* Tool call styles */
  .tool-call {
    background-color: rgba(var(--accent-primary-rgb), 0.1);
    border: 1px solid rgba(var(--accent-primary-rgb), 0.3);
  }
  
  .tool-call-header,
  .tool-result-header {
    font-weight: 500;
    margin-bottom: 0.5rem;
  }
  
  .tool-call-args pre,
  .tool-result-content pre {
    font-family: monospace;
    font-size: 0.85rem;
    background-color: rgba(0, 0, 0, 0.05);
    padding: 0.5rem;
    border-radius: 4px;
    overflow-x: auto;
    margin: 0;
  }
  
  /* Status message styles */
  .status {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
  }
  
  .status-icon {
    font-size: 1.1rem;
  }
  
  .status-info {
    background-color: rgba(59, 130, 246, 0.1); /* Light blue */
    border: 1px solid rgba(59, 130, 246, 0.3);
  }
  
  .status-success {
    background-color: rgba(34, 197, 94, 0.1); /* Light green */
    border: 1px solid rgba(34, 197, 94, 0.3);
  }
  
  .status-warning {
    background-color: rgba(245, 158, 11, 0.1); /* Light amber */
    border: 1px solid rgba(245, 158, 11, 0.3);
  }
  
  .status-error {
    background-color: rgba(239, 68, 68, 0.1); /* Light red */
    border: 1px solid rgba(239, 68, 68, 0.3);
  }

  .chat-message.user .message-timestamp {
    color: rgba(var(--button-text-rgb), 0.6); /* Use defined RGB and new opacity */
  }

  .chat-message.bot .message-timestamp {
    color: var(--text-muted);
  }
</style>
