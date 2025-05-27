<script lang="ts">
  import { createEventDispatcher } from 'svelte';

  let messageText: string = '';
  const dispatch = createEventDispatcher();

  function handleSubmit() {
    if (messageText.trim() === '') return;
    dispatch('sendMessage', { text: messageText.trim() });
    messageText = ''; // Clear input after sending
  }

  function handleKeyPress(event: KeyboardEvent) {
    if (event.key === 'Enter' && !event.shiftKey) {
      event.preventDefault(); // Prevent new line on Enter
      handleSubmit();
    }
  }
</script>

<form class="chat-input-form" on:submit|preventDefault={handleSubmit}>
  <textarea
    bind:value={messageText}
    on:keypress={handleKeyPress}
    placeholder="Type your message... (Shift+Enter for new line)"
    rows="1"
    class="chat-textarea"
  ></textarea>
  <button type="submit" class="send-button" aria-label="Send message" title="Send message">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
      <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
    </svg>
  </button>
</form>

<style>
  .chat-input-form {
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem 1.25rem;
    gap: 0.75rem;
    background-color: var(--bg-primary);
    position: relative;
    z-index: 1;
    border-top: 1px solid rgba(0,0,0,0.03);
  }

  .chat-textarea {
    flex-grow: 1;
    padding: 0.75rem 1rem;
    border-radius: 20px;
    border: 1px solid rgba(0,0,0,0.08);
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    resize: none;
    font-family: inherit;
    font-size: 0.95rem;
    line-height: 1.4;
    max-height: 120px;
    overflow-y: auto;
    transition: all 0.3s ease;
    position: relative;
  }

  .chat-textarea:focus {
    outline: none;
    border-color: rgba(var(--accent-primary-rgb), 0.3);
    background-color: var(--bg-secondary);
  }

  .send-button {
    background-color: var(--accent-primary);
    color: var(--button-text);
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    padding: 0;
    position: relative;
  }

  .send-button:hover,
  .send-button:focus {
    outline: none;
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(var(--accent-primary-rgb), 0.3);
  }
  
  .send-button:active {
    transform: scale(0.95);
  }

  .send-button svg {
    pointer-events: none; /* Ensure SVG doesn't interfere with button events */
    position: relative;
    z-index: 2;
    transition: transform 0.2s ease;
    width: 18px;
    height: 18px;
  }
  
  .send-button:hover svg {
    transform: translateX(1px); /* Subtle movement on hover */
  }
</style>
