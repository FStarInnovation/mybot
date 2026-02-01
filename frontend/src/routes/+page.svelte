<script lang="ts">
  import Header from '$lib/components/landing/Header.svelte';
  import Hero from '$lib/components/landing/Hero.svelte';
  import HowItWorks from '$lib/components/landing/HowItWorks.svelte';
  import Features from '$lib/components/landing/Features.svelte';
  import Pricing from '$lib/components/landing/Pricing.svelte';
  import FAQ from '$lib/components/landing/FAQ.svelte';
  import Footer from '$lib/components/landing/Footer.svelte';
</script>

<div class="landing-page">
  <Header />
  <main>
    <Hero />
    <HowItWorks />
    <Features />
    <Pricing />
    <FAQ />
  </main>
  <Footer />
</div>

<style>
  .landing-page {
    min-height: 100vh;
    background: linear-gradient(180deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
  }
  
  main {
    padding-top: 72px;
  }
</style>
