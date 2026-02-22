import { createApp } from 'vue'
import App from './App.vue'
import BoutonSombreClair from './components/BoutonSombreClair.vue'
import './styles/app.css'

import { initTheme } from './theme'

initTheme()

createApp(App).mount('#app')
createApp(BoutonSombreClair).mount('#footer-theme-toggle')