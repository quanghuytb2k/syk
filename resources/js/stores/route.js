export default {
    namespaced: true,
    state() {
        return {
            currentRoute: ['home'],
        }
    },
    mutations: {
        setRoute (state, route) {
            if (route !== undefined) state.currentRoute = [route]
        }
    }
}
