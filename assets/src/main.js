import { createApp } from 'vue'
import App from './App.vue'
import './styles/app.css'

import { initTheme } from './theme'

initTheme()

createApp(App).mount('#app')