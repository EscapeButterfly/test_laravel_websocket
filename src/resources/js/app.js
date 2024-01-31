import './bootstrap';

import {createApp} from 'vue';
import ChatComponent from "./components/ChatComponent.vue";

const app = createApp({
    el: '#app',
    components: {
        ChatComponent
    },
});

app.mount('#app');
