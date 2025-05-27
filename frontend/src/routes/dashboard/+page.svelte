<script lang="ts">
  import { onMount } from 'svelte';
  import { fade, slide } from 'svelte/transition';
  
  let stats = [
    { label: 'Total Users', value: '2,543', icon: '👥', change: '+12.5%' },
    { label: 'Active Now', value: '1,284', icon: '🟢', change: '+5.2%' },
    { label: 'Messages', value: '8,421', icon: '💬', change: '+24.1%' },
    { label: 'Response Time', value: '2.4s', icon: '⚡', change: '-18.3%' }
  ];
  
  let recentActivity = [
    { id: 1, user: 'John Doe', action: 'created a new bot', time: '2 min ago' },
    { id: 2, user: 'Jane Smith', action: 'updated bot settings', time: '15 min ago' },
    { id: 3, user: 'Alex Johnson', action: 'deployed new version', time: '1 hour ago' },
    { id: 4, user: 'Sarah Williams', action: 'trained the AI model', time: '3 hours ago' },
    { id: 5, user: 'Michael Brown', action: 'exported conversation data', time: '5 hours ago' }
  ];
  
  let selectedBot = null;
  let isLoading = true;
  
  onMount(() => {
    // Simulate loading data
    const timer = setTimeout(() => {
      isLoading = false;
    }, 1000);
    
    return () => clearTimeout(timer);
  });
</script>

