<script setup>
import SiteCard from './SiteCard.vue'
import { toggleTheme } from '../theme'

defineProps({
  sites: {
    type: Array,
    required: true
  }
})
</script>

<template>
  <div>
    <!-- Toggle dark / light -->
    <button
      class="theme-toggle"
      type="button"
      aria-label="Toggle theme"
      @click="toggleTheme"
    >
      🌗
    </button>

    <!-- Cards grid -->
    <section class="cards-grid">
      <SiteCard
        v-for="site in sites"
        :key="site.lien"
        :site="site"
      />
    </section>
  </div>
</template>