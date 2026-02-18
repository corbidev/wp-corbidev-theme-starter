<script setup>
import BoutonSombreClair from './components/BoutonSombreClair.vue'

defineProps({
  initialData: Object
})
</script>

<template>
  <div class="app-wrapper">
    <p class="text-primary">
      {{ initialData?.description }}
    </p>

    <BoutonSombreClair />
  </div>
</template>
