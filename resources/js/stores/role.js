import {authApi} from "../model/authApi";

export default {
    namespaced: true,
    state() {
        return {
            role: null,
        }
    },
    getters: {
        getRole(state) {
            return state.role
        }
    },
    actions: {
        async setRole(state) {
            let role = await authApi.get(`/api/auth/role`)
            state.state.role = role[0]
            return state.state.role
        },

        removeRole(state) {
            state.state.role = null
        }
    }
}
