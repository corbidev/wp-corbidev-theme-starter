import { createApp } from 'vue';
import App from './vue/App.vue';
const el=document.getElementById('app');
if(el){
 const data=JSON.parse(el.dataset.items||'{}');
 createApp(App,{initialData:data}).mount(el);
}
