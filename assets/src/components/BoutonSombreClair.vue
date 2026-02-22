<script setup>
import { computed, onMounted, ref } from "vue";
import { getCurrentTheme, toggleTheme } from "../theme";

const isDark = ref(false);

const buttonLabel = computed(() =>
  isDark.value ? "Passer en mode clair" : "Passer en mode sombre",
);

const iconTitle = computed(() =>
  isDark.value ? "Mode sombre activé" : "Mode clair activé",
);

const syncThemeState = () => {
  isDark.value = getCurrentTheme() === "dark";
};

const handleToggle = () => {
  toggleTheme();
  syncThemeState();
};

onMounted(() => {
  syncThemeState();
});
</script>

<template>
  <button
    class="theme-toggle"
    type="button"
    :aria-label="buttonLabel"
    :title="buttonLabel"
    @click="handleToggle"
  >
    <svg
      v-if="isDark"
      class="theme-toggle__icon"
      viewBox="0 0 24 24"
      role="img"
      aria-hidden="true"
    >
      <title>{{ iconTitle }}</title>
      <path
        d="M12 4a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V5a1 1 0 0 1 1-1Zm0 13a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm0 3a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1ZM4 12a1 1 0 0 1 1-1h1a1 1 0 1 1 0 2H5a1 1 0 0 1-1-1Zm14 0a1 1 0 0 1 1-1h1a1 1 0 1 1 0 2h-1a1 1 0 0 1-1-1ZM6.34 6.34a1 1 0 0 1 1.41 0l.71.71a1 1 0 0 1-1.41 1.41l-.71-.71a1 1 0 0 1 0-1.41Zm9.2 9.2a1 1 0 0 1 1.41 0l.71.71a1 1 0 0 1-1.41 1.41l-.71-.71a1 1 0 0 1 0-1.41ZM6.34 17.66a1 1 0 0 1 0-1.41l.71-.71a1 1 0 0 1 1.41 1.41l-.71.71a1 1 0 0 1-1.41 0Zm9.2-9.2a1 1 0 0 1 0-1.41l.71-.71a1 1 0 1 1 1.41 1.41l-.71.71a1 1 0 0 1-1.41 0Z"
      />
    </svg>

    <svg
      v-else
      class="theme-toggle__icon"
      viewBox="0 0 24 24"
      role="img"
      aria-hidden="true"
    >
      <title>{{ iconTitle }}</title>
      <path
        d="M21.64 13a1 1 0 0 0-1.05-.14 8 8 0 0 1-10.45-10.45 1 1 0 0 0-1.19-1.31 10 10 0 1 0 12.55 12.55 1 1 0 0 0 .14-1.05Z"
      />
    </svg>
  </button>
</template>
