import { createStore } from 'vuex';

const store = createStore();

// Auth store
import auth from './auth';
store.registerModule('auth', auth);

// Prefecture store
import prefecture from './prefecture';
store.registerModule('prefecture', prefecture);

import route from "./route";
store.registerModule('route', route);

import role from "./role";
store.registerModule('role', role);

import accessRole from "./accessRole";
store.registerModule('accessRole', accessRole);

export default store;
