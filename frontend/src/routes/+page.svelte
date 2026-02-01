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
  <div class="gradient-bg">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
  </div>
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
    background: #0f172a;
    color: #fff;
    position: relative;
    overflow-x: hidden;
  }
  
  .gradient-bg {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
  }
  
  .orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(100px);
    opacity: 0.4;
  }
  
  .orb-1 {
    width: 600px;
    height: 600px;
    background: #6366f1;
    top: -200px;
    right: -100px;
  }
  
  .orb-2 {
    width: 400px;
    height: 400px;
    background: #d946ef;
    bottom: 20%;
    left: -100px;
  }
  
  .orb-3 {
    width: 300px;
    height: 300px;
    background: #8b5cf6;
    top: 50%;
    right: 20%;
  }
</style>
