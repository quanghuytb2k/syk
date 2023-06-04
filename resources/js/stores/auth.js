import axios from '@/http-common';
import { authApi } from "@/model/authApi";

export default {
    namespaced: true,
    state() {
        return {
            user: null,
            info: null
        };
    },
    getters: {
        isLogged(state) {
            let user = localStorage.getItem('user')
            state.user = JSON.parse(user)
            return state.user !== null;
        },
        getUser() {
            return JSON.parse(localStorage.getItem('user'));
        }
    },
    mutations: {
        setUser(state, user) {
            user.expires_in = Date.now() + (user.expires_in * 1000)
            localStorage.setItem('user', JSON.stringify(user))
        },

        removeUser(state) {
            return localStorage.removeItem('user')
        }
    },
    actions: {
        async me(state, context) {
            if (!state.state.info) {
                state.state.info = await authApi.post('/api/auth/me')
            }
            return state.state.info
        },

        async login(context, { email, password }) {
            return axios.post('/api/auth/login', {
                email,
                password,
            })
        },

        logout(context) {
            return authApi.post('/api/auth/logout', null)
        },

        async register(context, payload) {
            await axios.post('/api/auth/register', payload);
        },

        async refreshToken() {
            let refreshUser = await authApi.post('/api/auth/refresh-token', null)

            refreshUser.expires_in = Date.now() + (refreshUser.expires_in * 1000)
            localStorage.setItem('user', JSON.stringify(refreshUser))
        },

        activate(context, payload) {
            return authApi.post('/api/auth/activate', payload);
        },

        changepass(context, payload) {
            return authApi.post('/api/auth/changepass', payload);
        },

        forgotpass(context, payload) {
            return authApi.post('/api/auth/forgotpass', payload);
        },

        resetpass(context, payload) {
            return authApi.post('/api/auth/resetpass', payload);
        },
    },
}
