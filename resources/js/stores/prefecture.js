import axios from '../http-common';

export default {
    namespaced: true,
    state() {
        return {
            data: [],
        };
    },
    getters: {
        data(state) {
            return state.data;
        },
    },
    actions: {
        async get(context) {
            try {
                const res = await axios.post('/prefecture/all');
                context.state.data = res.data.data;
            } catch { }
        },
    },
}
