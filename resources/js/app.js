import 'bootstrap';

import { createApp } from 'vue';
import Router from './views/Router.vue';

// Create app
const app = createApp(Router);

// Ant-design-vue
import Antdv from 'ant-design-vue';
app.use(Antdv);

// Router
import routers from './routes/index';
app.use(routers);

// Vuex
import store from './stores/_loader';
app.use(store);

// Mixin
import errorsHandler from './mixins/errors-handler';
app.mixin(errorsHandler);

// Mount to #app
app.mount('#app');
