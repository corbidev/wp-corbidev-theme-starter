<script setup>
defineProps({
  initialData: Object
})
</script>

<template>
  <div class="app-wrapper">
    <p class="text-primary">
      {{ initialData?.description }}
    </p>
  </div>
</template>
