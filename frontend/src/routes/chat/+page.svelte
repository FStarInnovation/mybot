<script lang="ts">
  import ChatInterface from '$lib/components/ChatInterface.svelte';
</script>

<div class="chat-page-container">
  <ChatInterface />
</div>

<style>
  .chat-page-container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Full viewport height */
    padding: 1rem; /* Some padding around the chat interface */
    box-sizing: border-box;
    background-color: var(--bg-tertiary); /* A slightly different background for the page */
  }

  /* Ensure ChatInterface itself takes up a good portion of the screen */
  .chat-page-container :global(.chat-interface) {
    width: 100%;
    max-width: 800px; /* Max width for the chat interface */
    height: 90vh;    /* Max height for the chat interface */
    max-height: 900px; /* Max height in pixels */
  }
</style>