<main class="dashboard">
  <div class="dashboard-header">
    <h1>Dashboard</h1>
    <div class="header-actions">
      <button class="btn btn-primary">
        <span>+</span> New Bot
      </button>
    </div>
  </div>
  
  {#if isLoading}
    <div class="loading-overlay">
      <div class="spinner"></div>
      <p>Loading your dashboard...</p>
    </div>
  {:else}
    <div class="dashboard-content">
      <!-- Stats Cards -->
      <div class="stats-grid">
        {#each stats as stat, i}
          <div class="stat-card" in:fade={{ delay: 100 * i }}>
            <div class="stat-icon">{stat.icon}</div>
            <div class="stat-details">
              <span class="stat-value">{stat.value}</span>
              <span class="stat-label">{stat.label}</span>
            </div>
            <div class="stat-change {stat.change.startsWith('+') ? 'positive' : 'negative'}">
              {stat.change}
            </div>
          </div>
        {/each}
      </div>
      
      <div class="dashboard-grid">
        <!-- Recent Activity -->
        <div class="card recent-activity">
          <div class="card-header">
            <h2>Recent Activity</h2>
            <a href="/activity" class="text-link">View All</a>
          </div>
          <div class="card-body">
            <ul>
              {#each recentActivity as activity, i}
                <li transition:slide={{ delay: 50 * i }}>
                  <div class="activity-item">
                    <div class="activity-details">
                      <span class="user">{activity.user}</span>
                      <span class="action">{activity.action}</span>
                    </div>
                    <span class="time">{activity.time}</span>
                  </div>
                </li>
              {/each}
            </ul>
          </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card quick-actions">
          <div class="card-header">
            <h2>Quick Actions</h2>
          </div>
          <div class="card-body">
            <button class="action-btn" on:click={() => (selectedBot = 'new')}>
              <span class="icon">🤖</span>
              <span>Create New Bot</span>
            </button>
            <button class="action-btn" on:click={() => (selectedBot = 'train')}>
              <span class="icon">🎓</span>
              <span>Train AI Model</span>
            </button>
            <button class="action-btn" on:click={() => (selectedBot = 'deploy')}>
              <span class="icon">🚀</span>
              <span>Deploy Changes</span>
            </button>
            <button class="action-btn" on:click={() => (selectedBot = 'analytics')}>
              <span class="icon">📊</span>
              <span>View Analytics</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  {/if}
  
  {#if selectedBot}
    <div class="modal-overlay" transition:fade>
      <div class="modal" in:slide={{ y: 20 }} out:slide={{ y: -20 }}>
        <button class="close-btn" on:click={() => (selectedBot = null)}>×</button>
        <h2>{
          selectedBot === 'new' ? 'Create New Bot' :
          selectedBot === 'train' ? 'Train AI Model' :
          selectedBot === 'deploy' ? 'Deploy Changes' :
          'View Analytics'
        }</h2>
        <p class="modal-message">
          {selectedBot === 'new' ? 'Create a new chatbot with custom settings and behavior.' :
           selectedBot === 'train' ? 'Train your AI model with the latest conversation data.' :
           selectedBot === 'deploy' ? 'Deploy your latest changes to production.' :
           'View detailed analytics and insights about your bot\'s performance.'}
        </p>
        <div class="modal-actions">
          <button class="btn btn-outline" on:click={() => (selectedBot = null)}>Cancel</button>
          <button class="btn btn-primary">
            { selectedBot === 'new' ? 'Create' : 'Continue' }
          </button>
        </div>
      </div>
    </div>
  {/if}
</main>

<style>
  .dashboard {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
    position: relative;
    min-height: 100vh;
  }
  
  .dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
  }
  
  .dashboard-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
  }
  
  .header-actions .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    background-color: #3b82f6;
    color: white;
    transition: all 0.2s ease;
  }
  
  .header-actions .btn:hover {
    background-color: #2563eb;
    transform: translateY(-1px);
  }
  
  .header-actions .btn span {
    font-size: 1.25rem;
    line-height: 1;
  }
  
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
  }
  
  .stat-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  
  .stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  }
  
  .stat-icon {
    font-size: 1.75rem;
    width: 3rem;
    height: 3rem;
    border-radius: 0.5rem;
    background: #e0f2fe;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0369a1;
  }
  
  .stat-details {
    flex: 1;
  }
  
  .stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    line-height: 1.2;
  }
  
  .stat-label {
    font-size: 0.875rem;
    color: #6b7280;
  }
  
  .stat-change {
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 1rem;
  }
  
  .stat-change.positive {
    background-color: #dcfce7;
    color: #166534;
  }
  
  .stat-change.negative {
    background-color: #fee2e2;
    color: #991b1b;
  }
  
  .dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
  }
  
  .card {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }
  
  .card-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .card-header h2 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
  }
  
  .text-link {
    color: #3b82f6;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: color 0.2s ease;
  }
  
  .text-link:hover {
    color: #2563eb;
    text-decoration: underline;
  }
  
  .card-body {
    padding: 1.5rem;
  }
  
  .recent-activity ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  .recent-activity li {
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
  }
  
  .recent-activity li:last-child {
    border-bottom: none;
  }
  
  .activity-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .activity-details {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
  }
  
  .user {
    font-weight: 600;
    color: #1f2937;
  }
  
  .action {
    color: #4b5563;
  }
  
  .time {
    font-size: 0.875rem;
    color: #9ca3af;
  }
  
  .quick-actions .card-body {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
  }
  
  .action-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    width: 100%;
    padding: 1rem;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    cursor: pointer;
    text-align: left;
    transition: all 0.2s ease;
  }
  
  .action-btn:hover {
    background-color: #f9fafb;
    border-color: #d1d5db;
    transform: translateY(-1px);
  }
  
  .action-btn .icon {
    font-size: 1.25rem;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .action-btn span:last-child {
    flex: 1;
    font-weight: 500;
    color: #1f2937;
  }
  
  .loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 10;
  }
  
  .spinner {
    width: 3rem;
    height: 3rem;
    border: 3px solid #e5e7eb;
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 1rem;
  }
  
  .loading-overlay p {
    color: #4b5563;
    font-size: 1.125rem;
    margin-top: 1rem;
  }
  
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
  }
  
  .modal {
    background: white;
    border-radius: 0.75rem;
    width: 100%;
    max-width: 28rem;
    padding: 1.5rem;
    position: relative;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  }
  
  .close-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 1.5rem;
    line-height: 1;
    cursor: pointer;
    color: #6b7280;
    padding: 0.25rem;
    border-radius: 0.25rem;
  }
  
  .close-btn:hover {
    background-color: #f3f4f6;
    color: #1f2937;
  }
  
  .modal h2 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 1rem 0;
  }
  
  .modal-message {
    color: #4b5563;
    margin-bottom: 1.5rem;
    line-height: 1.625;
  }
  
  .modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-top: 1.5rem;
  }
  
  .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid transparent;
  }
  
  .btn-primary {
    background-color: #3b82f6;
    color: white;
    border-color: #3b82f6;
  }
  
  .btn-primary:hover {
    background-color: #2563eb;
    border-color: #1d4ed8;
  }
  
  .btn-outline {
    background-color: white;
    color: #3b82f6;
    border-color: #d1d5db;
  }
  
  .btn-outline:hover {
    background-color: #f9fafb;
    border-color: #9ca3af;
  }
  
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
  
  @media (max-width: 1024px) {
    .dashboard-grid {
      grid-template-columns: 1fr;
    }
  }
  
  @media (max-width: 768px) {
    .dashboard {
      padding: 1rem;
    }
    
    .stats-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  
  @media (max-width: 480px) {
    .stats-grid {
      grid-template-columns: 1fr;
    }
    
    .dashboard-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }
    
    .header-actions {
      width: 100%;
    }
    
    .header-actions .btn {
      width: 100%;
      justify-content: center;
    }
  }
</style>
