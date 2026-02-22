<script setup>
import { computed, onMounted, ref } from 'vue'
import { getCurrentTheme, toggleTheme } from '../theme'

const isDark = ref(false)

const buttonLabel = computed(() =>
  isDark.value ? 'Passer en mode clair' : 'Passer en mode sombre'
)

const syncThemeState = () => {
  isDark.value = getCurrentTheme() === 'dark'
}

const handleToggle = () => {
  toggleTheme()
  syncThemeState()
}

onMounted(() => {
  syncThemeState()
})
</script>

<template>
  <button class="btn btn-ghost" type="button" @click="handleToggle">
    {{ buttonLabel }}
  </button>
</template>